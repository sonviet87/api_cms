<?php

namespace App\Services;

use App\Constants\PermissionConst;
use App\Constants\RolePermissionConst;
use App\Interfaces\AccountInterface;
use Illuminate\Support\Facades\Auth;

class AccountService extends BaseService
{
    protected $account;

    function __construct(AccountInterface $account)
    {
        $this->account = $account;
    }

    public function getAll($IDs)
    {
        return $this->account->getAll($IDs);
    }
    public function getList()
    {
        $filter=[];
        $user = Auth::user();
        if ($user->hasPermissionTo(PermissionConst::IS_SALE)) {
            $filter['user_id'] = $user->id;
            $staffs = $user->subordinates()->get();
            if ($staffs) {
                $filter['staffs'] = $staffs->pluck('id')->toArray();;
            }
        }
        return $this->account->getList($filter);
    }

    public function getListPaginate($perPage = 20,$filter)
    {
        $user = Auth::user();
        if ($user->hasPermissionTo(PermissionConst::IS_SALE)) {
            $filter['user_id'] = $user->id;
            $staffs = $user->subordinates()->get();
            if ($staffs) {
                $filter['staffs'] = $staffs->pluck('id')->toArray();;
            }
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

    public function createNewAccount($data)
    {
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
