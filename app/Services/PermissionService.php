<?php
namespace App\Services;

use App\Interfaces\PermissionInterface;
use App\Models\Permission;

class PermissionService extends BaseService {

    protected $permission;
    function __construct( PermissionInterface $permission  ){

        $this->permission = $permission;
    }


    public function getList()
    {
        return $this->permission->getList();
    }


}
