<?php

namespace App\Services;

use App\Constants\RolePermissionConst;

use App\Interfaces\PositionInterface;
use Illuminate\Support\Facades\Auth;

class PositionService extends BaseService
{
    protected $position;

    function __construct(PositionInterface $position)
    {
        $this->position = $position;
    }

    public function getAll($IDs)
    {
        return $this->position->getAll($IDs);
    }
    public function getList()
    {
        $filter=[];
        $role = Auth::user()->roles->pluck('name')->first();
        if(!$role) return $this->_result(false, "Không tìm thấy user");
        if($role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_SALE]){
            $filter['user_id'] = Auth::user()->id;
        }
        return $this->position->getList($filter);
    }

    public function getListPaginate($perPage = 20,$filter)
    {
        $role = Auth::user()->roles->pluck('name')->first();
        if(!$role) return $this->_result(false, "Không tìm thấy user");
        if($role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_SALE]){
            $filter['user_id'] = Auth::user()->id;
        }

        return $this->position->getListPaginate($perPage,$filter);
    }

    public function getListContactByID($id)
    {
        $contacts =  $this->position->getListContactByID($id);
        if (!$contacts) {
            return $this->_result(false, 'Không lấy được id');
        }
        return  $contacts;
    }

    public function createNew($data)
    {
        $account = $this->position->create($data);
        if (!$account) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $rs = $this->position->getByID($id);
        if (!$rs) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $rs);
    }

    public function update($id, $data)
    {
        $rs = $this->position->getByID($id);
        if (!$rs) {
            return $this->_result(false, 'Not found!');
        }


        $result = $this->position->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->position->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
