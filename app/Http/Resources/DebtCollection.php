<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DebtCollection extends ResourceCollection
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
                    'date_over' => $page->date_over,
                    'pay_first' => $page->guest_costs,
                    'pay_second' => $page->deployment_costs,
                    'deposit_percent' => $page->interest,
                    'debt_percent' => $page->commission,
                    'total_debt' => $page->tax,
                    'fp_id' => new FPResource($page->fp()->first()),

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
