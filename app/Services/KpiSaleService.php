<?php

namespace App\Services;

use App\Constants\DebtsConst;
use App\Http\Resources\UserCollection;
use App\Interfaces\AccountInterface;
use App\Interfaces\DebtInterface;
use App\Interfaces\FPInterface;
use App\Interfaces\KpiMemberGroupsInterface;
use App\Interfaces\KpiSettingsInterface;
use App\Interfaces\KpiSettingTotalInterface;
use App\Interfaces\KpiSetUpUserInterface;
use App\Interfaces\SysKpiInterface;

class KpiSaleService extends BaseService
{

    protected $fp;

    protected $debts;
    protected $setting;
    protected $settingTotal;
    protected $account;
    protected $sysKpi;
    protected $kpiSetUpUser;
    function __construct(FPInterface $fp,SysKpiInterface $sysKpi,DebtInterface $debts,KpiSettingsInterface $kpiSettings,KpiSettingTotalInterface $settingTotal,AccountInterface $account,KpiSetUpUserInterface $kpiSetUpUser)
    {
        $this->sysKpi = $sysKpi;
        $this->fp = $fp;
        $this->debts = $debts;
        $this->setting = $kpiSettings;
        $this->settingTotal = $settingTotal;
        $this->account = $account;
        $this->kpiSetUpUser = $kpiSetUpUser;
    }

    public function getList($filter)
    {
       return $this->getKpisales($filter);

    }

    public function getListKpiSale($filter){
        if(!isset($filter['listKpi']) && $filter['listKpi'] =='') return $this->_result(false, 'Không thể tạo kpi');
        $arrListKpi = [];
        $listKpiArray = explode(',', $filter['listKpi']);
        foreach ($listKpiArray as $item){

            $filterKpi = $filter;
            $filterKpi['groupMember'] = $item;

            $rs =  $this->getKpisales($filterKpi);

            $arrParams = [
                'total_selling'=>$rs->get('total_selling'),
                'sale_achievements' => $rs->get('sale_achievements'),
                'current_sale_achievements' => $rs->get('current_sale_achievements'),
                'debts_kpi' => $rs->get('debts_kpi'),
                'total_achievements' => $rs->get('total_achievements'),
                'total_percentage' => $rs->get('total_percentage'),
                'sale_setting_total' => $rs->get('sale_setting_total'),
                'target_sale' => $rs->get('target_sale'),
                'target_current_sale' => $rs->get('target_current_sale'),
                'salary' => $rs->get('salary'),
                'staff_manager' => $rs->get('staff_manager'),
                'kpi_company' => $rs->get('kpi_company'),
                'target_company' => $rs->get('target_company'),
                'total_selling_year' => $rs->get('total_selling_year'),
                'total_points' => $rs->get('total_points'),
                'target_kpi_company' => $rs->get('target_kpi_company'),

                'total_all_bouns' => $rs->get('total_all_bouns'),
                'user_name' => $rs->get('user_name'),
                'bg_color1' => $rs->get('bg_color1'),
                'bg_color2' => $rs->get('bg_color2'),
            ];
            $arrListKpi[] = $arrParams;
        }


        return $arrListKpi;
    }


    public function getKpisales($filter){
        if(isset($filter['groupMember']) && $filter['groupMember'] !=''){

            $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_1;
            $kpiSetUpUser = $this->kpiSetUpUser->getByID($filter['groupMember']);
           // dd($kpiSetUpUser);
            if($kpiSetUpUser==null)  return $this->_result(false, 'Không thể tạo kpi');
                $salesTargetMonths = $kpiSetUpUser->sales_months;
                $salesTargetMonths3 = $kpiSetUpUser->sales_3_months;
                $salesTargetMonths12 = $kpiSetUpUser->sales_12_months;
                $currentSalesTargetMonths = $kpiSetUpUser->current_sale_months;
                $currentSalesTargetMonths3 = $kpiSetUpUser->current_sale_3_months;
                $currentSalesTargetMonths12 = $kpiSetUpUser->current_sale_12_months;
                if($typeKpi== DebtsConst::MONTHS_1){
                    $perSale = $kpiSetUpUser->sales_months_percent;
                    $perCurrentSale = $kpiSetUpUser->current_sale_months_percent;
                    $perDebts  = $kpiSetUpUser->debts_1_percent;
                }
                elseif($typeKpi== DebtsConst::MONTHS_3) {
                    $perSale = $kpiSetUpUser->sales_3_months_percent;
                    $perCurrentSale = $kpiSetUpUser->current_sale_3_months_percent;
                    $perDebts  = $kpiSetUpUser->debts_3_percent;

                }
                else {
                    $perSale = $kpiSetUpUser->sales_12_months_percent;
                    $perCurrentSale = $kpiSetUpUser->current_sale_12_months_percent;
                    $perDebts  = $kpiSetUpUser->debts_12_percent;
                }


                $userID = $kpiSetUpUser->user_id;
                $filter['users'] = [$userID];
                //dd($filter);
                $rs = $this->fp->getListbyUsers($filter);
                $totalSelling = $rs->sum('selling');

                $targetSale = "";
                $targetCurrentSale = "";

                if($typeKpi== DebtsConst::MONTHS_1){
                    $targetSale = $salesTargetMonths;
                    $targetCurrentSale = $currentSalesTargetMonths;

                }
                elseif($typeKpi== DebtsConst::MONTHS_3) {
                    $targetSale =$salesTargetMonths3 ;
                    $targetCurrentSale =$currentSalesTargetMonths3 ;

                }
                else {
                    $targetSale =$salesTargetMonths12 ;
                    $targetCurrentSale =$currentSalesTargetMonths12 ;

                }
                /////////////////
                /// get Sale
                /// ////////////
                $total_percent_staff = 0;
                $total_points_staff = 0;
                $total_achievement_staff = 0;

                $staff_manager = $this->getStaffManager($kpiSetUpUser,$filter);
                //dd($staff_manager);
                if(count($staff_manager)>0){

                    $total_points_staff = collect($staff_manager)->sum(function ($item) {
                        return $item['total_points'];
                    });

                    $total_achievement_staff = collect($staff_manager)->sum(function ($item) {
                        $percentage = $item['percent'];
                        $points = $item['total_points'];
                        return ($percentage * $points) / 100;
                    });
                }


                $sale = $this->getSale($kpiSetUpUser,$totalSelling,$filter,'sale');


                $sale_achievements = ($perSale/100) * $sale->points;

                $current_sale= $this->getSale($kpiSetUpUser,$totalSelling,$filter,'current');
                $current_achievements = ($perCurrentSale/100) * $current_sale->points;


                ['total_debts' => $totalDebts,'over_due_debts'=> $over_due_debts,'debts_kpi' => $debts_kpi] = $this->getDebts($kpiSetUpUser,$filter);

                $debts_achievements = ($perDebts/100) * $debts_kpi->points;


                $tagretKpiYear = 0;
                //get percent total settings
                if($typeKpi==12) {

                    $rsYear = $this->sysKpi->getByID(1);
                    if($rsYear) $tagretKpiYear = $rsYear->kpi_company;

                    $rs->put('target_company', $rsYear->kpi_company);

                    //get total selling year
                    $filteryear = $filter;
                    unset($filteryear['users']);

                    $rsListYear = $this->fp->getListbyUsers($filteryear);
                    $totalSellingYear = $rsListYear->sum('selling');
                    $rs->put('total_selling_year', $totalSellingYear);
                    //percent year
                    //$percent_year = round(($totalSellingYear/$tagretKpiYear)*100,);

                    $settingCompany = $this->getKpiSettingsYear($totalSellingYear, $filter);
                    $filter['parent_id'] = $settingCompany->id;
                    if($settingCompany != null)  $rs->put('kpi_company', (object)[
                        'bonus' => $settingCompany['bonus'] ?? 0,
                        'points' => $settingCompany['points'] ?? 0,
                        'name' => $settingCompany['name'] ?? 0,
                    ]);

                }


                $total_points = $sale->points+ $current_sale->points+ $debts_kpi->points+$total_points_staff;
                $total_achievements= round($sale_achievements + $current_achievements + $debts_achievements+$total_achievement_staff,2);
               // $total_percentage = $sale->percentage + $current_sale->percentage +$debts_kpi->percentage + $total_percent_staff;

                $settingRs = $this->getKpiSettings($total_achievements,$filter,$kpiSetUpUser);

                $salary = $kpiSetUpUser->user->salary->salary;
                $totalAllBouns = 0;
                if($typeKpi==12) {
                    if($settingRs->bonus <1 ||  $settingCompany->bonus <1){
                        $totalAllBouns = ($settingRs->bonus*  $salary) + ($settingCompany->bonus*  $salary);
                    }
                    else {
                        $totalAllBouns = $settingCompany->bonus *  $settingRs->bonus * $salary;

                    }
                }else{
                    $totalAllBouns = $settingRs->bonus* $salary;
                }


                $debts_kpi_filtered = [
                    'percentage' => $debts_kpi->percentage,
                    'points' => $debts_kpi->points,
                    'debts_late' => $over_due_debts,
                    'total_debts' => $totalDebts
                ];
                //get min setting total

                $salesMonths = (float) $kpiSetUpUser->sales_months;
                $totalSelling = (float) $totalSelling;
                $total_percentage = $totalSelling != 0 ? round(( $totalSelling/$salesMonths) * 100, 2) : 0;

                //dd($total_percentage);
               // ['min_kpi_grogress' =>$minGrogressSetting ,'min_kpi_setting' =>$minConditionsSetting]= $this->getMinSettingBouns($total_percentage,$typeKpi);

                //get color label
                $bgcorlor1 = "green";
                $bgcorlor2 = "#a09c9c";
                $currentDate = now();
                $dayOfMonth = $currentDate->day;
                if($dayOfMonth > 20 && $kpiSetUpUser->sales_months < $totalSelling) $bgcorlor1 = "red";
                if($dayOfMonth > 20 && $total_percentage == 0) $bgcorlor2 = "red";


                $rs->put('bg_color1', $bgcorlor1);
                $rs->put('bg_color2', $bgcorlor2);
                $rs->put('total_achievements', $total_achievements);
                $rs->put('total_percentage', $total_percentage);
                $rs->put('total_selling', $totalSelling);


                $rs->put('sale_achievements', $sale);
                $rs->put('current_sale_achievements', $current_sale);
                $rs->put('target_current_sale', $targetCurrentSale);
                $rs->put('total_debts', $totalDebts);
                $rs->put('debts_kpi', $debts_kpi_filtered);
                $rs->put('sale_setting_total', $settingRs);
                $rs->put('target_sale', $targetSale);
                $rs->put('salary', $salary);
                $rs->put('staff_manager', $staff_manager);
                $rs->put('total_points', $total_points);
                $rs->put('target_kpi_company', $tagretKpiYear);
                $rs->put('total_all_bouns', $totalAllBouns);
                $rs->put('user_name', $kpiSetUpUser->user->name);
                $rs->put('percent_sale', $perSale);
                $rs->put('percent_current_sale', $perCurrentSale);
                $rs->put('percent_debts', $perDebts);
                $rs->put('sale_text', $kpiSetUpUser->sale_text);
                $rs->put('current_sale_text', $kpiSetUpUser->current_sale_text);
                $rs->put('debts_text', $kpiSetUpUser->debts_text);

                return $rs;
            }


        return $this->_result(false, 'Không thể tạo kpi');
    }
    protected  function getTotalKPIUser($kpiSetUpUser,$filter){
        if(isset($filter['groupMember']) && $filter['groupMember'] !=''){

            if($kpiSetUpUser!=null){
                $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_1;
                if($typeKpi== DebtsConst::MONTHS_1){
                    $perSale = $kpiSetUpUser->sales_months_percent;
                    $perCurrentSale = $kpiSetUpUser->current_sale_months_percent;
                    $perDebts  = $kpiSetUpUser->debts_1_percent;
                }
                elseif($typeKpi== DebtsConst::MONTHS_3) {
                    $perSale = $kpiSetUpUser->sales_3_months_percent;
                    $perCurrentSale = $kpiSetUpUser->current_sale_3_months_percent;
                    $perDebts  = $kpiSetUpUser->debts_3_percent;

                }
                else {
                    $perSale = $kpiSetUpUser->sales_12_months_percent;
                    $perCurrentSale = $kpiSetUpUser->current_sale_12_months_percent;
                    $perDebts  = $kpiSetUpUser->debts_12_percent;
                }


                $userID = $kpiSetUpUser->user_id;
                $filter['users'] = [$userID];

                $rs = $this->fp->getListbyUsers($filter);
                $totalSelling = $rs->sum('selling');

                $sale = $this->getSale($kpiSetUpUser,$totalSelling,$filter,'sale');
                $current_sale= $this->getSale($kpiSetUpUser,$totalSelling,$filter,'current');

                ['debts_kpi' => $debts_kpi] = $this->getDebts($kpiSetUpUser,$filter);

               return (($perSale/100) * $sale->points) + (($perCurrentSale/100) * $current_sale->points) +(($perDebts/100) * $debts_kpi->points)??0;
            }

        }
        return 0;
    }
    private function getDebts($kpiSetUpUser, $filter)
    {
        $filter['user_id'] = $kpiSetUpUser->user_id;

        $type = $filter['type'] ?? DebtsConst::MONTHS_1;

        [
            'overdueDebts' => $overdueDebts,
            'totalDebts' => $totalDebts,
        ] = $this->debts->getOverdueDebts($filter);
        $totalOverDebst = $overdueDebts->count();
        if($totalDebts== 0 || $totalDebts<= $totalOverDebst) {
            return [
                'over_due_debts' => $totalOverDebst,
                'total_debts' => $totalDebts,
                'debts_kpi' => (object)['number' => 0, 'percentage' => 0, 'points' => 0],

            ];
        }

        $conditionsDebts = $kpiSetUpUser->debtItems->filter(function ($condition) use ($type) {
            return $condition->type === $type.'months';
        });

        $goalDebts= $conditionsDebts->firstWhere('number', $totalOverDebst)
            ?: $conditionsDebts
                ->where('number', '<', $totalOverDebst)
                ->sortByDesc('number')
                ->first();
        return [
            'over_due_debts' => $totalOverDebst,
            'total_debts' => $totalDebts,
            'debts_kpi' => $goalDebts
        ];
    }

    private function getStaffManager($kpiSetUpUser, $filter)
    {
        $type = $filter['type'] ?? DebtsConst::MONTHS_1;


        $resultData = [];

        if ($kpiSetUpUser) {
            $filteredStaffManagers = $kpiSetUpUser->staffManagers->filter(function ($manager) use ($type) {

                    return $manager->type === $type . 'months'  && $manager->kpi_type == 'sale';

            });
            foreach ($filteredStaffManagers as $manager) {
                if($manager->user_id == null) continue;
                $user_name = $manager->user->name;
                $filterStaff = $filter;
                $filterStaff['users'] = [$manager->user_id];
                $kpiUser = $this->kpiSetUpUser->getByUserID($manager->user_id)->first();
                $totalPointsSale = $this->getTotalKPIUser($kpiUser, $filterStaff);
                $resultData[] = [
                    'user_id' => $manager->user_id,
                    'percent' => $manager->percent,
                    'user_name' => $user_name,
                    'total_points' =>$totalPointsSale,

                ];
            }
            /*$filteredStaffManagers = $kpiSetUpUser->staffManagers->filter(function ($manager) use ($type) {
                $manager->staffConditions = $manager->staffConditions->filter(function ($condition) use ($type) {
                    return $condition->type === $type . 'months';
                });
                return $manager->type === $type . 'months' && $manager->staffConditions->isNotEmpty();
            });

            foreach ($filteredStaffManagers as $manager) {
                if($manager->user_id == null) continue;
                $user_name = $manager->user->name;
                $filterStaff = $filter;
                $filterStaff['users'] = [$manager->user_id];
                $kpiUser = $this->kpiSetUpUser->getByUserID($manager->user_id)->first();
                $totalPercentSale = $this->getTotalKPIUser($kpiUser, $filterStaff);
                //$totalPercentSale = 90;

                $result = $manager->staffConditions
                    ->where('number', '<=', $totalPercentSale)
                    ->sortByDesc('number')
                    ->first();

                if (!$result) {
                    $maxCondition = $manager->staffConditions->sortByDesc('number')->first();
                    $minCondition = $manager->staffConditions->sortBy('number')->first();

                    if ($totalPercentSale < $minCondition->number) {
                        $result = collect(['number' => 0, 'percentage' => 0, 'points' => 0]);
                    } else {
                        $result = $maxCondition;
                    }
                }
                $result = $result->only(['number', 'percentage', 'points']);
                $resultData[] = [
                    'user_id' => $manager->user_id,
                    'user_name' => $user_name,
                    'total_percent' =>$totalPercentSale,
                    'kpi' => $result
                ];
            }*/
        }
        return $resultData;
    }

    private function getSale($kpiSetUpUser, $totalSelling,$filter,$type)
    {

        $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_1;

        $conditionsSale = $kpiSetUpUser->saleItems()->get();

        $conditionsSaleTypeList = $conditionsSale->filter(function ($item) use ($typeKpi,$type) {
            return $item['type_kpi'] == $type && $item['type'] == $typeKpi .'months';
        });

        // Tìm điều kiện phù hợp
        $matchedCondition = $conditionsSaleTypeList->first(function ($item) use ($totalSelling) {
            $min = $item['min'] != null ? (float)$item['min'] : null;
            $max = $item['max'] != null ? (float)$item['max'] : null;

            return ($totalSelling >= $min) &&  ($totalSelling <= $max);
        });

        // Nếu tìm thấy điều kiện phù hợp
        $rsCondition = null;
        if ($matchedCondition) {
            $rsCondition= $matchedCondition;
        }

        // Nếu không tìm thấy điều kiện, so sánh với min và max
        $minCondition = $conditionsSaleTypeList->sortBy('min')->first();
        $maxCondition = $conditionsSaleTypeList->sortByDesc('max')->first();

        if ($minCondition && $totalSelling < (float)$minCondition['min']) {
            $rsCondition = 0; // Trả về 0 nếu nhỏ hơn giá trị min
        }

        if ($maxCondition && $totalSelling > (float)$maxCondition['max']) {
            $rsCondition = $maxCondition; // Trả về dòng max nếu lớn hơn giá trị max
        }

        $resultCondition = null;
        if ($rsCondition === 0) {
            $resultCondition = (object)['percentage' => 0, 'points' => 0];
        } elseif ($rsCondition) {
            $resultCondition = (object)[
                'percentage' => $rsCondition['percentage'] ?? 0,
                'points' => $rsCondition['points'] ?? 0,
            ];
        }
        return  $resultCondition;

    }
    /*private function getKpiSettings($totalGoals,$filter){
       $totalGoals = round($totalGoals);// total percentage

        $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_1;
        $conditionsSettings = $this->settingTotal->getList();

        $conditionsSettingsType = $conditionsSettings->filter(function ($item) use ($typeKpi,$filter){
            return $item['type'] === $typeKpi.'months' && $item['type_kpi'] ==='sale';

        });

        // find condtion
        $matchedCondition = $conditionsSettingsType->first(function ($item) use ($totalGoals) {
            $min = $item['min'] != null ? (float)$item['min'] : null;
            $max = $item['max'] != null ? (float)$item['max'] : null;

            return ($totalGoals >= $min) &&  ($totalGoals <= $max);
        });
        // if exist
        if ($matchedCondition)  return  (object)[
            'bonus' => $matchedCondition['bonus'] ?? 0,
            'points' => $matchedCondition['points'] ?? 0,
            'name' => $matchedCondition['name'] ?? 0,

        ];;

        // if not exist, compare min và max
        $minCondition = $conditionsSettingsType->sortBy('min')->first();
        $maxCondition = $conditionsSettingsType->sortByDesc('max')->first();

        if ($minCondition && $totalGoals < (float)$minCondition['min']) {
           return  (object)['bonus' => 0, 'points' => 0,'name' => 'Không đạt','color'=>'red'];
        }

        if ($maxCondition && $totalGoals > (float)$maxCondition['max']) {
            return (object)[
                'bonus' => $maxCondition['bonus'] ?? 0,
                'points' => $maxCondition['points'] ?? 0,
                'name' => $maxCondition['name'] ?? 0,

            ];
        }

        return  (object)['bonus' => 0, 'points' => 0,'name' => 'Không đạt','color'=>'red'];

    }*/
    private function getKpiSettings($totalGoals, $filter,$kpiSetUpUser) {

        if ($totalGoals == 0) {
            return (object)['bonus' => 0, 'points' => 0, 'name' => 'Không đạt', 'color' => 'red'];
        }
        $countUser = count($kpiSetUpUser->user->subordinates()->get());
        $typeText =  $countUser> 0 ? 'manager':'sale';

        $totalGoals = floor($totalGoals);
        $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_1;
        $conditionsSettings = $this->settingTotal->getList();



        $conditionsSettingsType = $conditionsSettings->filter(function ($item) use ($typeKpi,$typeText) {
            return $item['type'] === $typeKpi . 'months' && $item['type_kpi'] === $typeText;
        });

        // Sắp xếp danh sách theo points để tìm giá trị lớn nhất
        $sortedConditions = $conditionsSettingsType->sortByDesc('points');

        // Tìm kiếm dòng có points khớp với totalGoals
        $matchedCondition = $sortedConditions->first(function ($item) use ($totalGoals) {
            return (float)$item['points'] == $totalGoals;
        });

        if ($matchedCondition) {
            return (object)[
                'bonus' => $matchedCondition['bonus'] ?? 0,
                'points' => $matchedCondition['points'] ?? 0,
                'name' => $matchedCondition['name'] ?? 'Không đạt',
            ];
        }

        // Nếu tổng điểm lớn hơn tất cả, trả về dòng có points lớn nhất
        $maxCondition = $sortedConditions->first();
        if ($maxCondition && $totalGoals > (float)$maxCondition['points']) {
            return (object)[
                'bonus' => $maxCondition['bonus'] ?? 0,
                'points' => $maxCondition['points'] ?? 0,
                'name' => $maxCondition['name'] ?? 'Không đạt',
            ];
        }

        return (object)['bonus' => 0, 'points' => 0, 'name' => 'Không đạt', 'color' => 'red'];
    }


    private function getMinSettingBouns($total_percentage, $typeKpi)
    {
        $conditionsSettingsType = $this->settingTotal->getList();
        $kpiRows = $conditionsSettingsType->filter(function ($item) use ($typeKpi) {
            return $item['type'] === $typeKpi . 'months' && $item['type_kpi'] === 'sale';
        });

        $kpiRows = $kpiRows->map(function ($row) {
            $row->min = (int) $row->min;
            return $row;
        });

        $sortedRows = $kpiRows->sortBy('min')->values();
        $minRow =$sortedRows->first();

        foreach ($sortedRows as $index => $row) {
            // Kiểm tra nếu input nhỏ hơn 'min' nhỏ nhất
            if ($index === 0 && $total_percentage < $row->min) {
                return [
                  'min_kpi_grogress' =>  $row,
                  'min_kpi_setting' =>$minRow
                ];
            }

            // Nếu số nằm giữa hoặc bằng 'min' hiện tại và nhỏ hơn hoặc bằng 'min' dòng tiếp theo
            if ($index < $sortedRows->count() - 1) {
                $nextRow = $sortedRows[$index + 1];
                if ($total_percentage >= $row->min && $total_percentage < $nextRow->min) {
                    return [
                        'min_kpi_grogress' =>  $nextRow,
                        'min_kpi_setting' =>$minRow
                    ];

                }
            } else {
                // Trường hợp số lớn hơn hoặc bằng 'min' của dòng cuối cùng
                if ($total_percentage >= $row->min) {
                    return [
                        'min_kpi_grogress' =>  $row,
                        'min_kpi_setting' =>$minRow
                    ];
                }
            }
        }

        return null;
    }


    private function getKpiSettingsYear($totalGoals,$filter){

        $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_1;
        $conditionsSettings = $this->settingTotal->getList();
        $conditionsSettingsType = $conditionsSettings->filter(function ($item) use ($typeKpi){
            return $item['type'] === $typeKpi.'months' && $item['type_kpi'] ==='company';
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
            return  (object)['bonus' => 0, 'points' => 0,'name' => 'Không đạt','color'=>'red'];
        }
       // dd($conditionsSettingsType);
        if ($maxCondition && $totalGoals > (float)$maxCondition['max']) {
            return $maxCondition;
        }

        return  (object)['bonus' => 0, 'points' => 0,'name' => 'Không đạt','color'=>'red'];

    }





}
