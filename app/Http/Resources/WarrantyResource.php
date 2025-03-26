<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarrantyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return  [
            'id' => $this->id,
            'name' => $this->name,
            'fp_id' => $this->fp_id,
            'fps' => new FPResource($this->fp()->get()->first()),
            'start_day' => $this->start_day,
            'end_day' => $this->end_day,
            'phone' => $this->phone,
            'email' => $this->email,
            'file_warranty' => $this->file_warranty,
            'details' => $this->details,
        ];
    }

    public function with($request)
    {
        return [
            'status' => true,
        ];
    }
}


