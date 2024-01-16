<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    //public static $wrap = 'users';
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function __construct($resource)
    {
        /* $this->pagination = [
             'total' => $resource->total(),
             'count' => $resource->count(),
             'per_page' => $resource->perPage(),
             'current_page' => $resource->currentPage(),
             'total_pages' => $resource->lastPage()
         ];

         $resource = $resource->getCollection();*/

        parent::__construct($resource);
    }


    public function toArray($request)
    {
        return $this->collection->transform(function ($page) {
                return [
                    'id' => $page->id,
                    'name' => $page->name,
                    'email' => $page->email,
                    'status' =>$page->status,
                    'salary' =>$page->salary->salary?? 0,
                    'phone' =>$page->phone,
                    'roles' => $page->roles->transform(function ($item) {
                        return [
                            'name' => $item->name,

                        ];
                    }),
                    'permissions' => $page->permissions->transform(function ($item) {
                        return [
                            $item->name
                        ];
                    })

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
