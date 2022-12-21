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
            'user_assign' => $this->user_assign,
            'user_assign_name' => $this->userAssign->name,
            'phone' => $this->userAssign->phone,
            'account' => $this->account->name,
            'contact' => $this->contact->name,
            'contact_id' => $this->contact_id,
            'file_customer_invoice' => $this->file_customer_invoice,
            'file_customer_invoice_url' => $this->file_customer_invoice_url,
            'file_company_receipt' => $this->file_company_receipt,
            'file_company_receipt_url' => $this->file_company_receipt_url,
            'file_bbbg' => $this->file_bbbg,
            'file_bbbg_url' => $this->file_bbbg_url,
            'file_ncc' => $this->file_ncc,
            'created_at' => $this->created_at,
            'details' => new FPDetailsCollection($this->fp_details()->get()),
            'date_invoice' => date('d-m-Y', strtotime($this->date_invoice)),
            'date_shipping' => date('d-m-Y', strtotime($this->date_shipping)),
            'number_invoice' => $this->number_invoice,
        ];
    }

    public function with($request)
    {
        return [
            'status' => true,
        ];
    }
}


