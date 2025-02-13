<?php

namespace App\Services;

use App\Constants\RolePermissionConst;
use App\Interfaces\AccountInterface;
use App\Interfaces\CostsFixedInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CostsFixedService extends BaseService
{
    protected $costs;

    function __construct(CostsFixedInterface $costs)
    {
        $this->costs = $costs;
    }

    public function getAll($IDs)
    {
        return $this->costs->getAll($IDs);
    }
    public function getList()
    {
        $filter=[];
        $role = Auth::user()->roles->pluck('name')->first();
        if(!$role) return $this->_result(false, "Không tìm thấy user");
        if($role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_SALE]){
            $filter['user_id'] = Auth::user()->id;
        }
        return $this->costs->getList($filter);
    }

    public function getListPaginate($perPage = 20,$filter)
    {
        $role = Auth::user()->roles->pluck('name')->first();
        if(!$role) return $this->_result(false, "Không tìm thấy user");
        if($role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_SALE]){
            $filter['user_id'] = Auth::user()->id;
        }

        return $this->costs->getListPaginate($perPage,$filter);
    }



    public function createNew($data)
    {
        $totalPoints = array_reduce($data['data'], function ($carry, $item) {
            $cleanTotal = (int)str_replace([',', '.'], '', $item['total']);
            return $carry + $cleanTotal;
        }, 0);

        $data['costs'] = $totalPoints;
       // $data["data"] =  json_encode($data['data']);
        $data['year'] = Carbon::parse($data["year"] )->year;
        $rs = $this->costs->create($data);
        if (!$rs) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function gettByID($id)
    {
        $account = $this->costs->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $account);
    }

    public function update($id, $data)
    {
        $totalPoints = array_reduce($data['data'], function ($carry, $item) {
            $cleanTotal = (int)str_replace([',', '.'], '', $item['total']);
            return $carry + $cleanTotal;
        }, 0);

        $data['costs'] = $totalPoints;
        $data["data"] =  json_encode($data['data']);
        $data['year'] = Carbon::parse($data["year"] )->year;
        $rs = $this->costs->getByID($id);
        if (!$rs) {
            return $this->_result(false, 'Not found!');
        }


        $result = $this->costs->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->costs->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
