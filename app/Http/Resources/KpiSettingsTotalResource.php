<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
class KpiSettingsTotalResource extends JsonResource
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
            'min_percentage' => $this->min,
            'max_percentage' => $this->max,
            'percentage' => $this->percentage,
            'points' => $this->points,
            'bonus' => $this->bonus,
            'parent_id' => $this->parent_id,
            'type_kpi' => $this->type_kpi,
            'name' => $this->name,
            'type' => $this->type,

        ];

    }
    public function with($request)
    {
        return [
            'status' => true,
           // 'kpi_company' => $this->kpi_company,
        ];
    }

}
