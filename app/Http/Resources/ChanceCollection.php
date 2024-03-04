<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ChanceCollection extends ResourceCollection
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
                    'account_id' => $page->account_id,
                    'name' => $page->name,
                    'account' => $page->account->name,
                    'user_id' => $page->user_id,
                    'contact' => $page->contact->name,
                    'phone' => $page->contact->phone,
                    'contact_id' => $page->contact_id,
                    'user_assign' => $page->user_assign,
                    'prices' => $page->prices,
                    'progress' => $page->progress,
                    'start_day' => $page->start_day,
                    'end_day' => $page->end_day,
                    'completed' => $page->completed,
                    'user_assign_name' => $page->userAssign->name,
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
