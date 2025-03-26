<?php
namespace App\Interfaces;

interface KpiInterface {


    /**
     * Get all  with paginate
     * @param minxed $fillter
     * @return mixed
     */
    public function getList($fillter=[]);




}
