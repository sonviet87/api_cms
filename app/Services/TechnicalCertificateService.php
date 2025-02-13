<?php

namespace App\Services;


use App\Interfaces\TechnicalCertificateInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;


class TechnicalCertificateService extends BaseService
{

    protected $technicalCertificate;

    function __construct(TechnicalCertificateInterface $technicalCertificate)
    {
        $this->technicalCertificate = $technicalCertificate;
    }

    public function getList($perPage,$filter)
    {
        return $this->technicalCertificate->getList($perPage,$filter);
    }

    public function create($data)
    {

        if(isset($data["end_date"])) $data["end_date"] =  Carbon::parse($data["end_date"])->toDateTimeString();
        if(isset($data["start_date"])) $data["start_date"] =  Carbon::parse($data["start_date"])->toDateTimeString();
        $rs = $this->technicalCertificate->create($data);

        if (!$rs) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $data = $this->technicalCertificate->getByID($id);
        if (!$data) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $data);
    }

    public function update($id, $data)
    {
        if(isset($data["end_date"])) $data["end_date"] =  Carbon::parse($data["end_date"])->toDateTimeString();
        if(isset($data["start_date"])) $data["start_date"] =  Carbon::parse($data["start_date"])->toDateTimeString();
        $rs = $this->technicalCertificate->getByID($id);
        if (!$rs) {
            return $this->_result(false, 'Not found!');
        }

        $result = $this->technicalCertificate->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->technicalCertificate->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
