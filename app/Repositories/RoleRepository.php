<?php
namespace App\Repositories;
use App\Interfaces\AccountInterface;
use App\Interfaces\RoleInterface;
use Spatie\Permission\Models\Role;


class RoleRepository implements RoleInterface {
    protected $model;
    function __construct(Role $role){
        $this->model = $role;
    }

    public function getList(){
        return $this->model->all();
    }

    public function getListPaginate($perPage = 20){
        return $this->model->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create($roleName,$permissions){
        $role = $this->model->create(['name' => $roleName,'guard_name' => 'api']);
        $role->syncPermissions($permissions);
        return $role;
    }

    public function getByID($id){
        return $this->model->find($id);
    }

    public function update($id, $roleName, $permissions){
        $role = $this->model->find($id);
        $role->syncPermissions($permissions);
        $role->update(['name'=>$roleName]);
        return $role;
    }

    public function destroy($ids){
        return $this->model->destroy($ids);
    }

}
