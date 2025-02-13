<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
class KpiSetUpUserCollection extends ResourceCollection
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
                    'year' => $page->year,
                    'user_id' => $page->user_id,
                    'sales_months' => $page->sales_months,
                    'sales_3_months' => $page->sales_3_months,
                    'sales_12_months' => $page->sales_12_months,

                    'current_sale_months' => $page->current_sale_months,
                    'current_sale_3_months' => $page->current_sale_3_months,
                    'current_sale_12_months' => $page->current_sale_12_months,
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
