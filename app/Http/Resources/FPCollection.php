<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Constants\FPConst;
class FPCollection extends ResourceCollection
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
                    'status' => FPConst::STATUS_NAME[$page->status],
                    'statusNumber' => $page->status,
                    'selling' => $page->selling,
                    'margin' => $page->margin,
                    'user' => $page->user->name,
                    'user_assign' => $page->userAssign->name,
                    'account' => $page->account?->name,
                    'contact' => $page->contact?->name,
                    'created_at' => $page->created_at,

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
