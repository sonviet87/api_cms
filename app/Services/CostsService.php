<?php

namespace App\Services;

use App\Constants\RolePermissionConst;
use App\Interfaces\AccountInterface;
use App\Interfaces\CostsFixedInterface;
use App\Interfaces\CostsInterface;
use App\Interfaces\CostsTeamInterface;
use Illuminate\Support\Facades\Auth;

class CostsService extends BaseService
{
    protected $costsTeam;
    protected $costsFixed;

    function __construct(CostsTeamInterface  $costsTeam,CostsFixedInterface $costsFixed)
    {
        $this->costsTeam = $costsTeam;
        $this->costsFixed = $costsFixed;
    }

    public function getAll($IDs)
    {
        return $this->costs->getAll($IDs);
    }
    public function getList($filter)
    {
        if(!isset($filter['year']) && $filter['year'] =='') return $this->_result(false, 'Không thể tạo kpi');
        $rsCostsTeam = $this->costsTeam->gettTeamByYear($filter['year']);
        if($rsCostsTeam->count() == 0) return $this->_result(false, 'Không tìm thấy nhóm');

        $mergedData = [];
        $totalSale = 0;
        $totalHumaneCosts = 0;
        foreach ($rsCostsTeam as $team) {
            $totalSale += $team['total_costs'];
            $totalHumaneCosts += $team['human_costs'];

            if (is_array($team['data'])) {
                $mergedData = array_merge($mergedData, $team['data']);
            }
        }

        $rsCostsFixed = $this->costsFixed->getByYear($filter['year']);
        $CostsFixed = 0;
        if($rsCostsFixed) $CostsFixed = $rsCostsFixed['costs'];
        $data = [
            'list' =>$mergedData,
            'total_sale' =>$totalSale,
            'total_human_costs' =>$totalHumaneCosts,
            'costs_fixed' =>$CostsFixed,
        ];
        return $this->_result(true, '',$data);


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
        $account = $this->costs->create($data);
        if (!$account) {
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
        $account = $this->costs->getByID($id);
        if (!$account) {
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
