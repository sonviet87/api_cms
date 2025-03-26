<?php

namespace App\Services;

use App\Constants\DebtsConst;
use App\Http\Resources\UserCollection;
use App\Interfaces\AccountInterface;
use App\Interfaces\DebtInterface;
use App\Interfaces\FPInterface;
use App\Interfaces\KpiMemberGroupsInterface;
use App\Interfaces\KpiSettingsInterface;
use App\Interfaces\KpiSettingSupplierInterface;
use App\Interfaces\KpiSettingTotalInterface;
use App\Interfaces\KpiSetUpUserInterface;
use App\Interfaces\SupplierInterface;
use App\Interfaces\SysKpiInterface;
use Carbon\Carbon;

class KpiSupplierService extends BaseService
{
    protected $fp;
    protected $settingTotal;
    protected $sysKpi;
    protected $kpiSettings;
    protected $supplier;
    function __construct(KpiSettingSupplierInterface $kpiSettings,KpiSettingTotalInterface $settingTotal,SupplierInterface $supplier,FPInterface $fp,SysKpiInterface $sysKpi)
    {
        $this->sysKpi = $sysKpi;
        $this->supplier = $supplier;
        $this->kpiSettings = $kpiSettings;
        $this->fp = $fp;
        $this->settingTotal = $settingTotal;
    }

    public function getList($filter)
    {
        if(isset($filter['groupMember']) && $filter['groupMember'] !=''){

            $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_1;

            $kpiSettingSupplier= $this->kpiSettings->getByID($filter['groupMember']);
            $userID = $kpiSettingSupplier->user_id;
            $filter['users'] = $userID;

            if($kpiSettingSupplier==null) return $this->_result(false, 'Không thể tạo kpi');
            $newSupplier=  $this->getNewSupplier($filter,$kpiSettingSupplier);

            $oldSupplierIncrease = $this->getOldSupplierIncreamDebts($filter,$kpiSettingSupplier);

            $total_achievement_new_supplier = ($kpiSettingSupplier->new_supplier_percent/100)*$newSupplier['new_supplier_conditions']['points'];
            $total_achievement_old_supplier = ($kpiSettingSupplier->old_supplier_percent/100)*$oldSupplierIncrease['old_increase_supplier_conditions']['points'];
            $total_achievement= $total_achievement_new_supplier + $total_achievement_old_supplier;

            //$total_percent = $newSupplier['new_supplier_conditions']['percentage'] + $oldSupplierIncrease['old_increase_supplier_conditions']['percentage'];
            $total_points = $newSupplier['new_supplier_conditions']['points'] + $oldSupplierIncrease['old_increase_supplier_conditions']['points'];
           //get kpi personal
            $personalKPI = $this->getKpiSettingsTotal($total_achievement, $filter,'buy_goods');
            //get kpi company
            $rsYear = $this->sysKpi->getByID(1);
            $targetKpiYear = $rsYear->kpi_company;
            $total_selling_year = 0;

            ['total_selling_year'=>$total_selling_year,'kpi_company'=>$companyKPI ]= $this->getKPICompany($filter,$targetKpiYear);

            $salary = $kpiSettingSupplier->user->salary->salary;
            $totalAllBouns = 0;
            if($personalKPI['bonus'] <1 ||  $companyKPI['bonus'] <1){
                $totalAllBouns = ($personalKPI['bonus']*  $salary) + ($companyKPI['bonus']*  $salary);
            }
            else {
                $totalAllBouns = $personalKPI['bonus'] *  $companyKPI['bonus'] * $salary;

            }


            return [
                'new_supplier' => $newSupplier['new_supplier'],
                'new_supplier_conditions' => $newSupplier['new_supplier_conditions'],
                'old_increase_supplier' => $oldSupplierIncrease['old_increase_supplier'],
                'old_increase_supplier_conditions' => $oldSupplierIncrease['old_increase_supplier_conditions'],
                'total_achievement' => $total_achievement,

                'total_points' => $total_points,
                'total_bouns' => $totalAllBouns,
                'kpi_personnal' =>  collect($personalKPI),
                'target_company' =>  $targetKpiYear,
                'kpi_company' => collect($companyKPI),
                'new_supplier_target' => $kpiSettingSupplier->new_supplier_target,
                'old_supplier_target' => $kpiSettingSupplier->old_supplier_target,
                'salary' => $salary,
                'total_selling_year' => $total_selling_year,
                'new_supplier_percent' => $kpiSettingSupplier->new_supplier_percent,
                'old_supplier_percent' => $kpiSettingSupplier->old_supplier_percent,

            ];
        }
        return $this->_result(false, 'Không thể tạo kpi');
    }

    public function getKPICompany($filter,$targetKpiYear){

        $filteryear = $filter;
        $filteryear['startDay'] = Carbon::create($filter['selectedYear'])->startOfYear();
        $filteryear['endDay'] = Carbon::create($filter['selectedYear'])->endOfYear();
        unset($filteryear['users']);

        $rsListYear = $this->fp->getListbyUsers($filteryear);
        $totalSellingYear = $rsListYear->sum('selling');
        //$percent_year = round(($totalSellingYear/$targetKpiYear)*100,);
        //dd($percent_year);
        $rs =  $this->getKpiSettingsYear($totalSellingYear, $filter,'company');

        return [
            'kpi_company' => $rs,
            'total_selling_year' => $totalSellingYear
        ];
    }

    private function getKpiSettingsYear($totalGoals,$filter,$kpi_type){

        $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_12;
        $conditionsSettings = $this->settingTotal->getList();
        $conditionsSettingsType = $conditionsSettings->filter(function ($item) use ($typeKpi,$kpi_type){
            return $item['type'] === $typeKpi.'months' && $item['type_kpi'] === $kpi_type;
        });

        // find condtion
        $matchedCondition = $conditionsSettingsType->first(function ($item) use ($totalGoals) {
            $min = $item['min'] != null ? (float)$item['min'] : null;
            $max = $item['max'] != null ? (float)$item['max'] : null;

            return ($totalGoals >= $min) &&  ($totalGoals <= $max);
        });


        // if exist
        if ($matchedCondition)  return  $matchedCondition;

        // if not exist, compare min và max
        $minCondition = $conditionsSettingsType->sortBy('min')->first();
        $maxCondition = $conditionsSettingsType->sortByDesc('max')->first();

        if ($minCondition && $totalGoals < (float)$minCondition['min']) {
            return  ['bonus' => 0, 'points' => 0,'name' => 'Không đạt'];
        }

        if ($maxCondition && $totalGoals > (float)$maxCondition['max']) {
            return $maxCondition;
        }

        return  ['bonus' => 0, 'points' => 0,'name' => 'Không đạt'];

    }
    private function getKpiSettingsTotal($totalGoals, $filter,$type)
    {
        //dd($totalGoals);
        $totalGoals = floor($totalGoals);
        $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_12;
        $conditionsSettings = $this->settingTotal->getList();
        $conditionsSettingsType = $conditionsSettings->filter(function ($item) use ($typeKpi,$type) {
            return $item['type'] === $typeKpi . 'months' && $item['type_kpi'] === $type;
        });

        if ($conditionsSettingsType->isEmpty()) {
            return ['bonus' => 0, 'points' => 0, 'name' => 'Không đạt'];
        }

        $sortedConditions = $conditionsSettingsType->sortBy('points');

        $minCondition = $sortedConditions->first();
        $maxCondition = $sortedConditions->last();

        if ($totalGoals <= 0 || $totalGoals < $minCondition['bonus']) {
            return ['bonus' => 0, 'points' => 0, 'name' => 'Không đạt'];
        }

        if ($totalGoals > $maxCondition['points']) {
            return $maxCondition;
        }

        return $sortedConditions->firstWhere('points', $totalGoals)
            ?: $sortedConditions
                ->where('bonus', '<', $totalGoals)
                ->sortByDesc('points')
                ->first();
    }
    public function getNewSupplier($filter, $kpiSettingTechnical)
    {
        $newSupplier = $this->supplier->getListNewSuplierbyUsers($filter);
        $newSupplier = $newSupplier->count();

        $newSupplierConditions = $kpiSettingTechnical->new_supplier_conditions ?? [];
        $rsConditionSupplier = ['number' => 0, 'percentage' => 0, 'points' => 0];

        if (!$newSupplierConditions || !is_array($newSupplierConditions) || $newSupplier == 0) {
            return [
                'new_supplier' => $newSupplier,
                'new_supplier_conditions' => $rsConditionSupplier
            ];
        }

        $maxCondition = null;

        foreach ($newSupplierConditions as $condition) {
            if (!$maxCondition || $condition['number'] > $maxCondition['number']) {
                $maxCondition = $condition;
            }

            if (isset($condition['number']) && $condition['number'] == $newSupplier) {
                $rsConditionSupplier = $condition;
                return [
                    'new_supplier' => $newSupplier,
                    'new_supplier_conditions' => $rsConditionSupplier
                ];
            }
        }

        if ($newSupplier > $maxCondition['number']) {
            $rsConditionSupplier = $maxCondition;
        }

        return [
            'new_supplier' => $newSupplier,
            'new_supplier_conditions' => $rsConditionSupplier
        ];
    }

    public function getOldSupplierIncreamDebts($filter, $kpiSettingTechnical){
        $rs = $this->supplier->getOldSupplierIncreaseDebts($filter);
       // dd($rs);
        $supplierIncrease = $rs->count();

        $supplierIncreaseConditions = $kpiSettingTechnical->old_supplier_conditions?? [];

        $rsConditionSupplier = ['number' => 0, 'percentage' => 0, 'points' => 0];

        if (!$supplierIncreaseConditions || !is_array($supplierIncreaseConditions) || $supplierIncrease == 0) {
            return [
                'old_increase_supplier' => $supplierIncrease,
                'old_increase_supplier_conditions' => $rsConditionSupplier
            ];
        }

        $maxCondition = null;

        foreach ($supplierIncreaseConditions as $condition) {
            if (!$maxCondition || $condition['number'] > $maxCondition['number']) {
                $maxCondition = $condition;
            }

            if (isset($condition['number']) && $condition['number'] == $supplierIncrease) {
                $rsConditionSupplier = $condition;
                return [
                    'old_increase_supplier' => $supplierIncrease,
                    'old_increase_supplier_conditions' => $rsConditionSupplier
                ];
            }
        }

        if ($supplierIncrease > $maxCondition['number']) {
            $rsConditionSupplier = $maxCondition;
        }

        return [
            'old_increase_supplier' => $supplierIncrease,
            'old_increase_supplier_conditions' => $rsConditionSupplier
        ];

    }





}
