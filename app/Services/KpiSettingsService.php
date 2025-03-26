<?php

namespace App\Services;


use App\Interfaces\KpiSettingsInterface;
use Illuminate\Support\Arr;


class KpiSettingsService extends BaseService
{

    protected $kpiSetting;

    function __construct(KpiSettingsInterface $kpiSettings)
    {
        $this->kpiSettings = $kpiSettings;
    }

    public function getList()
    {

        return $this->kpiSettings->getList();
    }

    public function create($data)
    {
        $rs = $this->kpiSettings->create($data);
        if (!$rs) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $data = $this->kpiSettings->getByID($id);
        if (!$data) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $data);
    }

    public function update($id, $data)
    {

        $arrMonths = $data['months'] ;
        $arrMonths3 = $data['months3'] ;
        $arrMonths12 = $data['months12'] ;
        $arrMergeSettings = array_merge($arrMonths,$arrMonths3,$arrMonths12);
        //deleted row
        $idArIDSettings = Arr::pluck($arrMergeSettings, 'id');
        $arrIDs = $this->getList()->pluck('id')->all();
        $idsSettingsDiff = array_diff($arrIDs,$idArIDSettings);
        if($idsSettingsDiff){
            $this->kpiSettings->destroy($idsSettingsDiff);
        }

        $arrSettings = [];
        foreach ($arrMergeSettings as $key => $item){


            $arrSettings[$key]["max_percentage"] = $item['max_percentage'];
            $arrSettings[$key]["min_percentage"] = $item['min_percentage'];
            $arrSettings[$key]["percentage"] = $item['percentage'];
            $arrSettings[$key]["type"] = $item['type'];
            if(isset($item['id'])){
                $this->kpiSettings->update($item['id'],$arrSettings[$key]);
            }else{
                $this->kpiSettings->create($arrSettings[$key]);
            }

        }


        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->kpiSettings->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
