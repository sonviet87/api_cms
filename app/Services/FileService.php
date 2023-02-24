<?php

namespace App\Services;


use App\Helpers\CustomFunctions;
use App\Interfaces\FileInterface;
use Exception;
use Illuminate\Support\Facades\Storage;


class FileService  extends BaseService{
    protected $dirUpload = "files";
    public function uploadStorage($file){
        $name =$file->getClientOriginalName();
        $name = strtolower(CustomFunctions::vnToStr(preg_replace('/\s+/', '-',$name)));
        $ext = $file->extension();
        if (Storage::disk('public')->exists($this->dirUpload."/".$name)) {
            $file_url = $file->storeAs($this->dirUpload, strtotime("now")."_".$name, 'public');
        }else{
            $file_url =  $file->storeAs($this->dirUpload, $name,'public');
        }

        return [
            'name' => $name,
            'file_url' => 'storage/'.$file_url,
            'file_url_hostname' => config('filesystems.disks.public.url')."/".$file_url,
            'extension' => $ext,
        ];
    }

}
