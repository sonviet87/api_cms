<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Constants\FPConst;
class ReportDebtSupplierCollection extends ResourceCollection
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
                    'supplier'=> $page->supplier->company,
                    'total_debt'=>$page->total_debt,
                    'created_at' => $page->created_at,
                    'isDone' => $page->isDone == 2 ? "Chưa trả": "Đã trả",
                    'isDone_number' => $page->isDone,
                    'fp' => new ReportDebtSupplierByIDFPResource($page->fp()->first(),$page->supplier_id,),
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
