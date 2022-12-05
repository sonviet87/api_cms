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
            'name' =>$this->name,
            'shipping_charges' => $this->shipping_charges,
            'shipping_charges_percent' => $this->shipping_charges_percent,
            'guest_costs' => $this->guest_costs,
            'guest_costs_percent' => $this->guest_costs_percent,
            'deployment_costs' => $this->deployment_costs,
            'deployment_costs_percent' => $this->deployment_costs_percent,
            'interest' => $this->interest,
            'interest_percent' => $this->interest_percent,
            'commission' => $this->commission,
            'commission_percent' => $this->commission_percent,
            'tax' => $this->tax,
            'bids_cost' => $this->bids_cost,
            'bids_cost_percent' => $this->bids_cost_percent,
            'status' => $this->status,
            'selling' => $this->selling,
            'margin' => $this->margin,
            'user' => $this->user->name,
            'user_id' => $this->user_id,
            'account_id' => $this->account_id,
            'phone' => $this->user->phone,
            'account' => $this->account->name,
            'contact' => $this->contact->name,
            'contact_id' => $this->contact_id,
            'created_at' => $this->created_at,
            'details' => new FPDetailsCollection($this->fp_details()->get())
        ];
    }

    public function with($request)
    {
        return [
            'status' => true,
        ];
    }
}


