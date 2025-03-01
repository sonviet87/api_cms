<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class KpiSettingSupplierResource extends JsonResource
{
    public function toArray($request)
    {

        $date = Carbon::create($this->year, 1, 1, 0, 0, 0, 'UTC');
        return [
            'name' => $this->name,
            'user_id' => $this->user_id,
            'year' => $date,
            'new_supplier_conditions' => $this->new_supplier_conditions,
            'old_supplier_conditions' => $this->old_supplier_conditions,
            'new_supplier_target' => $this->new_supplier_target,
            'old_supplier_target' => $this->old_supplier_target,
            'new_supplier_percent' => $this->new_supplier_percent,
            'old_supplier_percent' => $this->old_supplier_percent,

        ];
    }



    public function with($request)
    {
        return [
            'status' => true,
        ];
    }
}
