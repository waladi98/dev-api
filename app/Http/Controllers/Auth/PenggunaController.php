<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\KlienPengguna;


class PenggunaController extends CoreController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $waktu;
    private $_namaTabel;
    private $_kolom;
    public function __construct(Request $request)
    {
        $this->_namaTabel = new User;
        $this->_kolom = 'kode';
        $this->waktu = Carbon::now();
        parent::__construct($request);
    }

    public function register(Request $request)
    {
        $this->validate($request,[
            'kode' => 'required|unique:kodec_sys_pengguna',
            'nama' => 'required',
            'email' => 'required | email |unique:kodec_sys_pengguna',
            'kata_sandi' => 'required | ',
            'pin' => 'required | numeric',

        ]);
    
        $kode = $request->input('kode');
        $email = $request->input('email');
        $nama = $request->input('nama');
        $kata_sandi = Hash::make($request->input('kata_sandi'));
        $pin = $request->input('pin');
        
        $register = User::create([
            'kode' => $kode,
            'nama' => $nama,
            'email' => $email,
            'kata_sandi' => $kata_sandi,
            'pin' => $pin
        ]);

        if ($register) {
            return response()->json([
                'code' => 201,
                'success' => true,
                'message' => 'Pengguna Baru Berhasil Ditambahkan',
                'data' => $register
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna Tidak Berhasil Ditambahkan',
                'data' => ''
            ], 401);
        }       
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'kode' => 'required',
            'kata_sandi' => 'required'
        ]);
  
        $kode = $request->input('kode');
        $kata_sandi = $request->input('kata_sandi');
  
        $user = User::find($kode);
        
        $isValidPassword = Hash::check($kata_sandi, $user->kata_sandi);
        if (!$user) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid Parameter',
                "result" => [
                "login" => false,
                "kode" => "kode pengguna tidak valid",
            ]
            ], 401);
        } elseif (!$isValidPassword) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid Parameter',
                "result" => [
                    "login" => false,
                    "kata_sandi" => "Kata sandi Tidak Valid",
                ]
              ],401);
        } else {
            $generateToken = bin2hex(random_bytes(40));
            $user->update([
                'akses_terakhir' => $this->waktu
            ]);
            $klien = "ws_desktop";
            $data = [
                'kode_pengguna' => $user->kode,
                'kode_klien' =>   $klien,
                'akses_terakhir' =>   $this->waktu,
                'token' => $generateToken
            ];            
            
            return $this->createKlienPengguna($data);   
        }
    }


    public function createKlienPengguna($data){
        $UserLogin = KlienPengguna::where('kode_pengguna', $data['kode_pengguna'])->first();     
        if ($UserLogin) 
        {
            
            $UserLogin->update([
                'akses_terakhir' =>   $data['akses_terakhir'],
                'token' => $data['token']
            ]);
            return response()->json([
                'code' => 200,
                'message' => 'OK',
                "result" => [
                    "login" => true,
                    "kelompok" => null,
                    "user_token" => $data['token'],
                ]
            ], 200);
        } else 
        {
            $UserLogin = KlienPengguna::create($data);
            return response()->json([
                'code' => 200,
                'message' => 'OK',
                "result" => [
                    "login" => true,
                    "kelompok" => null,
                    "user_token" => $data['token'],
                ]
            ], 200);
        }
    }

    public function getData(Request $request)
    {
        
        $data = [
            'select' =>$request->input('select', "*"),
            'where' =>$request->input('where', 'kode is not null'),
            'group' =>$request->input('group', null),
            'order' =>$request->input('order', 'kode'),
            'limit' => $request->input('limit', 10),
            'offset' =>$request->input('offset', 0)
        ];            
        $table = $this->_namaTabel->getTable();
        
        return parent::list($table,$data);
    }    
}
