<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TechnicalCertificateCollection extends ResourceCollection
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
                    'start_date' => $page->start_date,
                    'end_date' => $page->end_date,
                    'name' => $page->name,
                    'user_id' => $page->user_id,
                    'points' => $page->points,
                    'user_name' =>$page->user->name,
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
