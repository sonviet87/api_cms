<?php

namespace App\Services;

use App\Constants\DebtsConst;
use App\Http\Resources\UserCollection;
use App\Interfaces\AccountInterface;
use App\Interfaces\ChanceInterface;
use App\Interfaces\DebtInterface;
use App\Interfaces\DebtSupplierInterface;
use App\Interfaces\FPInterface;
use App\Interfaces\KpiMemberGroupsInterface;
use App\Interfaces\KpiSettingsInterface;
use Carbon\Carbon;

class DashboardService extends BaseService
{

    protected $fp;
    protected $debt;
    protected $chance;
    protected $debtSupplier;
    protected $account;


    function __construct(FPInterface $fp,DebtInterface $debt,ChanceInterface $chance,DebtSupplierInterface $debtSupplier,AccountInterface $account)
    {

        $this->fp = $fp;
        $this->debt = $debt;
        $this->debtSupplier = $debtSupplier;
        $this->chance = $chance;
        $this->account = $account;

    }

    public function getList($filter=[])
    {
        $unpaidDebt = $this->getDebt($filter);
        $unpaidSupplier = $this->getDebtSupplier($filter);
        $chance = $this->getChance($filter);
        $fpKpi= $this->getKpiFP($filter);

       ['total_accounts' => $totalAccount,'total_new_accounts'=> $totalNewAccount,'total_old_accounts' => $totalOldAccount ] = $this->account->getAccountDashboard();

        return [
            'debts' => [
                'unpaid_debts' => count($unpaidDebt),
                'unpaid_supplier' => count($unpaidSupplier)
            ],
            'chances' => $chance,
            'fp' => $fpKpi,
            'customer' => [
                'total' => $totalAccount,
                'new_account' => $totalNewAccount,
                'old_account' => $totalOldAccount
            ]
        ];
    }

    public function getDebt($filter){
        return $this->debt->getUnPaidCustomerDebts($filter);
    }
    public function getDebtSupplier($filter){
        return $this->debt->getUnPaidCustomerDebts($filter);
    }

    public function getChance($filter){
        return $this->chance->getChanceStatsByDateRange($filter);
    }

    public function getKpiFP($filter){
        return $this->fp->getKpiFP($filter);
    }

}
