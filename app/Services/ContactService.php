<?php

namespace App\Services;

use App\Interfaces\ContactInterface;

class ContactService extends BaseService
{
    protected $contact;

    function __construct(ContactInterface $contact)
    {
        $this->contact = $contact;
    }

    public function getList()
    {
        return $this->contact->getList();
    }

    public function getListPaginate($perPage = 20)
    {
        return $this->contact->getListPaginate($perPage);
    }



    public function createNew($data)
    {
        $account = $this->contact->create($data);
        if (!$account) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $account = $this->contact->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $account);
    }

    public function update($id, $data)
    {
        $account = $this->contact->getByID($id);
        if (!$account) {
            return $this->_result(false, 'Not found!');
        }


        $result = $this->contact->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->contact->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
