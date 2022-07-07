<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use App\Models\CoreModel;

use DB;
use Validator;


class CoreController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $model;
    private $_namaView;
    public function __construct(Request $request)
    {
        $this->model = new CoreModel;
        $this->model->key_field = '';
        $this->_namaView;
    }
    
    /*cek validator*/
    protected function validasi(Request $request)
    {
        $messages = [];
                                            
        $validator = Validator::make($request->all(), $this->rules,$messages);

        if ($validator->fails()) 
        {
            
            $this->out = 
            [
                "code"    => 204,
                "message" => "Invalid Parameter",
                "result"  => $validator->errors()
            ];

            return $validator->errors();
        }
        else
        {
            return [];
        }
    }   

   
    public function fromCamelCase($input) 
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
          $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        $result = implode('_', $ret);
        //$result = "pmb_trn" . "_" . $result;
        //$result = "s"."v" . "_" . $result;
        // die($result);
        return $result;
    }
    // public function jumlahData($table_name,$where,$group){
    //     $where = $where;
    //     $group = $group;
    //     return $this->model::getJumlah($table_name,$where,$group);
    // }
    public function listData($data,$table_name)
    {        

       if ($table_name) {
            $result = $this->model::getData($table_name,$data);                 
            if ($result) {
                return response()->json([
                    'code' => 200,
                    // 'jumlah_data' => $jumlah_data,
                    'message' => 'OK',
                    "result" => $result
                ], 200);
            } else {
                return response()->json([
                    'code' => 404,
                    'message' => 'Data Kosong',
                    "result" => null
                ], 404);
            } 
        } else {
            return response()->json([
                'code' => 501,
                'message' => 'Tabel Tidak ditemukan.!',
                "result" => [
                    "Table" => $table_name,
                ]
            ], 502);
        }  
    }
    public function createData(Request $request,$table_name = null)
    { 
        
        $input  = $this->model::initColumnForInput($request,'kodec',$table_name);
        $this->rules = $this->model::initColumnForRules('kodec',$table_name,'create');
        // if (!empty($input))
        // {
        //     $data = array_merge($data,$input);
        // }
        /*validator*/
        $validasi = $this->validasi($request);
        if ( $validasi) {
            return response()->json([
                'code' => 422,
                'message' => 'Invalid Parameter',
                "result" => $validasi
            ], 422);                     
        } 
        
        if ($table_name) {
            //die($input['kode']);
            $result = $this->model::create($table_name,$input); 
            if (!$result) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Data Kosong',
                    "result" => null
                ], 404);
            } else {
                return response()->json([
                    'code' => 201,
                    'message' => 'Insert Data Berhasil',
                    "result" => $input
                ], 201);
            }    
        } else {
            return response()->json([
                'code' => 501,
                'message' => 'Tabel Tidak ditemukan.!',
                "result" => [
                    "Table" => $table_name,
                ]
            ], 502);
        }                    
    }
    public function modifyData(Request $request,$table_name = null,$table_varians = null)
    { 
        $input  = $this->model::initColumnForInput($request,'kodec',$table_name);

        /*set validation*/
        if ($table_varians == 'master' ) {
            
            $this->validate($request,[
                'kode' => 'required',
            ]);
            $this->model->key_field = 'kode';
        }
         elseif ($table_varians = 'referensi') {
            $this->validate($request,[
                'id' => 'required',
            ]);
            $this->model->key_field = 'id';
        } else {
            $this->validate($request,[
                'id' => 'required',
            ]);
            $this->model->key_field = 'id';
        }          
        if ($table_name) {
            $result = $this->model::modify($table_name,$input,$this->model->key_field." = '".$request->input($this->model->key_field)."'");
            if (!$result) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Tidak Ada yang Diupdate!',
                    "result" => null
                ], 404);
            } else {
                return response()->json([
                    'code' => 201,
                    'message' => 'Update Data Berhasil',
                    "result" => $input
                ], 201);
            }    
        } else {
            return response()->json([
                'code' => 501,
                'message' => 'Tabel Tidak ditemukan.!',
                "result" => [
                    "Table" => $table_name,
                ]
            ], 502);
        }                    
    }
    public function removeData(Request $request,$table_name = null,$table_varians = null)
    { 
        $input  = $this->model::initColumnForInput($request,'kodec',$table_name);

        /*set validation*/
        if ($table_varians == 'master' ) {
            
            $this->validate($request,[
                'kode' => 'required',
            ]);
            $this->model->key_field = 'kode';
        }
         elseif ($table_varians = 'referensi') {
            $this->validate($request,[
                'id' => 'required',
            ]);
            $this->model->key_field = 'id';
        } else {
            $this->validate($request,[
                'id' => 'required',
            ]);
            $this->model->key_field = 'id';
        }             
        $primary_key = $this->model->key_field." = '".$request->input($this->model->key_field)."'";
        if ($table_name) {
            $result = $this->model::remove($table_name,$primary_key);
            if (!$result) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Tidak Ada data yang dihapus',
                    "result" => $input 
                ], 404);
            } else {
                return response()->json([
                    'code' => 201,
                    'message' => 'Data Berhasil Dihapus!',
                    "result" => $input
                ], 201);
            }    
        } else {
            return response()->json([
                'code' => 501,
                'message' => 'Tabel Tidak ditemukan.!',
                "result" => [
                    "Table" => $table_name,
                ]
            ], 502);
        }                    
    }


     /* List Data versi #1 */
    /*public function listData($table_name,$request)
    {    
        $table_name = $this->fromCamelCase($table_name);  
        if ($table_name) {
        $data = $this->model::getData($table_name,$request); 
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
        } else {
            return response()->json([
                'code' => 501,
                'message' => 'Tabel Tidak ditemukan.!',
                "result" => [
                    "Table" => $table_name,
                ]
            ], 502);
        }        
    }
    */
    // public function list($tabel,$request)
    // {
    //     $select = $request['select'];
    //     $where = $request['where'];
    //     $group = $request['group'];
    //     $order = $request['order'];
    //     $limit = $request['limit'];
    //     $offset = $request['offset'];

    //      if ($tabel) {
    //         $query  = DB::table($tabel);    
    //         $query->selectRaw($select);
    //         $query->whereRaw($where);
    //         //$query->groupByRaw($group);
    //         $query->orderByRaw($order);
    //         $query->take($limit);
    //         $query->offset($offset);
    //         $data = array();
    //         $data = $query->get();   
    //         // if ($query ) {
    //         //     $query  = DB::select("SELECT COUNT(nama_kolom) FROM nama_table");  
    //         // }  
    //         if (!$data) {
    //             return response()->json([
    //                 'code' => 404,
    //                 'message' => 'Data Kosong',
    //                 "result" => null
    //             ], 404);
    //         } else {
    //             return response()->json([
    //                 'code' => 200,
    //                 'message' => 'OK',
    //                 "result" => $data
    //             ], 200);
    //         }
    //     }  else {
    //         return response()->json([
    //             'code' => 501,
    //             'message' => 'Tabel Tidak ditemukan.!',
    //             "result" => [
    //                 "Table" => $tabel,
    //             ]
    //         ], 502);
    //     }        
    // }
    

}
