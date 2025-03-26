<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\FileService;

class UploadController extends RestfulController
{

    protected $fileService;

    public function __construct(FileService $fileService){
        parent::__construct();
        $this->fileService = $fileService;

    }

    public function uploadStorage(Request $request){
        $this->validate($request, [
            'file_url' => 'required|file',
        ]);
        try{
            if(!$request->hasFile('file_url')){
                return $this->_error(trans('messages.images_not_found'));
            }
            $images = $request->file('file_url');
            $folder = $request->input("name", '');
            $file = $this->fileService->uploadStorage($images,$folder);
            $this->_response('tesst');
            return $this->_response($file);


        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }

    }

}
