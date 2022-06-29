<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use DB;
use Validator;


class CoreController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request=NULL)
    {
        $this->db = new DB;
    }

    public function list($tabel,$request){
        $select = $request['select'];
        $where = $request['where'];
        $group = $request['group'];
        $order = $request['order'];
        $limit = $request['limit'];
        $offset = $request['offset'];
       
       
        

        // return response()->json([    
        //     //$request['limit']
        //     // $select,
        //     $request,
        //    // $data,
        //     // $limit
        //       ], 404);
        
            
        
         if ($tabel) {
            $query  = DB::table($tabel);    
            $query->selectRaw($select);
            $query->whereRaw($where);
            //$query->groupByRaw($group);
            $query->orderByRaw($order);
            $query->take($limit);
            $query->offset($offset);
            $data = array();
            $data = $query->get();   
            // if ($query ) {
            //     $query  = DB::select("SELECT COUNT(nama_kolom) FROM nama_table");  
            // }  
            if (!$data) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Data Kosong',
                    "result" => null
                ], 404);
            } else {
                return response()->json([
                    'code' => 200,
                    'message' => 'OK',
                    "result" => $data
                ], 200);
            }
        }  else {
            return response()->json([
                'code' => 501,
                'message' => 'Tabel Tidak ditemukan.!',
                "result" => [
                    "Table" => $tabel,
                ]
            ], 501);
        }     
        // if ($tabel) {
        //     $limit = $request['limit'];
        //     $select = $request['select'];
        //     $query  = DB::table($tabel);    
        //     $query->take($limit);  
        //     $query->select($select);
        //     $query->where('kode' , 'user');
        //     $data = array();
        //     $data = $query->get();     
        //     if (!$data) {
        //         return response()->json([
        //             'code' => 404,
        //             'message' => 'Data Kosong',
        //             "result" => null
        //         ], 404);
        //     } else {
        //         return response()->json([
        //             'code' => 200,
        //             'message' => 'OK',
        //             "result" => $data
        //         ], 200);
        //     }
        // }  else {
        //     return response()->json([
        //         'code' => 501,
        //         'message' => 'Tabel Tidak ditemukan.!',
        //         "result" => [
        //             "Table" => $tabel,
        //         ]
        //     ], 501);
        // }     
    }
    

}
