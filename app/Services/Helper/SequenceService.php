<?php

namespace App\Services\Helper;

use App\Models\Config\Sequence;
use App\Models\Config\SequenceFormat;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class SequenceService
{
    public function get($prefix, $date, $entity = '')
    {
        $data = [
            'prefix'   => $prefix,
            'date'     => $date,
            'entity'   => $entity
        ];
        $validation = Validator::make($data, [
            'prefix'    => 'required|max:20',
            'date'      => 'required|date',
            'entity'    => 'nullable',
        ]);

        if ($validation->fails()) {
            return ["success" => false, "message" => $validation->messages()];
        }

        $validated_data = $validation->validated();
        // dd($validated_data);

        $format = $this->get_format($validated_data['prefix']);
        if (!empty($format['success']) && $format['success'] == false) {
            return $format;
        }
        if(!empty($format->monthly_reset))
        {
            $month = date("m", strtotime($validated_data['date']));
            $year = date("Y", strtotime($validated_data['date']));
        }
        else
        {
            $month = 0;
            $year = 0;
        }

        $seq_data = Sequence::where('sequence_name', $format->sequence_name)
            ->where('year', $year)
            ->where('month', $month);
        if ($validated_data['entity'] != null && $validated_data['entity'] != "") {
            $seq_data = $seq_data->where('entitycode', $validated_data['entity']);
        } else
            $seq_data->where(function ($seq_data) {
                $seq_data->where('entitycode', '')
                    ->orWhere('entitycode', null);
            });

        $seq_data = $seq_data->first();
        // dd($seq_data);
        if (is_null($seq_data)) {
            $insert_seq = array(
                "sequence_prefix" => $format->sequence_prefix,
                "entitycode" => $validated_data['entity'],
                "sequence_name" => $format->sequence_name,
                "year" => $year,
                "month" => $month,
                "sequence_length" => $format->sequence_length,
                "sequence_number" => 0,
                "increment" => 1,
                "min_value" => 1,
                "max_value" => 99999999999,
                "cur_value" => 2,
                "cycle" => 0,
                "is_active" => '1',
            );
            $insert = Sequence::create($insert_seq);
            $sequence = [
                "sequence_prefix" => $format->sequence_prefix,
                "entitycode" => $validated_data['entity'],
                "year" => ($year > 0) ? $year : null,
                "month" => ($month > 0) ? $month : null,
                "number" => str_pad('1', $format->sequence_length, "0", STR_PAD_LEFT)
            ];

            $ret =  (isset($sequence[$format->sequence_format1]) ? $sequence[$format->sequence_format1] : '') .
                (isset($sequence[$format->sequence_format2]) ? $sequence[$format->sequence_format2] : '') .
                (isset($sequence[$format->sequence_format3]) ? $sequence[$format->sequence_format3] : '') .
                (isset($sequence[$format->sequence_format4]) ? $sequence[$format->sequence_format4] : '') .
                (isset($sequence[$format->sequence_format5]) ? $sequence[$format->sequence_format5] : '');
        } else {
            $update = Sequence::where('sequence_name', $format->sequence_name)
                ->where('year', $year)
                ->where('month', $month)
                ->update([
                    "cur_value" => ($seq_data->cur_value + 1),
                    "updated_at" => now()
                ]);
            $sequence = [
                "sequence_prefix" => $seq_data->sequence_prefix,
                "entitycode" => $seq_data->entitycode,
                "year" => ($year > 0) ? $year : null,
                "month" => ($month > 0) ? $month : null,
                "number" => str_pad($seq_data->cur_value, $seq_data->sequence_length, $seq_data->sequence_number, STR_PAD_LEFT)
            ];
            $ret =  (isset($sequence[$format->sequence_format1]) ? $sequence[$format->sequence_format1] : '') .
                (isset($sequence[$format->sequence_format2]) ? $sequence[$format->sequence_format2] : '') .
                (isset($sequence[$format->sequence_format3]) ? $sequence[$format->sequence_format3] : '') .
                (isset($sequence[$format->sequence_format4]) ? $sequence[$format->sequence_format4] : '') .
                (isset($sequence[$format->sequence_format5]) ? $sequence[$format->sequence_format5] : '');
        }
        return $ret;
    }

    private function get_format($prefix_name)
    {
        $seq_format = SequenceFormat::where('sequence_name', $prefix_name)
            ->first();

        if (empty($seq_format)) {
            return ["success" => false, "message" => "Sequence Not Found."];
        }
        return $seq_format;
    }

    public function save(array $data, SequenceFormat $sequence = null)
    {
        // dd($sequence);
        $user_id = auth()->user()->id;
        // dd($data);
        $validation = Validator::make($data, [
            'sequence_format1' => 'required',
            'sequence_format2' => 'required',
            'sequence_format3' => 'required',
            'sequence_format4' => 'required',
            'sequence_format5' => 'required',
            'sequence_prefix' => 'required',
            'sequence_length' => 'required',
        ]);

        if ($validation->fails()) {
            return ["success" => false, "message" => $validation->messages()];
        }

        $validated_data = $validation->validated();

        if (!$sequence) {
            $validated_data['activated_at'] = now();
            $validated_data['activated_by'] = $user_id;
            $validated_data['created_by'] = $user_id;
        }

        $validated_data['updated_at'] = $sequence ? now() : null;
        $validated_data['updated_by'] = $sequence ? $user_id : null;

        if ($sequence) {
            return $sequence->update($validated_data);
        }
        // dd($data);
        return $sequence;
    }

    public function update(SequenceFormat $sequence, array $data)
    {
        return $this->save($data, $sequence);
    }
}
