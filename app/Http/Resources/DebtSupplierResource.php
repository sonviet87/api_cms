<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DebtSupplierResource extends JsonResource
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
            'name' => $this->name,
            'date_over' => $this->date_over,
            'pay_first' => $this->pay_first,
            'pay_second' => $this->pay_second,
            'deposit_percent' => $this->deposit_percent,
            'debt_percent' => $this->debt_percent,
            'number_date_over' => $this->number_date_over,
            'total_debt' => $this->total_debt,
            'isDone' => $this->isDone,
            'fp_id' => new FPResource($this->fp()->first()),
            'supplier_id' => $this->supplier_id,
            'date_invoice' => $this->date_invoice ?$this->date_invoice: '',

        ];
    }

    public function with($request)
    {
        return [
            'status' => true,
        ];
    }
}


