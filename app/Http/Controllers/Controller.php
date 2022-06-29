<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Std;
use Illuminate\Support\Facades\DB;
class Controller extends BaseController
{
    public function index($tabel){

        if ($tabel) {
            $query = DB::table($tabel)->paginate(20);
            
            $results = $query;
            
            if ($results != null) {
                return response()->json([
                    'success' => true,
                    'message' => 'Request Sukses',
                    'result' => $results 
                    ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Request Sukses',
                    'data' => null
                ], 200);
            }
        }  else {
            return response()->json([
                'status' => false,
                'message' => 'Table not found',
                'data' => ''
            ], 404);
        }     
    }
}
