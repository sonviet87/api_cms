<?php
namespace App\Services;

use App\Interfaces\RoleInterface;
use App\Interfaces\UserInterface;
use App\Models\Permission;

class RoleService extends BaseService {
    protected $user;
    protected $role;
    function __construct(
        UserInterface $user,
        RoleInterface $role
    ){
        $this->user = $user;
        $this->role = $role;
    }


    public function getListPaginate($perPage = 20)
    {
        return $this->role->getListPaginate($perPage);
    }

    public function create($roleName,$permissions)
    {

        $role = $this->role->create($roleName,$permissions);
        if (!$role) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $user = $this->role->getByID($id);
        if (!$user) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $user);
    }

    public function update($id, $roleName,$permissions)
    {
        $role = $this->role->getByID($id);
        if (!$role) {
            return $this->_result(false, 'Not found!');
        }

        $result = $this->role->update($id, $roleName,$permissions);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroy($ids)
    {
        $check = $this->role->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }
    /**
     * Get Scopes by user
     * @param  collection $user
     * @return string
     */
    public function getScopesByUser($user){
        $result = '';
        if(!$user){
            return $result;
        }
        $scopes = [];
        // get all permissions by role
        if ($user->role_id) {
            if($user->role_id == 1){
                $scopes['admin'] = 1; // mark admin scope if user has admin role
            }else{
                foreach (Permission::where([
                    'user_role_id' => $user->role_id,
                    'isAllowed' => true,
                    'type' => 'role',
                ])->cursor() as $per) {
                    $actions = $per->action;
                    if (!empty($actions)) {
                        $actions = json_decode($actions, 1);
                        if (!empty($actions)) {
                            foreach ($actions as $act) {
                                // use scopes to overwrite duplicated data
                                $scopes[$per->controller . ':' . $act] = 1;
                            }
                        }
                    }
                }
            }
        }
        if (!empty($scopes)) {
            foreach ($scopes as $key => $scope){
                if($scope){
                    $result .= $key . ' ';
                }
            }
        }
        return trim($result);
    }


}
