<?php

namespace App\Services;


use App\Helpers\CustomFunctions;
use App\Interfaces\FileInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;


class FileService  extends BaseService{
    protected $dirUpload = "files";
    public function uploadStorage($file,$folder){
        if($folder!="") $folder = "/".$folder;
        $this->dirUpload = $this->dirUpload ."/". Carbon::now()->format('Y')."/".Carbon::now()->format('m');
        $name =$file->getClientOriginalName();
        $name = strtolower(CustomFunctions::vnToStr(preg_replace('/\s+/', '-',$name)));
        $ext = $file->extension();
        if (Storage::disk('public')->exists($this->dirUpload.$folder."/".$name)) {
            $file_url = $file->storeAs($this->dirUpload.$folder, strtotime("now")."_".$name, 'public');
        }else{
            $file_url =  $file->storeAs($this->dirUpload.$folder, $name,'public');
        }

        return [
            'name' => $folder!=""? $folder."/".$name : $name,
            'file_url' => 'storage/'.$file_url,
            'file_url_hostname' => config('filesystems.disks.public.url')."/".$file_url,
            'extension' => $ext,
        ];
    }

}
