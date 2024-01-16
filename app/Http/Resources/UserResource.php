<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'salary' =>$this->salary,
            'username' => $this->username,
            'phone' => $this->phone,
            'role_id' => $this->roles->first()->id,
            'salary_lv_id' => $this->salary->id ?? null,
            'salary' => $this->salary->salary ?? 0,
        ];
    }

    public function with($request)
    {
        return [
            'status' => true,
        ];
    }
}


