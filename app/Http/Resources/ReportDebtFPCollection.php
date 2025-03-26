<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Constants\FPConst;
class ReportDebtFPCollection extends ResourceCollection
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
                    'code' => $page->code,
                    'name' => $page->name,
                    'shipping_charges' => $page->shipping_charges,
                    'guest_costs' => $page->guest_costs,
                    'deployment_costs' => $page->deployment_costs,
                    'interest' => $page->interest,
                    'commission' => $page->commission,
                    'tax' => $page->tax,
                    'bids_cost' => $page->bids_cost,
                    'statusNumber' => $page->status,
                    'selling' => $page->selling,
                    'margin' => $page->margin,
                    'total_debt'=>$page->total_debt,
                    'created_at' => $page->created_at,
                    'isDone' => $page->isDone == 2 ? "Chưa thu xong": "Đã thu xong",
                    'isDone_number' => $page->isDone,
                    'fp' => new FPResource($page->fp()->first()),
                    'total_margin' => $page->fp_sum_margin,
                    'total_selling' => $page->fp_sum_selling,
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
