<?php
namespace App\Interfaces;

interface KpiTechnicalInterface {


    /**
     * Get all  with paginate
     * @param minxed $fillter
     * @return mixed
     */
    public function getList($fillter=[]);




}
