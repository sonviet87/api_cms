<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
class KpiMemberGroupsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'profit_months' => $this->profit_months,
            'profit_3_months' => $this->profit_3_months,
            'profit_12_months' => $this->profit_12_months,
            'profit_months_percent' => $this->profit_months_percent,
            'profit_3_months_percent' => $this->profit_3_months_percent,
            'profit_12_months_percent' => $this->profit_12_months_percent,
            'customer_months' => $this->customer_months,
            'customer_3_months' => $this->customer_3_months,
            'customer_12_months' => $this->customer_12_months,
            'debts_months' => $this->debts_months,
            'debts_3_months' => $this->debts_3_months,
            'debts_12_months' => $this->debts_12_months,
            'users' => new UserCollection($this->users()->get()),
            'customer_months_conditions' => $this->customers()->where('type','1months')->get(),
            'customer_3months_conditions' =>$this->customers()->where('type','3months')->get(),
            'customer_12months_conditions' =>$this->customers()->where('type','12months')->get(),
            'debts_months_conditions' =>$this->debts()->where('type','1months')->get(),
            'debts_3months_conditions' =>$this->debts()->where('type','3months')->get(),
            'debts_12months_conditions' =>$this->debts()->where('type','12months')->get(),
        ];

    }

}
