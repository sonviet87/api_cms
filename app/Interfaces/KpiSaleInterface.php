<?php
namespace App\Interfaces;

interface KpiSaleInterface {


    /**
     * Get all  with paginate
     * @param minxed $fillter
     * @return mixed
     */
    public function getList($fillter=[]);




}
