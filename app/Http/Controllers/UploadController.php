<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \stdClass;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $file_data = $_FILES["fileKey"];

            if ( $file_data["type"] != "video/mp4" && $file_data["type"] != "image/jpeg" ) {
        
                return ['status' => 'file type not allowed'];
            }

            $target_dir = getcwd().DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR;

            $target_file = $target_dir . basename( $file_data["name"] );

            $type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            $name = basename($target_file, ".".$type) . "_" . time(); 
            $filename = $name . "." . $type;
      
            $target_file = $target_dir . $filename;

            if( move_uploaded_file($file_data["tmp_name"], $target_file) )
            {
                $id = time();

                $obj = [];
                $obj["id"] = $id;
                $obj["filename"] = $filename;
                $obj["type"] = $type;
                $obj["date"] = date("Y-m-d H:i", $id);
            
                $obj = json_encode( $obj );
                
                $filePath = getcwd().DIRECTORY_SEPARATOR."temp_db".DIRECTORY_SEPARATOR."temp_db.txt";
                
                $fp = fopen($filePath, "a"); 
                fwrite($fp, filesize($filePath) > 0 ? ",".$obj : $obj ); 
                fclose($fp);

                return ['status' => 'good'];
            }
            else
            {
                return ['status' => 'upload error'];
            }



        } catch (Exception $e) {
            
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        try {

            $data = [];

            $filePath = getcwd().DIRECTORY_SEPARATOR."temp_db".DIRECTORY_SEPARATOR."temp_db.txt";
            $myfile = fopen($filePath, "r") or die("Unable to open file!");
            if (filesize($filePath) > 0) 
            {
                $data = fread($myfile, filesize($filePath));
                $data = "[".$data."]";
                
                fclose($myfile); 
            }

            http_response_code(200);
            return $data;

        } catch (Exception $e) {
            http_response_code(400);
            return $e;
        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
 
}
