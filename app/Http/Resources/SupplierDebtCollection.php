<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SupplierDebtCollection extends ResourceCollection
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
                    'id' => $page->supplier_id,
                    'name' => $page->supplier()->first()->company

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
