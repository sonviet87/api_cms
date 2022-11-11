<?php

namespace App\Services;


use App\Interfaces\FPInterface;

class FPService extends BaseService
{
    protected $fp;

    function __construct(FPInterface $fp)
    {
        $this->fp = $fp;
    }

    public function getList()
    {
        return $this->fp->getList();
    }

    public function getListPaginate($perPage = 20)
    {
        return $this->fp->getListPaginate($perPage);
    }

    public function createNew($data)
    {
        $account = $this->fp->create($data);
        if (!$account) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $account = $this->fp->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $account);
    }

    public function update($id, $data)
    {
        $account = $this->fp->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }


        $result = $this->fp->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->fp->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
