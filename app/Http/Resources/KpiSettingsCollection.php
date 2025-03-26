<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class KpiSettingsCollection extends ResourceCollection
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
                    'min_percentage' => $page->min_percentage,
                    'max_percentage' => $page->max_percentage,
                    'percentage' => $page->percentage,
                    'type' => $page->type,

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
