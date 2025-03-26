<?php
namespace App\Interfaces;

interface FPDetailInterface {



    /**
     * Create new
     * @param array $data
     * @return mixed
     */
    public function create($data);

    /**
     * detete
     * @param array $ids
     * @return mixed
     */
    public function destroy($ids);
    /**
     * get ids
     * @param array $ids
     * @return mixed
     */
    public function getIDS($id);

}
