<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function tracking(Request $request)
    {
        // Define validation rules
        $rules = [
            'cn_no' => 'required|string|max:100',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $cn_no = $request->cn_no;

        $response = Http::withHeaders([
            'api-key' => 'd345tf26ghe-93GLS1uwem-dEkLx320q',
        ])->get("https://tms.omile.id/gls/restapi/basic/tracking/list?id=".$cn_no);

        // Check if the request was successful
        if ($response->successful()) {
            // Decode the JSON response into an array
            $data = $response->json();

            // Return the data or pass it to a view
            return view('tracking', $data);
        } else {
            // Handle the error
            return view('tracking-nodata');
        }
    }

    public function provinces(Request $request)
    {
        // Define validation rules
        $rules = [
            'prov' => 'required|string',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $prov = $request->prov;

        $response = Http::withHeaders([
            'api-key' => 'd345tf26ghe-93GLS1uwem-dEkLx320q',
        ])->get("https://tms.omile.id/gls/restapi/basic/city/list?prov_name=".$prov);

        // Check if the request was successful
        if ($response->successful()) {
            $data = $response->json();
            // Convert the 'data' array to a collection
            $collection = collect($data['data']);

            // Use the unique method to remove duplicate provinces
            $uniqueProvinces = $collection->unique('PROVINCE_NAME')->values()->all();

            // Update the original data array
            $data['data'] = $uniqueProvinces;
            // Return the data or pass it to a view
            return $data;
        } else {
            // Handle the error
            return $response;
        }
    }

    public function cities(Request $request)
    {
        // Define validation rules
        $rules = [
            'prov' => 'required|string',
            'city' => 'required|string',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $prov = $request->prov;
        $city = $request->city;

        $response = Http::withHeaders([
            'api-key' => 'd345tf26ghe-93GLS1uwem-dEkLx320q',
        ])->get("https://tms.omile.id/gls/restapi/basic/city/list?prov_name=$prov&city_name=$city");

        // Check if the request was successful
        if ($response->successful()) {
            $data = $response->json();
            // Convert the 'data' array to a collection
            $collection = collect($data['data']);

            // Use the unique method to remove duplicate provinces
            $uniqueProvinces = $collection->unique('CITY_NAME')->values()->all();

            // Update the original data array
            $data['data'] = $uniqueProvinces;
            // Return the data or pass it to a view
            return $data;
        } else {
            // Handle the error
            return $response;
        }
    }

    public function geo(Request $request)
    {
        // Define validation rules
        $rules = [
            'prov' => 'required|string',
            'city' => 'required|string',
            'name' => 'required|string',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $prov = $request->prov;
        $city = $request->city;
        $name = $request->name;

        $response = Http::withHeaders([
            'api-key' => 'd345tf26ghe-93GLS1uwem-dEkLx320q',
        ])->get("https://tms.omile.id/gls/restapi/basic/city/list?prov_name=$prov&city_name=$city&name=$name");

        // Check if the request was successful
        if ($response->successful()) {
            $data = $response->json();
            // Return the data or pass it to a view
            return $data;
        } else {
            // Handle the error
            return $response;
        }
    }

    public function rates(Request $request)
    {
        // Define validation rules
        $rules = [
            'from' => 'required|string',
            'to' => 'required|string',
            'weight' => 'required',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $from = $request->from;
        $to = $request->to;
        $weight = $request->weight;
        $body = [
            "from" => $from,
            "thru" => $to,
            "weight" => $weight
        ];
        $url = "https://tms.omile.id/gls/restapi/basic/price/list_all_ltime";

        $response = Http::withHeaders([
            'api-key' => 'd345tf26ghe-93GLS1uwem-dEkLx320q',
        ])->post($url, $body);

        // Check if the request was successful
        if ($response->successful()) {
            $data = $response->json();
            // Return the data or pass it to a view
            return $data;
        } else {
            // Handle the error
            return $response;
        }
    }
}
