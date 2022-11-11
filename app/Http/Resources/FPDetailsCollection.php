<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FPDetailsCollection extends ResourceCollection
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
                    'category' => $page->category()->get(),
                    'supplier' => $page->supplier()->get(),
                    'qty' => $page->qty,
                    'price_buy' => $page->price_buy,
                    'price_sell' => $page->price_sell,
                    'profit' => $page->profit,
                    'price_guest' => $page->price_guest,
                    'price_bids' => $page->price_bids,

                ];
            });


    }


}
