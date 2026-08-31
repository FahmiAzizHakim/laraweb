<?php

namespace App\Services\Helper;

use DB;
use Illuminate\Support\Facades\Http;
use App\Models\Masterdata\Geolocation\Rajaongkir;
use App\Models\Masterdata\Geolocation\Districts;

class RajaOngkirService
{
    public function mappingdistrict($params)
    {
        $header = [
            "key" => env('RAJAONGKIR_KEY')
        ];
        $url = "https://pro.rajaongkir.com/api/subdistrict";
        $body = ["city" => $params['city']];

        $response = Http::withHeaders($header)->get($url, $body)->json();

        if(!isset($response))
            return ["success" => false];
        
        $res = $response['rajaongkir']['results'];
        
        if(!isset($res))
            return ["success" => false, "message" => "empty data"];

        DB::beginTransaction();
        $data = [];
        $district = [];
        foreach($res as $val)
        {
            $district = Districts::where('district_name', $val['subdistrict_name'])->first();
            // if(!isset($district))
            //     return ["success" => false, "data" => $district];
            $data['province_id'] = $val['province_id'];
            $data['city_id'] = $val['city_id'];
            $data['subdistrict_id'] = $val['subdistrict_id'];
            $data['district_code'] = (isset($district->district_code)) ? $district->district_code : null;
            $data['district_name'] = $val['subdistrict_name'];
            $mapping = Rajaongkir::create($data);
        }
        DB::commit();
        
        return ["success" => true, "data" => $response['rajaongkir']['results']];
    }

    public function getCost($params)
    {
        $header = [
            "key" => env('RAJAONGKIR_KEY')
        ];
        $url = "https://pro.rajaongkir.com/api/cost";
        $origin = Rajaongkir::where("district_code", $params['origin'])->first();
        $destination = Rajaongkir::where("district_code", $params['destination'])->first();
        $body = [
                "origin"            => $origin->subdistrict_id,
                "originType"        => "subdistrict",
                "destination"       => $destination->subdistrict_id,
                "destinationType"   => "subdistrict",
                "weight"            => $params['weight'],
                "courier"           => $params['courier'],
                "length"            => (isset($params['length'])) ? $params['length'] : 0,
                "height"            => (isset($params['height'])) ? $params['height'] : 0,
                "width"             => (isset($params['width'])) ? $params['width'] : 0
            ];

        $response = Http::withHeaders($header)->post($url, $body)->json();

        if(!isset($response))
            return ["success" => false, "message" => "No Data Found"];
        
        if($response['rajaongkir']['status']['code'] != '200')
            return ["success" => false, "message" => $response['rajaongkir']['status']['description']];
        
        return ["success" => true, "data" => $response['rajaongkir']['results']];
    }

    public function tracking($cn_no, $courier)
    {
        $header = [
            "key" => env('RAJAONGKIR_KEY')
        ];
        $url = "https://pro.rajaongkir.com/api/waybill";
        $body = [
                "waybill"   => $cn_no,
                "courier"   => $courier
            ];

        $response = Http::withHeaders($header)->post($url, $body)->json();

        if(!isset($response))
            return ["success" => false, "message" => "No Data Found"];
        
        if($response['rajaongkir']['status']['code'] != '200')
            return ["success" => false, "message" => $response['rajaongkir']['status']['description']];
        
        return ["success" => true, "data" => $response['rajaongkir']['result']];
    }
}