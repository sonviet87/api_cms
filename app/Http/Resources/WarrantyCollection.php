<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WarrantyCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */


    public function toArray($request)
    {
       
        return $this->collection->transform(function ($page) {
           
                return [
                    'id' => $page->id,
                    'name' => $page->name,
                    'fp_id' => $page->fp_id,
                    'fps' => $page->fp()->get()->first(),
                    'account' => $page->account,
                    'start_day' => $page->start_day,
                    'end_day' => $page->end_day,
                    'phone' => $page->phone,
                    'email' => $page->email,
                    'file_warranty' => $page->file_warranty,
                    'details' => $page->details,
                ];
            });


    }

    public function with($request)
    {
        return [
            'status' => true,
        ];
    }
}
