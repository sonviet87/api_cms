<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SalaryCollection extends ResourceCollection
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
                    'level' => $page->level,
                    'salary' => $page->salary,

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
