<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DebtSupplierCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($page) {

            return [
                'id' => $page->id,
                'name' => $page->name,
                'date_over' => $page->date_over,
                'pay_first' => $page->guest_costs,
                'pay_second' => $page->deployment_costs,
                'deposit_percent' => $page->interest,
                'debt_percent' => $page->commission,
                'number_date_over' => $page->number_date_over,
                'total_debt' => $page->total_debt,
                'isDone_number' => $page->isDone,
                'isDone' => $page->isDone == 2 ? "Chưa thu" : "Đã thu xong",
                'fp_code' => $page->fp->code,
                'supplier' => $page->supplier->company

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


