<?php
namespace App\Interfaces;

interface ReportInterface {


    /**
     * Get all  with paginate
     * @param interger $perPage
     * @return mixed
     */
    public function getListPaginate($perPage = 20);




}
