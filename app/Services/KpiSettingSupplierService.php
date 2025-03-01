<?php

namespace App\Services;

use App\Constants\RolePermissionConst;
use App\Interfaces\KpiSettingSupplierInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KpiSettingSupplierService extends BaseService
{

    protected $kpiSettingSupplier;

    function __construct(KpiSettingSupplierInterface $kpiSettingSupplier)
    {
        $this->kpiSettingSupplier = $kpiSettingSupplier;

    }

    public function getList($filter=[])
    {
        return $this->kpiSettingSupplier->getList($filter);

    }

    public function getListPaginate($perPage = 20,$filter)
    {
        return $this->kpiSettingSupplier->getListPaginate($perPage,$filter);
    }


    public function create($data)
    {

        $arrSetting =  [
            'name' => $data['name'],
            'user_id' => $data['user_id'],
            'year' => Carbon::parse($data["year"] )->year,
            'new_supplier_conditions' => $data['new_supplier_conditions'],
            'new_supplier_target' => $data['new_supplier_target'],
            'old_supplier_conditions' => $data['old_supplier_conditions'],
            'old_supplier_target' => $data['old_supplier_target'],
            'old_supplier_percent' => $data['old_supplier_percent'],
            'new_supplier_percent' => $data['new_supplier_percent'],

        ];

        $kpiSetting = $this->kpiSettingSupplier->create($arrSetting);

        if (!$kpiSetting) {
            return $this->_result(false, 'Created failed');
        }


        return $this->_result(true, 'Created successfully');
    }



    public function getByID($id)
    {
        $data = $this->kpiSettingSupplier->getByID($id);

        if (!$data) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $data);
    }


    public function update($id, $data)
    {
        DB::beginTransaction();
        try {

            $kpiSetting = $this->kpiSettingSupplier->getByID($id);
            $arrSetting =  [
                'name' => $data['name'],
                'user_id' => $data['user_id'],
                'year' => Carbon::parse($data["year"] )->year,
                'new_supplier_conditions' => $data['new_supplier_conditions'],
                'new_supplier_target' => $data['new_supplier_target'],
                'old_supplier_conditions' => $data['old_supplier_conditions'],
                'old_supplier_target' => $data['old_supplier_target'],
                'old_supplier_percent' => $data['old_supplier_percent'],
                'new_supplier_percent' => $data['new_supplier_percent'],

            ];
            $this->kpiSettingSupplier->update($id,$arrSetting);



            DB::commit();
            return $this->_result(true, 'Updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->_result(false, 'Update failed: ' . $e->getMessage());
        }
    }



    public function destroyByIDs($ids)
    {
        if(empty($ids)) return $this->_result(false, 'Delete failed!');


        $check = $this->kpiSettingSupplier->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
