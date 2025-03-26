<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Constants\FPConst;
class KpiMemberGroupsCollection extends ResourceCollection
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
                    'profit_months' => $page->profit_months,
                    'profit_3_months' => $page->profit_3_months,
                    'profit_12_months' => $page->profit_12_months,
                    'customer_months' => $page->customer_months,
                    'customer_3_months' => $page->customer_3_months,
                    'customer_12_months' => $page->customer_12_months,
                    'debts_months' => $page->debts_months,
                    'debts_3_months' => $page->debts_3_months,
                    'debts_12_months' => $page->debts_12_months,
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
