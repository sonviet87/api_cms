<?php
namespace App\Interfaces;

interface KpiSuppliernterface {


    /**
     * Get all  with paginate
     * @param minxed $fillter
     * @return mixed
     */
    public function getList($fillter=[]);




}
