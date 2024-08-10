<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Province;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function getProvinces(Request $request)
    {
        try {
            $isPaginate = !empty($request->is_paginate) ? filter_var($request->query('is_paginate'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : true;
            $search = $request->search;

            if ($isPaginate) {
                $provinces = Province::where('province_name', 'like', '%'.$search.'%')->paginate($request->per_page ?? 15);
            } else {
                $provinces = Province::get();
            }
            //return successful response
            return response()->json(['error' => false, 'result' => $provinces], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function getCitiesByProvinceId($provinceId, Request $request)
    {
        try {
            $isPaginate = !empty($request->is_paginate) ? filter_var($request->query('is_paginate'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : true;
            $search = $request->search;

            if ($isPaginate) {
                $cities = City::where('city_name', 'like', '%'.$search.'%')->where('province_id', $provinceId)->paginate($request->per_page ?? 15);
            } else {
                $cities = City::where('province_id', $provinceId)->get();
            }
            //return successful response
            return response()->json(['error' => false, 'result' => $cities], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }

    public function getCities(Request $request)
    {
        try {
            $isPaginate = !empty($request->is_paginate) ? filter_var($request->query('is_paginate'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : true;
            $search = $request->search;

            if ($isPaginate) {
                $cities = City::where('city_name', 'like', '%'.$search.'%')->with(relations:["province"])->paginate($request->per_page ?? 15);
            } else {
                $cities = City::with(relations:["province"])->get();
            }
            //return successful response
            return response()->json(['error' => false, 'result' => $cities], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['error' => true, 'message' => $e->getMessage()], 406);
        }
    }
}
