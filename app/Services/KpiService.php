<?php

namespace App\Services;



use App\Constants\RolePermissionConst;
use App\Interfaces\DebtInterface;
use App\Interfaces\FPInterface;
use App\Interfaces\KpiInterface;
use App\Interfaces\KpiMemberGroupsInterface;
use App\Interfaces\ReportInterface;
use Illuminate\Support\Facades\Auth;


class KpiService extends BaseService
{

    protected $fp;
    protected $groupMember;
    protected $debts;
    function __construct(FPInterface $fp,KpiMemberGroupsInterface $groupMember,DebtInterface $debts)
    {
        $this->groupMember = $groupMember;
        $this->fp = $fp;
        $this->debts = $debts;

    }

    public function getList($filter)
    {
        $profitTargetMonths = 0;
        $profitTargetMonths3 = 0;
        $profitTargetMonths12 = 0;
        if (isset($filter['startDay']) && $filter['startDay'] != '' && isset($filter['endDay']) && $filter['endDay'] != '') {
            $startDay = $filter['startDay'];
            $endDay = $filter['endDay'];
        }
        if(isset($filter['groupMember']) && $filter['groupMember'] !=''){
            $groupMember = $this->groupMember->getByID($filter['groupMember']);
            if($groupMember!=null){
                $profitTargetMonths = $groupMember->profit_months;
                $profitTargetMonths3 = $groupMember->profit_3_months;
                $profitTargetMonths12 = $groupMember->profit_12_months;
                $customerTargetMonths = $groupMember->customer_months;
                $customerTargetMonths3 = $groupMember->	customer_3_months;
                $customerTargetMonths12 = $groupMember->customer_12_months;
                $debtsTargetMonths = $groupMember->debts_months;
                $debtsTargetMonths3 = $groupMember->debts_3_months;
                $debtsTargetMonths12 = $groupMember->debts_12_months;
                $users = $groupMember->users()->get()->pluck('id')->all();
                if(count($users)>0) $filter['users'] = $users;

                /////////////////
                /// get Accounts
                /// ////////////
                $rs =   $this->fp->getListbyUsers($filter);
                //get account id betten start day and end day
                $distinctAccount = $rs->unique('account_id')->pluck('account_id')->toArray();
                //get all account id
                $rsAccount = $this->fp->getIDsUsersNotExistInCurrentUsers(['startDay'=> $filter['startDay'],'users' =>$filter['users']]);
                $distinctAccountAll = $rsAccount->unique()->values()->all();
                $accoutResult = array_diff($distinctAccount, $distinctAccountAll);



                //get conditions customer
                $conditionsCustomer = $groupMember->customers()->get();
                $conditionsCustomerMonths = $conditionsCustomer->filter(function ($item) {
                    return $item['type'] === 'months';
                });

                //compare values to get percents
                $goalPercentCustomer = $conditionsCustomerMonths->firstWhere('number', count($accoutResult))
                    ?: $conditionsCustomerMonths
                        ->where('number', '<', count($accoutResult))
                        ->sortByDesc('number') // Sắp xếp theo giảm dần để lấy giá trị lớn nhất
                        ->first();


                $conditionsCustomer3Months = $conditionsCustomer->filter(function ($item) {
                    return $item['type'] === '3months';
                });
                $conditionsCustomer12Months = $conditionsCustomer->filter(function ($item) {
                    return $item['type'] === '3months';
                });

                $totalMargin = $rs->sum('margin');
                //get total percent profit
                $totlaPrecentProftMonths = ($totalMargin/$profitTargetMonths)*100;
                //get total percent profit max 70%
                $totlaPrecentProftMax70Months = (($totalMargin*0.7)/$profitTargetMonths)*100;
                ////////////////////////
                /// Debts
                /// ///////////////////
                $conditionsDebts = $groupMember->debts()->get();
                $conditionsDebtsMonths = $conditionsDebts->filter(function ($item) {
                    return $item['type'] === 'months';
                });

                $rsDebuts = $this->debts->getListKpi($filter);
                $arrPercentDebuts= [];
                foreach ($rsDebuts as $debt) {
                    $matchedCondition = null;

                    foreach ($conditionsDebtsMonths as $condition) {
                        if ($debt->diff >= $condition->min_day && $debt->diff <= $condition->max_day) {
                            $matchedCondition = $condition;
                            break;
                        }
                    }

                    if (!$matchedCondition) {
                        // Nếu không có điều kiện phù hợp, xử lý theo yêu cầu của bạn
                        if ($debt->diff >= $conditionsDebtsMonths->max('max_day')) {
                            $percent = $conditionsDebtsMonths->max('percent');
                        } elseif ($debt->diff <= $conditionsDebtsMonths->min('min_day')) {
                            $percent = $conditionsDebtsMonths->min('percent');
                        } else {
                            // Xử lý theo trường hợp không có điều kiện nào khớp
                            // Hoặc bạn có thể đặt giá trị mặc định khác tùy thuộc vào yêu cầu của bạn
                        }
                    } else {
                        $percent = $matchedCondition->percent;
                    }
                    $arrPercentDebuts[]=$percent;
                    // Sử dụng $percent theo nhu cầu của bạn (ví dụ: lưu vào một mảng, hiển thị, v.v.).
                }

                $rs->put('total_profit', $totalMargin);
                $rs->put('debuts', $rsDebuts);
                $rs->put('debuts_percent', $arrPercentDebuts);
                $rs->put('conditionsDebtsMonths', $conditionsDebtsMonths);

                $rs->put('target_profit_months', $profitTargetMonths);
                $rs->put('target_profit_3_months', $profitTargetMonths3);
                $rs->put('target_profit_12_months', $profitTargetMonths12);
                $rs->put('target_customer_months', $customerTargetMonths);
                $rs->put('target_customer_3_months', $customerTargetMonths3);
                $rs->put('target_customer_12_months', $customerTargetMonths12);
                $rs->put('target_debts_months', $debtsTargetMonths);
                $rs->put('target_debts_3_months', $debtsTargetMonths3);
                $rs->put('target_debts_12_months', $debtsTargetMonths12);
               // $rs->put('account_id', $distinctAccount);
               // $rs->put('account_id_test',$distinctAccountAll);
                $rs->put('account_new',$accoutResult);
                $rs->put('total_account_new',count($accoutResult));
               // $rs->put('customer_conditions',$groupMember->customers()->get());
                $rs->put('customer_conditions_months',$conditionsCustomerMonths);
                $rs->put('goal_percent_customer',$goalPercentCustomer? $goalPercentCustomer->percentage : 0);
                $rs->put('total_percent_profit_months', $totlaPrecentProftMonths);
                $rs->put('total_percent_profit_max_70_months', $totlaPrecentProftMax70Months);

            }
            return $rs;
        }
        return $this->_result(false, 'Không thể tạo kpi');
    }



}
