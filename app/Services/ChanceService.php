<?php

namespace App\Services;

use App\Constants\ChanceConst;
use App\Constants\RolePermissionConst;
use App\Interfaces\ChanceInterface;
use Illuminate\Support\Facades\Auth;

class ChanceService extends BaseService
{
    protected $account;

    function __construct(ChanceInterface $account)
    {
        $this->account = $account;
    }

    public function getList()
    {
        $filter=[];
        $role = Auth::user()->roles->pluck('name')->first();
        if(!$role) return $this->_result(false, "Không tìm thấy user");
        if($role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_SALE]){
            $filter['user_id'] = Auth::user()->id;
        }
        return $this->account->getList($filter);
    }

    public function getListPaginate($perPage = 20,$filter)
    {
        $role = Auth::user()->roles->pluck('name')->first();
        if(!$role) return $this->_result(false, "Không tìm thấy user");
        if($role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_SALE]){
            $filter['user_id'] = Auth::user()->id;
        }

        return $this->account->getListPaginate($perPage,$filter);
    }

    public function getListContactByID($id)
    {
        $contacts =  $this->account->getListContactByID($id);
        if (!$contacts) {
            return $this->_result(false, 'Không lấy được id');
        }
        return  $contacts;
    }

    public function createNew($data)
    {
        $user = Auth::user();
        $data['user_id']= $user->id;
        $data['progress']= ChanceConst::STEP_1;
        $account = $this->account->create($data);
        if (!$account) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getAccountByID($id)
    {
        $account = $this->account->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $account);
    }

    public function update($id, $data)
    {
        $account = $this->account->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }


        $result = $this->account->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyAccountByIDs($ids)
    {
        $check = $this->account->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
