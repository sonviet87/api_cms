<?php

namespace App\Http\Resources;


use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
class CostsTeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        $date = Carbon::create($this->year, 1, 1, 0, 0, 0, 'UTC');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'year' => $date,
            'data' => $this->data,
            'costs_fixed' => $this->costs_fixed,
            'percent' => $this->percent,

        ];

    }
    public function with($request)
    {
        return [
            'status' => true,
        ];
    }

}
