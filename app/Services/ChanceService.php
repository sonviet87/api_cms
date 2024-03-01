<?php

namespace App\Services;

use App\Constants\ChanceConst;
use App\Constants\RolePermissionConst;
use App\Interfaces\ChanceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ChanceService extends BaseService
{
    protected $chance;

    function __construct(ChanceInterface $chance)
    {
        $this->chance = $chance;
    }

    public function getList()
    {
        $filter=[];
        $role = Auth::user()->roles->pluck('name')->first();
        if(!$role) return $this->_result(false, "Không tìm thấy user");
        if($role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_SALE]){
            $filter['user_id'] = Auth::user()->id;
        }
        return $this->chance->getList($filter);
    }

    public function getListPaginate($perPage = 20,$filter)
    {
        $role = Auth::user()->roles->pluck('name')->first();
        if(!$role) return $this->_result(false, "Không tìm thấy user");
        if($role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_SALE]){
            $filter['user_id'] = Auth::user()->id;
        }

        return $this->chance->getListPaginate($perPage,$filter);
    }

    public function getListContactByID($id)
    {
        $contacts =  $this->chance->getListContactByID($id);
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
        if(isset($data["start_day"])) $data['start_day'] =  Carbon::parse($data["start_day"])->toDateTimeString();
        $rs = $this->chance->create($data);
        if (!$rs) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getAccountByID($id)
    {
        $chance = $this->chance->getByID($id);
        if (!$chance) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $chance);
    }

    public function update($id, $data)
    {
        $rs = $this->chance->getByID($id);
        if (!$rs) {
            return $this->_result(false, 'Not found!');
        }

        if(isset($data["start_day"])) $data['start_day'] =  Carbon::parse($data["start_day"])->toDateTimeString();
        //dd($data["start_day"]);
        $result = $this->chance->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyAccountByIDs($ids)
    {
        $check = $this->chance->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

    public function updateStatus($id, $status)
    {

        $check = $this->chance->updateStatus($id,$status);

        if (!$check) {
            return $this->_result(false, 'Lỗi');
        }
        $rs= $this->chance->getByID($id);

        $data = ['id'=>$id , 'name' => $rs->name,'email_assgin' => $rs->userAssign->email,'progress'=>$rs->progress];
        return $this->_result(true, 'Cập nhật thành công',$data);
    }

}
