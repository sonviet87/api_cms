<?php

namespace App\Services;

use App\Constants\DebtsConst;
use App\Http\Resources\UserCollection;
use App\Interfaces\DebtInterface;
use App\Interfaces\FPInterface;
use App\Interfaces\KpiMemberGroupsInterface;
use App\Interfaces\KpiSettingsInterface;

class KpiService extends BaseService
{

    protected $fp;
    protected $groupMember;
    protected $debts;
    protected $setting;
    function __construct(FPInterface $fp,KpiMemberGroupsInterface $groupMember,DebtInterface $debts,KpiSettingsInterface $kpiSettings)
    {
        $this->groupMember = $groupMember;
        $this->fp = $fp;
        $this->debts = $debts;
        $this->setting = $kpiSettings;
    }

    public function getList($filter)
    {
        if(isset($filter['groupMember']) && $filter['groupMember'] !=''){
            $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_1;
            $groupMember = $this->groupMember->getByID($filter['groupMember']);
            if($groupMember!=null){
                $profitTargetMonths = $groupMember->profit_months;
                $profitTargetMonths3 = $groupMember->profit_3_months;
                $profitTargetMonths12 = $groupMember->profit_12_months;
                $customerTargetMonths = $groupMember->customer_months;
                $customerTargetMonths3 = $groupMember->	customer_3_months;
                $customerTargetMonths12 = $groupMember->customer_12_months;

                $users = new UserCollection($groupMember->users()->get());
                $totalSalary = 0;

                foreach ($users as $employee) {
                    $totalSalary += (int) $employee->salary->salary;
                }

                $usersID = $users->pluck('id')->all();
                if(count($usersID)>0) $filter['users'] = $usersID;

                $profitType = DebtsConst::MONTHS_1;
                $customerTager = 0;
                if($typeKpi== DebtsConst::MONTHS_1){
                    $profitType = $profitTargetMonths;
                    $customerTager = $customerTargetMonths;
                }
                elseif($typeKpi== DebtsConst::MONTHS_3) {
                    $profitType =$profitTargetMonths3 ;
                    $customerTager = $customerTargetMonths3;
                }
                else {
                    $profitType =$profitTargetMonths12 ;
                    $customerTager = $customerTargetMonths12;
                }

                /////////////////
                /// get Accounts
                /// ////////////
                [
                    'totalMargin' => $totalMargin,
                    'goalPercentCustomer' =>$goalPercentCustomer,
                    'accoutResult' => $accoutResult,
                    'conditionsCustomer' =>$conditionsCustomer,
                    'list' => $rs

                ] = $this->getCustomer($groupMember,$filter,$customerTager);
                //get total percent profit



                $totlaPrecentProft = ($totalMargin/$profitType)*100;
                //get total percent profit max 70%
                $totlaPrecentProftMax70 = (($totalMargin*0.7)/$profitType)*100;
                ////////////////////////
                /// Debts
                /// ///////////////////
                [
                    "totalPercentDebuts" => $totalPercentDebuts,
                    "rsDebuts" => $rsDebuts,
                    "debuts_percent" => $arrPercentDebuts,
                    "conditionsDebtsTypeList" => $conditionsDebtsTypeList
                ] = $this->getDebts($groupMember,$filter);
                $goalPercentCustomerTotal = $goalPercentCustomer? $goalPercentCustomer->percentage : 0;

                //calculator total percent goals
                $totalGoals = $totlaPrecentProftMax70 + $goalPercentCustomerTotal + $totalPercentDebuts;
                //get percent total settings
                [ "percent" => $percentTotalSettings, "resultKpiGoals" => $resultKpiGoals,'record' => $record ]= $this->getKpiSettings($totalGoals,$filter);

                //revenues
                $revenues = ($totalMargin*$percentTotalSettings)/100;

                $totalProfitMargin = $totalMargin - ($totalSalary + $revenues);
                $totalPercentRevenues = 0;
                if($totalMargin!=0)  $totalPercentRevenues = ($totalProfitMargin/$totalMargin)*100;

                $rs->put('totalPercentRevenues', $totalPercentRevenues);
                $rs->put('totalProfitMargin', $totalProfitMargin);
                $rs->put('totalSalary', $totalSalary);
                $rs->put('percentTotalSettings', $percentTotalSettings);
                $rs->put('result_kpi_goals', $resultKpiGoals);
                $rs->put('totalGoals', $totalGoals);
                $rs->put('totalPercentDebuts', $totalPercentDebuts);
                $rs->put('total_profit', $totalMargin);
                $rs->put('debuts', $rsDebuts);
                $rs->put('debuts_percent', $arrPercentDebuts);
                $rs->put('conditions_debts_type_list', $conditionsDebtsTypeList);
                $rs->put('target_profit', $profitType);
                $rs->put('target_profit_months', $profitTargetMonths);
                $rs->put('target_profit_3_months', $profitTargetMonths3);
                $rs->put('target_profit_12_months', $profitTargetMonths12);
                $rs->put('target_customer_months', $customerTargetMonths);
                $rs->put('target_customer_3_months', $customerTargetMonths3);
                $rs->put('target_customer_12_months', $customerTargetMonths12);
                $rs->put('target_customer', $customerTager);
                $rs->put('record_setting_percent', $record);
                $rs->put('users', $users);
                $rs->put('revenues', $revenues);


               // $rs->put('account_id', $distinctAccount);
               // $rs->put('account_id_test',$distinctAccountAll);
                $rs->put('account_new',$accoutResult);
                $rs->put('total_account_new',count($accoutResult));
               // $rs->put('customer_conditions',$groupMember->customers()->get());
                $rs->put('customer_conditions',$conditionsCustomer);
                $rs->put('goal_percent_customer',$goalPercentCustomerTotal);
                $rs->put('total_percent_profit', $totlaPrecentProft);
                $rs->put('total_percent_profit_max_70', $totlaPrecentProftMax70);

            }
            return $rs;
        }
        return $this->_result(false, 'Không thể tạo kpi');
    }
    private function getCustomer($groupMember,$filter,$customerTager){
        $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_1;
        $rs =   $this->fp->getListbyUsers($filter);
        //get account id betten start day and end day
        $distinctAccount = $rs->unique('account_id')->pluck('account_id')->toArray();
        //get all account id
        $rsAccount = $this->fp->getIDsUsersNotExistInCurrentUsers(['startDay'=> $filter['startDay'],'users' =>$filter['users']]);
        $distinctAccountAll = $rsAccount->unique()->values()->all();
        $accoutResult = array_diff($distinctAccount, $distinctAccountAll);
        $newAccount = count($accoutResult);
        //check new account greate than account target

        //get conditions customer
        $conditionsCustomer = $groupMember->customers()->get();
        $conditionsCustomer = $conditionsCustomer->filter(function ($item) use ($typeKpi) {
            return $item['type'] === $typeKpi .'months';
        });

        //compare values to get percents
        $goalPercentCustomer = $conditionsCustomer->firstWhere('number', $newAccount)
            ?: $conditionsCustomer
                ->where('number', '<', count($accoutResult))
                ->sortByDesc('number') // Sắp xếp theo giảm dần để lấy giá trị lớn nhất
                ->first();

        $totalMargin = $rs->sum('margin');
       // if($customerTager> $newAccount)  $goalPercentCustomer = 0;
        return [
            'totalMargin' => $totalMargin,
            'goalPercentCustomer' =>$goalPercentCustomer,
            'accoutResult' => $accoutResult,
            'conditionsCustomer' =>$conditionsCustomer,
            'list' => $rs


        ];
    }

    private function getDebts($groupMember,$filter){
        $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_1;
        $conditionsDebts = $groupMember->debts()->get();
        $conditionsDebtsTypeList = $conditionsDebts->filter(function ($item) use ($typeKpi){
            return $item['type'] === $typeKpi .'months';
        });
        //get all days
        $rsDebuts = $this->debts->getListKpi($filter);
        $arrPercentDebuts= [];
        $totalPercentDebuts = 0;
        //compare day to conditions
        foreach ($rsDebuts as $debt) {
            $matchedCondition = null;
            //if day debt less than 0 and greater than debt day allows
            if($debt->diff<0 || $debt->diff > $debt->day_debuts_allows ) {
                $percent = 0;
            }else{
                // loop to find day correct conditions
                foreach ($conditionsDebtsTypeList as $condition) {
                    if ($debt->diff >= $condition->min_days && $debt->diff <= $condition->max_days) {
                        $matchedCondition = $condition;
                        break;
                    }
                }
                //if not found will get max day or min day to percent
                if (!$matchedCondition) {
                    if ($debt->diff >= $conditionsDebtsTypeList->max('max_days')) {
                        $percent = $conditionsDebtsTypeList->max('percentage');
                    } elseif ($debt->diff < $conditionsDebtsTypeList->min('min_days')) {
                        $percent =  0;
                    } else {
                        $percent = 0;
                    }

                } else {
                    $percent = $matchedCondition->percentage;
                }
            }

            $arrPercentDebuts[]=$percent;
            $totalDebuts = count($arrPercentDebuts);

            if($totalDebuts>0){
                $totalPercentDebuts = array_sum($arrPercentDebuts)/$totalDebuts;
            }


        }
        return  [
            "totalPercentDebuts" => $totalPercentDebuts,
            "rsDebuts" => $rsDebuts,
            "debuts_percent"=> $arrPercentDebuts,
            "conditionsDebtsTypeList" => $conditionsDebtsTypeList
         ];

    }

    private function getKpiSettings($totalGoals,$filter){
        $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_1;
        $conditionsSettings = $this->setting->getList();
        $conditionsSettingsType = $conditionsSettings->filter(function ($item) use ($typeKpi){
            return $item['type'] === $typeKpi.'months';
        });

        $matchedCondition = null;
        $record = 0;
        $minPercent = $conditionsSettingsType->min('min_percentage');
        $maxPercent = $conditionsSettingsType->max('max_percentage');
        //if day debt less than 0 and greater than debt day allows
        if($totalGoals == 0 ) {
            $percent = 0;
        }else{
            // loop to find day correct conditions
            foreach ($conditionsSettingsType as $condition) {
                if ($totalGoals >= $condition->min_percentage && $totalGoals <= $condition->max_percentage) {
                    $matchedCondition = $condition;
                    $record =  $condition;
                    break;
                }
            }

            //if not found will get max day or min day to percent

            if (!$matchedCondition) {
                if ($totalGoals >= $maxPercent) {
                    $record = $conditionsSettings->sortByDesc('percentage')->values()->first();

                    $percent = $maxPercent;
                } elseif ($totalGoals <$minPercent) {
                    $percent = 0;
                    $record =  0;
                } else {
                    $percent = 0;
                    $record =  0;
                }

            } else {
                $percent = $matchedCondition->percentage;
            }

        }
        return [
            'resultKpiGoals' => ($totalGoals == 0.0 || $totalGoals < $minPercent  ) ? 0: 1,
            'percent' =>$percent,
            'record' => $record
        ];
    }


}
