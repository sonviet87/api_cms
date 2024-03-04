<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChanceResource extends JsonResource
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
            'prices' => $this->prices,
            'name' =>$this->name,
            'user' => $this->user->name,
            'user_id' => $this->user_id,
            'account_id' => $this->account,
            'user_assign' => $this->user_assign,
            'user_assign_name' => $this->userAssign->name,
            'account' => $this->account->name,
            'contact' => $this->contact->name,
            'contact_id' => $this->contact_id,
            'start_day' => $this->start_day,
            'files' => $this->files,
            'progress' => $this->progress,
            'completed' => $this->completed,


        ];
    }

    public function with($request)
    {
        return [
            'status' => true,
        ];
    }
}


