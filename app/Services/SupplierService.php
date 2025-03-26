<?php

namespace App\Services;


use App\Interfaces\SupplierInterface;
use Carbon\Carbon;


class SupplierService extends BaseService
{
    protected $supplier;

    function __construct(SupplierInterface $supplier)
    {
        $this->supplier = $supplier;
    }

    public function getList()
    {
        return $this->supplier->getList();
    }

    public function getListPaginate($perPage = 20,$filter)
    {
        return $this->supplier->getListPaginate($perPage,$filter);
    }

    public function createNew($data)
    {
        $account = $this->supplier->create($data);
        if (!$account) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $account = $this->supplier->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $account);
    }

    public function update($id, $data)
    {
        $rs = $this->supplier->getByID($id);
        if (!$rs) {
            return $this->_result(false, 'Not found!');
        }
        //upate history debts
        $currentYear = Carbon::now()->year;
        $oldDebts = $rs->debts;

        if ($oldDebts != $data['debts']) {

            if (empty($rs->increase_debt_times) || $rs->increase_debt_times != $currentYear) {
                $history = $rs->history ? $rs->history: [];
                $data['increase_debt_times'] = $currentYear;
                $history[] = [
                    'year' => $currentYear,
                    'current_value' => $data['debts'],
                    'old_value' => $oldDebts,
                ];

                $data['history'] = $history;
            }

        }
        //update data
        $result = $this->supplier->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->supplier->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
