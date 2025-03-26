<?php

namespace App\Services;

use App\Constants\RolePermissionConst;
use App\Interfaces\AccountInterface;
use App\Interfaces\CostsFixedInterface;
use App\Interfaces\CostsInterface;
use App\Interfaces\CostsTeamInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CostsTeamService extends BaseService
{
    protected $costs;

    function __construct(CostsTeamInterface $costs)
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

        $data['year'] = Carbon::parse($data["year"] )->year;
        $temporaryData = [];

        if (isset($data['data']) && is_string($data['data'])) {
            $temporaryData = json_decode($data['data'], true);


            if (json_last_error() !== JSON_ERROR_NONE || !is_array($temporaryData)) {
                return $this->_result(false, 'Invalid JSON format for data');
            }
        }


        $totalSales = 0;
        $totalHumanCosts = 0;
        if (!empty($temporaryData) && is_array($temporaryData)) {
            $totalSales = array_reduce($temporaryData, function ($carry, $item) {
                return $carry + (isset($item['sale']) ? (float)$item['sale'] : 0);
            }, 0);

            $totalHumanCosts = array_reduce($temporaryData, function ($carry, $item) {
                return $carry + (isset($item['human_cost']) ? (float)str_replace(',', '', $item['human_cost']) : 0);
            }, 0);
        }

        $data['total_costs'] = $totalSales;
        $data['human_costs'] = $totalHumanCosts;

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

    public function gettTeamByYear($year)
    {
        $rs = $this->costs->gettTeamByYear($year);
        if (!$rs) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $rs);
    }

    public function update($id, $data)
    {
        $data["data"] =  json_encode($data['data']);
        $data['year'] = Carbon::parse($data["year"] )->year;

        $temporaryData = [];

        if (isset($data['data']) && is_string($data['data'])) {
            $temporaryData = json_decode($data['data'], true);


            if (json_last_error() !== JSON_ERROR_NONE || !is_array($temporaryData)) {
                return $this->_result(false, 'Invalid JSON format for data');
            }
        }


        $totalSales = 0;
        $totalHumanCosts = 0;
        if (!empty($temporaryData) && is_array($temporaryData)) {
            $totalSales = array_reduce($temporaryData, function ($carry, $item) {
                return $carry + (isset($item['sale']) ? (float)$item['sale'] : 0);
            }, 0);

            $totalHumanCosts = array_reduce($temporaryData, function ($carry, $item) {
                return $carry + (isset($item['human_cost']) ? (float)str_replace(',', '', $item['human_cost']) : 0);
            }, 0);
        }

        $data['total_costs'] = $totalSales;
        $data['human_costs'] = $totalHumanCosts;

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
