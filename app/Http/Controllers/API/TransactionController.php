<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SportActivity;
use App\Models\SportActivityParticipant;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    function generateInvoiceId()
    {
        $prefix = "INV";
        $datePart = date('Ymd'); // Format the date as YYYYMMDD
        $randomNumber = rand(100000, 999999); // Generate a random 6-digit number
        return "{$prefix}/{$datePart}/{$randomNumber}";
    }

    public function getMyTransaction(Request $request)
    {
        try {
            $isPaginate = !empty($request->is_paginate) ? filter_var($request->query('is_paginate'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : true;
            $search = $request->search;

            $user = Auth::user();

            $query = Transaction::with([
                'transaction_items',
            ])->where('user_id', $user->id);

            if (!empty($search)) {
                $query->where('title', 'like', '%' . $search . '%');
            }

            if ($isPaginate) {
                $activities = $query->paginate($request->per_page ?? 15);
            } else {
                $activities = $query->get();
            }
            //return successful response
            return response()->json(['error' => false, 'result' => $activities], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function getAllTransaction(Request $request)
    {
        try {
            $isPaginate = !empty($request->is_paginate) ? filter_var($request->query('is_paginate'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : true;
            $search = $request->search;

            $query = Transaction::with([
                'transaction_items',
            ]);

            if (!empty($search)) {
                $query->where('title', 'like', '%' . $search . '%');
            }

            if ($isPaginate) {
                $activities = $query->paginate($request->per_page ?? 15);
            } else {
                $activities = $query->get();
            }
            //return successful response
            return response()->json(['error' => false, 'result' => $activities], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function getTransactionById($transactionId, Request $request)
    {
        try {
            $query = Transaction::with(['transaction_items',])
                ->where('id', $transactionId)
                ->first();

            //return successful response
            return response()->json(['error' => false, 'result' => $query], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function createTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_method_id' => 'required|integer',
            'sport_activity_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()], 406);
        }
        $user = Auth::user();
        $sport_activity_id = $request->input('sport_activity_id');
        try {
            // Start the database transaction
            DB::beginTransaction();

            $sport_activity = SportActivity::findOrFail($sport_activity_id);

            $invoice_id = $this->generateInvoiceId();
            $order_date = now(); // Use Laravel's helper for current date and time
            $expired_date = now()->addHours(24); // Add 24 hours to current date and time

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->payment_method_id = $request->input('payment_method_id');
            $transaction->invoice_id = $invoice_id;
            $transaction->status = 'pending';
            $transaction->total_amount = $sport_activity->price;
            $transaction->order_date = $order_date;
            $transaction->expired_date = $expired_date;
            $transaction->save();

            $items = new TransactionItem();
            $items->transaction_id = $transaction->id;
            $items->sport_activity_id = $request->input('sport_activity_id');
            $items->title = $sport_activity->title;
            $items->price = $sport_activity->price;
            $items->price_discount = $sport_activity->price_discount;
            $items->save();

            // If everything is successful, commit the transaction
            DB::commit();

            //return successful response
            return response()->json(['error' => false, 'result' => $transaction, 'message' => 'Transaction Created'], 200);
        } catch (\Exception $e) {
            // Rollback the transaction if anything fails
            DB::rollBack();

            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function updateTransactionProofPayment($transactionId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'proof_payment_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()], 406);
        }

        try {
            $transaction = Transaction::findOrFail($transactionId);
            $transaction->proof_payment_url = $request->input('proof_payment_url');
            $transaction->save();

            //return successful response
            return response()->json(['error' => false, 'message' => 'Transaction Updated'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function updateTransactionStatus($transactionId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:success,failed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()], 406);
        }

        $status = $request->input('status');

        try {
            // Start the database transaction
            DB::beginTransaction();

            $transaction = Transaction::with(['transaction_items',])
                ->findOrFail($transactionId);

            if ($status === 'success') {
                $participant = new SportActivityParticipant();
                $participant->user_id = $transaction->user_id;
                $participant->sport_activity_id = $transaction->transaction_items->sport_activity_id;
                $participant->save();
            }

            $transaction->status = $status;
            $transaction->save();

            // If everything is successful, commit the transaction
            DB::commit();

            //return successful response
            return response()->json(['error' => false, 'message' => 'Transaction Updated'], 200);
        } catch (\Exception $e) {
            // Rollback the transaction if anything fails
            DB::rollBack();

            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function cancelTransaction($transactionId)
    {
        try {
            $transaction = Transaction::findOrFail($transactionId);

            if ($transaction->status !== 'pending') {
                return response()->json(['error' => true, 'message' => "Failed to cancel transaction, only 'pending' status are allowed"], 406);
            }

            $transaction->status = 'cancelled';
            $transaction->save();

            //return successful response
            return response()->json(['error' => false, 'message' => 'Transaction Updated'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }
}
