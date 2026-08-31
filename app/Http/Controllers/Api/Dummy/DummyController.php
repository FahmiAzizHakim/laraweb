<?php

namespace App\Http\Controllers\Api\Dummy;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DummyController extends Controller
{
  public function index()
  {
    return $request->all();
  }

  public function show(Request $request)
  {
    $params = $request->validate([
      'dummy_no' => 'required|string|max:30'
    ]);
    $dummy = [
            'dummy_no'    => $request->dummy_no,
            'dummy_name'  => 'dummy test file',
            'dummy_int'   => 5,
            'dummy_type'  => 'DUMMY',
            'dummy_label' => 'data',
            'dummy_date'  => '2026-01-01',
            'is_active'   => true
    ];
    
    return response()->json([
            'data' => $dummy,
        ]);
  }

  public function insert(Request $request)
  {
    $params = $request->validate([
            'dummy_no'       => 'string|max:30',
            'dummy_name'      => 'string',
            'dummy_int'   => 'integer',
            'dummy_type' => 'string',
            'dummy_label' => 'string',
            'dummy_date' => 'date',
            'is_active'  => 'boolean',
    ]);
    return response()->json([
            'data' => $params,
        ]);
  }
}