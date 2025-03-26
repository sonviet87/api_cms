<?php
namespace App\Interfaces;

interface DashboardInterface {


    /**
     * Get all  with paginate
     * @param minxed $fillter
     * @return mixed
     */
    public function getList($fillter=[]);




}
