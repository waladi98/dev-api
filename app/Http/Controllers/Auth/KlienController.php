<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use App\Http\Controllers\CoreController;
use Carbon\Carbon;
use App\Models\Klien;

class KlienController extends CoreController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $waktu;
    public function __construct(Request $request)
    {
        $this->model = new Klien;
        $this->waktu = Carbon::now();
        parent::__construct($request);
    }

    public function register(Request $request)
    {
        $this->validate($request,[
            'kode' => 'required|unique:ws_sys_klien',
            'email' => 'required |email|unique:ws_sys_klien',
            'password' => 'required | ',
            'pin' => 'required | numeric',

        ]);
    
        $kode = $request->input('kode');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $pin = $request->input('pin');
        
        $register = Klien::create([
            'kode' => $kode,
            'email' => $email,
            'password' => $password,
            'pin' => $pin
        ]);

        if ($register) {
            return response()->json([
                'code' => 201,
                'success' => true,
                'message' => 'Klien Baru Berhasil Ditambahkan',
                'data' => $register
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Klien Tidak Berhasil Ditambahkan',
                'data' => ''
            ], 401);
        }       
    }
    public function login(Request $request){
        

        $this->validate($request,[
            'email' => 'required',
            'password' => 'required',
        ]);
        $waktu = Carbon::now();
        $email = $request->input('email');
        $password = $request->input('password'); 
        
        $klien = Klien::where('email', $email)->first();//objek
        
        if ($klien) {
            if (Hash::check($password, $klien->password)) {
                $apiToken = base64_encode(Str::random(60));
                
                $klien->update([
                    'token' => $apiToken,
                    'akses_terakhir' =>  $waktu 
               ]);
            
               return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Login Klien Berhasil',
                        'data' => [
                             'client' => $klien,
                            ]
                    ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'sandi Klien Salah',
                        'data' => [
                            'client' => null,
                            'api-token' => ''
                            ]
                    ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Akun Klien Tidak Tersedia!',
                    'data' => [
                        'client' => null,
                        'api-token' => ''
                        ]
                ], 404);
        }
    }

}
