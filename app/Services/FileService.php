<?php

namespace App\Services;


use App\Interfaces\FileInterface;
use Exception;
use Illuminate\Support\Facades\Storage;

class FileService  extends BaseService{
    protected $dirUpload = "files";
    public function uploadStorage($file){
        $name =$file->getClientOriginalName();
        $ext = $file->extension();
        if (Storage::disk('fp')->exists($this->dirUpload."/".$name)) {
            $file_url = $file->storeAs($this->dirUpload, strtotime("now")."_".$name, 'fp');
        }else{
            $file_url =  $file->storeAs($this->dirUpload, $file->getClientOriginalName(),'fp');
        }

        return [
            'name' => $name,
            'file_url' => '/storage/'.$file_url,
            'file_url_hostname' => config('filesystems.disks.public.url')."/".$file_url,
            'extension' => $ext,
        ];
    }

}
