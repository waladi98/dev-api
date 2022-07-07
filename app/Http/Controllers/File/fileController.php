<?php

    namespace App\Http\Controllers\File;

    use Illuminate\Http\Request;

    use App\Http\Controllers\smartController;
    use Laravel\Lumen\Routing\Controller as BaseController;
    
	use Storage;
    use Illuminate\Support\Facades\File;
    class fileController extends BaseController
    {

        public function fileUpload(Request $request)
        {
            $this->validate($request,[
                'path' => 'required',
                'file' => 'required',
            ]);
            $obj        = $request->all();
            $files = $request->file('file');

            $filename     = $this->saveFile($files,$obj['path']);

            $this->out = 
            [
                "code"    => 200,
                "message" => "Upload Sukses",
                "result"  => $filename
            ];

            return $this->out;
        }

        private function saveFile($file,$path)
        {
            $filename         = $file->getClientOriginalName();
            $mimeType         = $file->getClientMimeType();

            // $mimeClientAlowed = array(
            //     'application/pdf',
            //     'image/jpeg',
            //     'image/png',
            // );

            // if (!in_array($mimeType, $mimeClientAlowed)) {
            //     throw new Exception('error file type');
            // }

            $targetPath  = 'storage/'.$path;
            $targetFile  = str_replace('//', '/', $targetPath);
            $newfilename = time() . '_' . $filename;
            $filenameNew = time() . '_' . $filename;

                // upload file
		    $file->move($targetPath,$newfilename);

            return array('filename' => $filename, 'newfilename' => $targetPath.'/'.$filenameNew,'mime'=>$mimeType);
        }

        //get target path file
        public function removeFile(Request $request)
        {
            $this->validate($request,[
                //'nama_file' => 'required',
                'target_path' => 'required',
            ]);
            $obj = [
                //'nama_file' => $request->input('nama_file'),
                'target_path' => $request->input('target_path'),
            ];
            return $filename  = $this->delete($obj);
        }

        //hapus file
        public function delete($data)
        {  
            //$result = "storage/".$data['target_path']."/".$data['nama_file'];
            //die($result);
            $result = File::delete($data);    
            if (!$result) {
                return response()->json([
                    'code' => 404,
                    'success' => true,
                    'message' => 'File TIdak Ditemukan',
                    "result" => $result
                ], 404);
            } else {
                return response()->json([
                    'code' => 201,
                    'success' => true,
                    'message' => 'File berhasil dihapus',
                    "result" => $data
                ], 201);
            }  
        }
     
    }
?> 