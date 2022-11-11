<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FPResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return  [
            'id' => $this->id,
            'shipping_charges' => $this->shipping_charges,
            'guest_costs' => $this->guest_costs,
            'deployment_costs' => $this->deployment_costs,
            'interest' => $this->interest,
            'commission' => $this->commission,
            'tax' => $this->tax,
            'bids_cost' => $this->bids_cost,
            'status' => $this->status,
            'selling' => $this->selling,
            'margin' => $this->margin,
            'user' => $this->user->name,
            'account' => $this->account->name,
            'contact' => $this->contact->name,
            'created_at' => $this->created_at,
            'fp_details' => new FPDetailsCollection($this->fp_details()->get())
        ];
    }

    public function with($request)
    {
        return [
            'status' => true,
        ];
    }
}


