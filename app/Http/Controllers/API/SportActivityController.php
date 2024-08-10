<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SportActivity;
use App\Models\SportActivityParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SportActivityController extends Controller
{

    public function getSportActivities(Request $request)
    {
        try {
            $isPaginate = !empty($request->is_paginate) ? filter_var($request->query('is_paginate'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : true;
            $search = $request->search;
            $sport_category_id = $request->sport_category_id;
            $city_id = $request->city_id;

            $query = SportActivity::select('id', 'sport_category_id', 'city_id', 'user_id', 'title', 'price', 'price_discount', 'slot', 'address', 'activity_date', 'start_time', 'end_time', 'created_at', 'updated_at')
                ->with([
                    'organizer' => function ($query) {
                        $query->select('id', 'name', 'email');
                    },
                    'city',
                    'sport_category',
                    'participants' => function ($query) {
                        $query->select('id', 'sport_activity_id', 'user_id')
                            ->with(['user' => function ($query) {
                                $query->select('id', 'name', 'email');
                            }]);
                    }
                ]);

            if (!empty($city_id)) {
                $query->where('city_id', '=', $city_id);
            }

            if (!empty($sport_category_id)) {
                $query->where('sport_category_id', '=', $sport_category_id);
            }

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

    public function getSportActivityById($sportActivityId)
    {
        try {
            $query = SportActivity::select('id', 'sport_category_id', 'city_id', 'user_id', 'title', 'description', 'price', 'price_discount', 'slot', 'address', 'map_url', 'activity_date', 'start_time', 'end_time', 'created_at', 'updated_at')
                ->with([
                    'organizer' => function ($query) {
                        $query->select('id', 'name', 'email');
                    },
                    'city',
                    'sport_category',
                    'participants' => function ($query) {
                        $query->select('id', 'sport_activity_id', 'user_id')
                            ->with(['user' => function ($query) {
                                $query->select('id', 'name', 'email');
                            }]);
                    }
                ])
                ->where('id', $sportActivityId)->first();

            //return successful response
            return response()->json(['error' => false, 'result' => $query], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function createSportActivity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sport_category_id' => 'required|integer',
            'city_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'price_discount' => 'nullable|integer|min:0|lt:price',
            'slot' => 'required|integer|min:1',
            'address' => 'required|string',
            'map_url' => 'required|url',
            'activity_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()], 406);
        }

        $user = Auth::user();

        try {
            $sport_activity = new SportActivity();
            $sport_activity->user_id = $user->id;
            $sport_activity->sport_category_id = $request->input('sport_category_id');
            $sport_activity->city_id = $request->input('city_id');
            $sport_activity->title = $request->input('title');
            $sport_activity->description = $request->input('description');
            $sport_activity->price = $request->input('price');
            $sport_activity->price_discount = $request->input('price_discount');
            $sport_activity->slot = $request->input('slot');
            $sport_activity->address = $request->input('address');
            $sport_activity->map_url = $request->input('map_url');
            $sport_activity->activity_date = $request->input('activity_date');
            $sport_activity->start_time = $request->input('start_time');
            $sport_activity->end_time = $request->input('end_time');

            $sport_activity->save();

            //return successful response
            return response()->json(['error' => false, 'result' => $sport_activity, 'message' => 'data saved'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function updateSportActivity($sportActivityId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sport_category_id' => 'required|integer',
            'city_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'price_discount' => 'nullable|integer|min:0|lt:price',
            'slot' => 'required|integer|min:1',
            'address' => 'required|string',
            'map_url' => 'required|url',
            'activity_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()], 406);
        }

        try {
            $user = Auth::user();

            $sport_activity = SportActivity::find($sportActivityId);
            if (!$sport_activity) {
                return response()->json(['error' => true, 'message' => 'Sport Activity not found'], 406);
            }

            $participantCount = SportActivityParticipant::where('sport_activity_id', $sportActivityId)->count();
            if ($sport_activity->slot < $participantCount) {
                return response()->json(['error' => true, 'message' => 'Slot cannot be lower than the total number of participants'], 406);
            }

            $sport_activity->user_id = $user->id;
            $sport_activity->sport_category_id = $request->input('sport_category_id');
            $sport_activity->city_id = $request->input('city_id');
            $sport_activity->title = $request->input('title');
            $sport_activity->description = $request->input('description');
            $sport_activity->price = $request->input('price');
            $sport_activity->price_discount = $request->input('price_discount');
            $sport_activity->slot = $request->input('slot');
            $sport_activity->address = $request->input('address');
            $sport_activity->map_url = $request->input('map_url');
            $sport_activity->activity_date = $request->input('activity_date');
            $sport_activity->start_time = $request->input('start_time');
            $sport_activity->end_time = $request->input('end_time');

            $sport_activity->save();

            //return successful response
            return response()->json(['error' => false, 'result' => $sport_activity, 'message' => 'data saved'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function deleteSportActivity($sportActivityId)
    {
        try {
            $sport_activity = SportActivity::find($sportActivityId);
            if (!$sport_activity) {
                return response()->json(['error' => true, 'message' => 'Sport Activity not found'], 406);
            }

            $participantCount = SportActivityParticipant::where('sport_activity_id', $sportActivityId)->count();
            if ($participantCount > 0) {
                return response()->json(['error' => true, 'message' => 'Sport Activity cannot be deleted, there are registered participants'], 406);
            }

            $sport_activity->delete();
            
            //return successful response
            return response()->json(['error' => false, 'message' => 'Data deleted successfully'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }
}
