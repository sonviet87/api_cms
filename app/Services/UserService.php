<?php

namespace App\Services;

use App\Constants\RolePermissionConst;
use App\Constants\UserConst;
use App\Interfaces\UserInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    protected $user;

    function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getList()
    {
        return $this->user->getList();
    }

    public function getUserByEmail($email)
    {
        return $this->user->getUserByEmail($email);
    }

    public function getUserByPhone($phone)
    {
        return $this->user->getUserByPhone($phone);
    }

    public function checkPassword($password){

        return Hash::check($password, Auth::user()->password);
    }

    public function getUserByFacebookID($facebook_id)
    {
        return $this->user->getUserByFacebookID($facebook_id);
    }

    public function getUserByGoogleID($google_id)
    {
        return $this->user->getUserByGoogleID($google_id);
    }

    public function registerByEmail($data)
    {
        /* if(isset($data['gender'])){
            $data['gender'] = CustomFunctions::isEnabled($data['gender']);
            if($data['gender'] == null) unset($data['gender']);
        } */
        /* if(isset($data['birthday'])){
            if (CustomFunctions::isValidDate($data['birthday']) == false) {
                unset($data['birthday']);
            }
        } */

        $data['role_id'] = RolePermissionConst::ROLE_CLIENT;
        $data['status'] = UserConst::STATUS_ACTIVE;
        $user = $this->user->createNewUserByEmail($data);
        return $user;
    }

    public function createUserByFacebookUser($facebookUser)
    {
        $user = false;
        if (isset($facebookUser['email']) && !empty($facebookUser['email'])) {
            $userGetByFacebookEmail = $this->getUserByEmail($facebookUser['email']);
            if (!$userGetByFacebookEmail) {
                $userGetByFacebookID = $this->getUserByFacebookID($facebookUser['id']);
                if (!$userGetByFacebookID) {
                    $user = $this->user->createNewUser([
                        'name' => $facebookUser['name'] ?? '',
                        'status' => UserConst::STATUS_UNACTIVE
                    ]);
                } else {
                    $user = $userGetByFacebookID;
                }
            } else {
                $user = $userGetByFacebookEmail;
            }
            $user->forceFill([
                'email' => $facebookUser['email'],
                'facebook_id' => $facebookUser['id'],
            ])->save();

        } else if (isset($facebookUser['id']) && !empty($facebookUser['id'])) {
            $userGetByFacebookID = $this->getUserByFacebookID($facebookUser['id']);
            if (!$userGetByFacebookID) {
                $user = $this->user->createNewUser([
                    'name' => $facebookUser['name'] ?? '',
                    'facebook_id' => $facebookUser['id'],
                    'status' => UserConst::STATUS_UNACTIVE
                ]);
            } else {
                $user = $userGetByFacebookID;
            }
        }
        return $user;
    }

    public function createUserByGoogleUser($googleUser)
    {
        $user = false;
        if (isset($googleUser['email']) && !empty($googleUser['email'])) {
            $userGetByGoogleEmail = $this->getUserByEmail($googleUser['email']);
            if (!$userGetByGoogleEmail) {
                $userGetByGoogleID = $this->getUserByGoogleID($googleUser['id']);
                if (!$userGetByGoogleID) {
                    $user = $this->user->createNewUser([
                        'name' => $googleUser['name'] ?? '',
                        'status' => UserConst::STATUS_UNACTIVE
                    ]);
                } else {
                    $user = $userGetByGoogleID;
                }
            } else {
                $user = $userGetByGoogleEmail;
            }
            $user->forceFill([
                'email' => $googleUser['email'],
                'google_id' => $googleUser['id'],
            ])->save();

        } else if (isset($googleUser['id']) && !empty($googleUser['id'])) {
            $userGetByGoogleID = $this->getUserByGoogleID($googleUser['id']);
            if (!$userGetByGoogleID) {
                $user = $this->user->createNewUser([
                    'name' => $googleUser['name'] ?? '',
                    'google_id' => $googleUser['id'],
                    'status' => UserConst::STATUS_UNACTIVE
                ]);
            } else {
                $user = $userGetByGoogleID;
            }
        }
        return $user;
    }

    public function getListPaginate($perPage = 20, $filter = [])
    {
        return $this->user->getListPaginate($perPage,$filter);
    }

    public function createNewUser($data)
    {
        $data['status'] = UserConst::STATUS_ACTIVE;
        $data['password'] = Hash::make($data['password']);
        $user = $this->user->createNewUser($data);
        if (!$user) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getUserByID($id)
    {
        $user = $this->user->getUserByID($id);
        if (!$user) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $user);
    }

    public function updateUserByID($id, $data)
    {
        $user = $this->user->getUserByID($id);
        if (!$user) {
            return $this->_result(false, 'Not found!');
        }
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        if (isset($data['email']) && $user->email != $data['email']) {
            $user = $this->user->getUserByEmail($data['email']);
            if ($user) {
                return $this->_result(false, 'Your email has already taken!');
            }
        }
        $result = $this->user->updateUserByID($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function changePassword($oldPass,$password){
        $isDulicate = $this->checkPassword($oldPass);
        if(!$isDulicate) return $this->_result(false, 'Mật khẩu không đúng');
        $password= Hash::make($password);
        $result = $this->user->updatePassword(Auth::user()->id, $password);
        if (!$result) {
            return $this->_result(false, 'Cập nhật không thành công');
        }
        return $this->_result(true, 'Cập nhật thành công');
    }
    public function destroyUsersByIDs($ids)
    {
        $check = $this->user->destroyUsersByIDs($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
