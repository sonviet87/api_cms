<?php
namespace App\Interfaces;

interface ReportDebtFPInterface {


    /**
     * Get all  with paginate
     * @param interger $perPage
     * @return mixed
     */
    public function getListPaginate($perPage = 20);




}