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
                    'category' => $page->category()->get()->first(),
                    'category_id' => $page->category()->get()->first(),
                    'supplier' => $page->supplier()->get()->first(),
                    'supplier_id' => $page->supplier()->get()->first()->id,
                    'qty' => $page->qty,
                    'price_buy' => $page->price_buy,
                    'price_sell' => $page->price_sell,
                    'profit' => $page->profit,
                    'total_sell' => $page->total_sell,
                    'total_buy' => $page->total_buy,
                    'file' => $page->file,
                    'file_url' => $page->file_url,
                    'number_invoice' => $page->number_invoice? $page->number_invoice: '',
                    'date_invoice' => $page->date_invoice? $page->date_invoice:'',

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
