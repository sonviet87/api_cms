<?php
namespace App\Repositories;
use App\Interfaces\PermissionInterface;
use Spatie\Permission\Models\Permission;


class PermissionRepository implements PermissionInterface {
    protected $model;

    function __construct(Permission $permission){
        $this->model = $permission;
    }
    public function getList(){
        return $this->model->all();
    }

}
