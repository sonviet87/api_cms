<?php

namespace App\Services;

use App\Constants\RolePermissionConst;
use App\Interfaces\KpiCustomerInterface;
use App\Interfaces\KpiDebtsInterface;
use App\Interfaces\KpiMemberGroupsInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class KpiMemberGroupsService extends BaseService
{
    protected $kpiMemberGroups;
    protected $kpiCustomer;
    protected $kpiDebts;

    function __construct(KpiMemberGroupsInterface $kpiMemberGroups,KpiCustomerInterface $kpiCustomer,KpiDebtsInterface $kpiDebts)
    {
        $this->kpiMemberGroups = $kpiMemberGroups;
        $this->kpiCustomer = $kpiCustomer;
        $this->kpiDebts = $kpiDebts;
    }

    public function getList()
    {
        $filter=[];
        return $this->kpiMemberGroups->getList($filter);
    }

    public function getListPaginate($perPage = 20,$filter)
    {
        return $this->kpiMemberGroups->getListPaginate($perPage,$filter);
    }


    public function create($data)
    {
        $users = $data['users'];
        $customer_months_conditions = $data['customer_months_conditions'];
        $customer_3months_conditions = $data['customer_3months_conditions'];
        $customer_12months_conditions = $data['customer_12months_conditions'];
        $debts_months_conditions = $data['debts_months_conditions'];
        $debts_3months_conditions = $data['debts_3months_conditions'];
        $debts_12months_conditions = $data['debts_12months_conditions'];

        $arrKpiMemberGroups = Arr::except($data, ['users','customer_months_conditions','customer_3months_conditions','customer_12months_conditions','debts_months_conditions','debts_3months_conditions','debts_12months_conditions']);

        $memberGroups = $this->kpiMemberGroups->create($arrKpiMemberGroups);

        if (!$memberGroups) {
            return $this->_result(false, 'Created failed');
        }

        $id = $memberGroups->id;
        if(!empty($users)){
            //add to users
            $idUsers = collect($users)->pluck('id')->all();

            $memberGroups->users()->sync($idUsers);
            //add to customer groups

            $arrCustomerAll = array_merge($customer_months_conditions, $customer_3months_conditions, $customer_12months_conditions);
            $arrCustomer = [];
            foreach ($arrCustomerAll as $key => $item){

                $arrCustomer[$key]["group_id"] = $id;
                $arrCustomer[$key]["number"] = $item['number'];
                $arrCustomer[$key]["percentage"] = $item['percentage'];
                $arrCustomer[$key]["type"] = $item['type'];

            }

            $this->kpiCustomer->create($arrCustomer);
            //add to debts groups
            $arrDebtsAll = array_merge($debts_months_conditions, $debts_3months_conditions, $debts_12months_conditions);
            $arrDebts = [];
            foreach ($arrDebtsAll as $key => $item){
                $arrDebts[$key]["group_id"] = $id;
                $arrDebts[$key]["min_days"] = $item['min_days'];
                $arrDebts[$key]["max_days"] = $item['max_days'];
                $arrDebts[$key]["percentage"] = $item['percentage'];
                $arrDebts[$key]["type"] = $item['type'];

            }
            $this->kpiDebts->create($arrDebts);

        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $data = $this->kpiMemberGroups->getByID($id);
        if (!$data) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $data);
    }

    public function update($id, $data)
    {
        $rs = $this->kpiMemberGroups->getByID($id);
        if (!$rs) {
            return $this->_result(false, 'Not found!');
        }


        $result = $this->kpiMemberGroups->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->kpiMemberGroups->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
