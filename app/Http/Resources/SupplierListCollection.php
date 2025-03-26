<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SupplierListCollection extends ResourceCollection
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
                    'name' => $page->company,
                    'address' => $page->address,
                    'mst' => $page->mst,
                    'account' => $page->account,
                    'phone' => $page->phone,
                    'email' => $page->email,
                    'user' => $page->user->name,
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
