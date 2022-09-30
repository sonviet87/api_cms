<?php

namespace App\Services;

use App\Interfaces\AccountInterface;

class AccountService extends BaseService
{
    protected $account;

    function __construct(AccountInterface $account)
    {
        $this->account = $account;
    }

    public function getList()
    {
        return $this->account->getList();
    }

    public function getListPaginate($perPage = 20)
    {
        return $this->account->getListPaginate($perPage);
    }

    public function createNewUser($data)
    {
        $account = $this->account->create($data);
        if (!$account) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getAccountByID($id)
    {
        $account = $this->account->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $account);
    }

    public function update($id, $data)
    {
        $account = $this->account->getAccountByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }


        $result = $this->account->updateAccountByID($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyAccountByIDs($ids)
    {
        $check = $this->account->destroyAccountByIDs($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
