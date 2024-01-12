<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
class KpiSettingsResource extends JsonResource
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
            'min_percentage' => $this->min_percentage,
            'max_percentage' => $this->max_percentage,
            'percentage' => $this->percentage,
            'type' => $this->type,

        ];

    }
    public function with($request)
    {
        return [
            'status' => true,
        ];
    }

}
