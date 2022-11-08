<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactCollection extends ResourceCollection
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
                    'phone' => $page->phone,
                    'email' => $page->email,
                    'created_at' => $page->created_at,
                    'user' => $page->users->name,
                    'user_id' => $page->user_id,
                    'account_id' => $page->account_id,
                    'account' => $page->accounts->name
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
