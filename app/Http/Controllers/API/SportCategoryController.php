<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SportCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SportCategoryController extends Controller
{
    //
    public function getCategories(Request $request)
    {
        try {
            $isPaginate = !empty($request->is_paginate) ? filter_var($request->query('is_paginate'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : true;
            $search = $request->search;

            if ($isPaginate) {
                $categories = SportCategory::where('name', 'like', '%'.$search.'%')->paginate($request->per_page ?? 15);
            } else {
                $categories = SportCategory::get();
            }
            //return successful response
            return response()->json(['error' => false, 'result' => $categories], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function createCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()], 406);
        }

        $user = Auth::user();

        try {
            $category = new SportCategory();
            $category->name = $request->input('name');
            $category->save();

            $category = SportCategory::where('id', $category->id)->first();

            //return successful response
            return response()->json(['error' => false, 'result' => $category, 'message' => 'data saved'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function updateCategory($categoryId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()], 406);
        }

        try {
            $category = SportCategory::find($categoryId);
            if (!$category) {
                return response()->json(['error' => true, 'message' => 'Category not found'], 406);
            }
            $category->name = $request->input('name');

            $category->save();

            //return successful response
            return response()->json(['error' => false, 'result' => $category, 'message' => 'data saved'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function deleteCategory($categoryId)
    {
        try {
            SportCategory::where('id', $categoryId)->delete();
            //return successful response
            return response()->json(['error' => false, 'message' => 'data deleted'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }
}
