<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AccountCollection extends ResourceCollection
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
                    'address' => $page->address,
                    'city' => $page->city,
                    'email' => $page->email,
                    'industry' => $page->industry,
                    'legal_address' => $page->legal_address,
                    'legal_name' => $page->legal_name,
                    'name' => $page->name,
                    'phone' => $page->phone,
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
