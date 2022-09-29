<?php
namespace App\Services;

use App\Constants\RolePermissionConst;
use App\Constants\UserConst;
use App\Helpers\CustomFunctions;
use App\Interfaces\UserInterface;
use App\Models\Permission;

class RoleService extends BaseService {
    protected $user;
    function __construct(
        UserInterface $user
    ){
        $this->user = $user;
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
