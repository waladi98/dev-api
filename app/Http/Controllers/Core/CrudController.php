<?php

namespace App\Http\Controllers\Core;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\KlienPengguna;


class CrudController extends CoreController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $waktu;
    private $_namaTabel;
    private $_namaView;
    private $DB_prefix;
    private $DB_name;
    private $_kolom;
    public function __construct(Request $request)
    {
        //$this->_namaTabel = 'kodec_mst_guru';
        //$this->_namaTabel = new User;
        //$this->_namaView = 'sv_guru';
        $this->DB_name = 'kodec';
        $this->DB_prefix = 's';
        $this->waktu = Carbon::now();
        parent::__construct($request);
    }
   
    public function getData(Request $request,$modul_type = null,$table_name= null,$action= null)
    {
        $endpoint = $modul_type."/".$table_name;
        if ($modul_type == 'master') {
            $this->_namaView = $this->DB_prefix.'v_'.$this->fromCamelCase($table_name);
            $data = [
                'select' =>$request->input('select', "*"),
                'where' =>$request->input('where', 'kode is not null'),
                'group' =>$request->input('group', null),
                'order' =>$request->input('order', 'kode'),
                'limit' => $request->input('limit', 10),
                'offset' =>$request->input('offset', 0)
            ];
        } elseif ($modul_type == 'referensi') {
            $this->_namaView = $this->DB_name.'_ref_'.$this->fromCamelCase($table_name);
            $data = [
                'select' =>$request->input('select', "*"),
                'where' =>$request->input('where', 'id is not null'),
                'group' =>$request->input('group', null),
                'order' =>$request->input('order', 'id'),
                'limit' => $request->input('limit', 10),
                'offset' =>$request->input('offset', 0)
            ];
        } else {
            $this->_namaView = $this->DB_prefix.'v_'.$this->fromCamelCase($table_name);
            $data = [
                'select' =>$request->input('select', "*"),
                'where' =>$request->input('where', 'id is not null'),
                'group' =>$request->input('group', null),
                'order' =>$request->input('order', 'id'),
                'limit' => $request->input('limit', 10),
                'offset' =>$request->input('offset', 0)
            ]; 
        }       
        $table_name = $this->_namaView;
        // $where = "kode = '991' ";
        // $group = '';
        // $jumlah_data = $this->jumlahData($table_name,$data,$group);
        return parent::listData($data,$table_name);
    }

    public function createData(Request $request,$modul_type = null,$table_name= null,$action= null)
    {
        $endpoint = $modul_type."/".$table_name."/".$action;

        if ($modul_type == 'master') {
            $this->_namaTabel = $this->DB_name.'_mst_'.$this->fromCamelCase($table_name);
            $data = [
                'select' =>$request->input('select', "*"),
                'where' =>$request->input('where', 'kode is not null'),
                'group' =>$request->input('group', null),
                'order' =>$request->input('order', 'kode'),
                'limit' => $request->input('limit', 10),
                'offset' =>$request->input('offset', 0)
            ];
        } elseif ($modul_type == 'referensi') {
            $this->_namaTabel = $this->DB_name.'_ref_'.$this->fromCamelCase($table_name);
            $data = [
                'select' =>$request->input('select', "*"),
                'where' =>$request->input('where', 'id is not null'),
                'group' =>$request->input('group', null),
                'order' =>$request->input('order', 'id'),
                'limit' => $request->input('limit', 10),
                'offset' =>$request->input('offset', 0)
            ];
        } else {
            $this->_namaTabel = $this->DB_name.'_trn_'.$this->fromCamelCase($table_name);
        }        

        $table_name = $this->_namaTabel;
        
        return parent::createData($request,$table_name);
    }
    
    public function modifyData(Request $request,$modul_type = null,$table_name= null,$action= null)
    {
        $endpoint = $modul_type."/".$table_name."/".$action;

        if ($modul_type == 'master') {
            $this->_namaTabel = $this->DB_name.'_mst_'.$this->fromCamelCase($table_name);
        } elseif ($modul_type == 'referensi') {
            $this->_namaTabel = $this->DB_name.'_ref_'.$this->fromCamelCase($table_name);
        } else {
            $this->_namaTabel = $this->DB_name.'_trn_'.$this->fromCamelCase($table_name);
        }       
        
        $table_varians = $modul_type;
        $table_name = $this->_namaTabel;
        
        return parent::modifyData($request,$table_name,$table_varians );
    }
    

    public function deleteData(Request $request,$modul_type = null,$table_name= null,$action= null)
    {
        $endpoint = $modul_type."/".$table_name."/".$action;

        if ($modul_type == 'master') {
            $this->_namaTabel = $this->DB_name.'_mst_'.$this->fromCamelCase($table_name);
        } elseif ($modul_type == 'referensi') {
            $this->_namaTabel = $this->DB_name.'_ref_'.$this->fromCamelCase($table_name);
        } else {
            $this->_namaTabel = $this->DB_name.'_trn_'.$this->fromCamelCase($table_name);
        }       
        
        $table_varians = $modul_type;
        $table_name = $this->_namaTabel;
       
        return parent::removeData($request,$table_name,$table_varians );
    }

    /* Get Data versi #1 */
    // public function getDataTransaksi(Request $request,$table_name){
    //     $this->_namaView = 's'.'v_'.$this->fromCamelCase($table_name);
    //         $data = [
    //             'select' =>$request->input('select', "*"),
    //             'where' =>$request->input('where', 'id is not null'),
    //             'group' =>$request->input('group', null),
    //             'order' =>$request->input('order', 'id'),
    //             'limit' => $request->input('limit', 10),
    //             'offset' =>$request->input('offset', 0)
    //         ];      
    //     $table_name = $this->_namaView;
       
    //     return parent::listData($data,$table_name);
    // }
    // public function create(Request $request)
    // {
    //     // $data = [
    //     //     'wal' => 'ok'
    //     // ];
    //     $table_name = $this->_namaTabel;
    //     return parent::createData($request,$table_name);
    // }

    // public function modifyData(Request $request)
    // {
    //     // $data = [
    //     //     'wal' => 'ok'
    //     // ];
    //     $table_varians = 'master';
    //     $table_name = $this->_namaTabel;
    //     return parent::modifyData($request,$table_name,$table_varians );
    // }
    // public function getData(Request $request)
    // {
        
    //     $data = [
    //         'select' =>$request->input('select', "*"),
    //         'where' =>$request->input('where', 'kode is not null'),
    //         'group' =>$request->input('group', null),
    //         'order' =>$request->input('order', 'kode'),
    //         'limit' => $request->input('limit', 10),
    //         'offset' =>$request->input('offset', 0)
    //     ];            
    //    //$table_name = $this->_namaTabel->getTable();
    //    $table_name = $this->_namaView;
        
    //     return parent::listData($table_name,$data);
    // }
























    // public function getData(Request $request)
    // {
        
    //     $data = [
    //         'select' =>$request->input('select', "*"),
    //         'where' =>$request->input('where', 'kode is not null'),
    //         'group' =>$request->input('group', null),
    //         'order' =>$request->input('order', 'kode'),
    //         'limit' => $request->input('limit', 10),
    //         'offset' =>$request->input('offset', 0)
    //     ];            
    //     $table = $this->_namaTabel->getTable();
        
    //     return parent::list($table,$data);
    // }    
}
