<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PermissionCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return $this->collection->transform(function ($page) {
            return [
               'id' => $page->id,
                'name' => $page->name,
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
