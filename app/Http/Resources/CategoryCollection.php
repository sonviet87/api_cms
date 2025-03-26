<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryCollection extends ResourceCollection
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
                    'descriptions' => $page->descriptions,
                    'tax_percent' => $page->tax_percent,

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
