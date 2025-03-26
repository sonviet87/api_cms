<?php

namespace App\Services;

use App\Constants\RolePermissionConst;
use App\Interfaces\ContactInterface;
use Illuminate\Support\Facades\Auth;

class ContactService extends BaseService
{
    protected $contact;

    function __construct(ContactInterface $contact)
    {
        $this->contact = $contact;
    }

    public function getList()
    {
        $role = Auth::user()->roles->pluck('name')->first();
        if(!$role) return $this->_result(false, "Không tìm thấy user");
        if($role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_SALE]){
            $filter['user_id'] = Auth::user()->id;
        }
        return $this->contact->getList();
    }

    public function getListPaginate($perPage = 20)
    {
        $role = Auth::user()->roles->pluck('name')->first();
        if(!$role) return $this->_result(false, "Không tìm thấy user");
        if($role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_SALE]){
            $filter['user_id'] = Auth::user()->id;
        }
        return $this->contact->getListPaginate($perPage);
    }



    public function createNew($data)
    {
        $account = $this->contact->create($data);
        if (!$account) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $account = $this->contact->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $account);
    }

    public function update($id, $data)
    {
        $account = $this->contact->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }


        $result = $this->contact->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->contact->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
