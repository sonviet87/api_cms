<?php

namespace App\Services;


use App\Interfaces\TechnicalReviewInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;


class TechnicalReviewService extends BaseService
{

    protected $technicalReview;

    function __construct(TechnicalReviewInterface $technicalReview)
    {
        $this->technicalReview = $technicalReview;
    }

    public function getList($perPage,$filter)
    {

        return $this->technicalReview->getList($perPage,$filter);
    }

    public function create($data)
    {
        if(isset($data["start_date"])) $data["start_date"] =  Carbon::parse($data["start_date"])->toDateTimeString();
        if(isset($data["data"])){
            $totalPoints = array_reduce($data['data'], function ($carry, $item) {
                return $carry + (int)$item['points'];
            }, 0);

            $count = count(array_filter($data['data'], function ($item) {
                return isset($item['points']) && is_numeric($item['points']);
            }));

            $averagePoints = $count > 0 ? round($totalPoints / $count,1) : 0;

            $data["points"] =  $averagePoints;
            $data["data"] =  json_encode($data['data']);


        }

        $rs = $this->technicalReview->create($data);
        if (!$rs) {
            return $this->_result(false, 'Created failed');
        }
        return $this->_result(true, 'Created successfully');
    }

    public function getByID($id)
    {
        $data = $this->technicalReview->getByID($id);
        if (!$data) {
            return $this->_result(false, 'Not found!');
        }
        return $this->_result(true, '', $data);
    }

    public function update($id, $data)
    {
        if(isset($data["start_date"])) $data["start_date"] =  Carbon::parse($data["start_date"])->toDateTimeString();
        if(isset($data["data"])){
            $totalPoints = array_reduce($data['data'], function ($carry, $item) {
                return $carry + (int)$item['points'];
            }, 0);

            $count = count(array_filter($data['data'], function ($item) {
                return isset($item['points']) && is_numeric($item['points']);
            }));

            $averagePoints = $count > 0 ? round($totalPoints / $count,1) : 0;

            $data["points"] =  $averagePoints;
            $data["data"] =  json_encode($data['data']);


        }
        $rs = $this->technicalReview->getByID($id);
        if (!$rs) {
            return $this->_result(false, 'Not found!');
        }

        $result = $this->technicalReview->update($id, $data);
        if (!$result) {
            return $this->_result(false, 'Updated failed');
        }
        return $this->_result(true, 'Updated successfully');
    }

    public function destroyByIDs($ids)
    {
        $check = $this->technicalReview->destroy($ids);
        if (!$check) {
            return $this->_result(false, 'Delete failed!');
        }
        return $this->_result(true, 'Delete successfuly');
    }

}
