<?php
namespace App\Interfaces;

interface TechnicalProjectInterface {
    /**
     * Get all
     * @param interger $perPage
     * @return mixed
     */
    public function getList($perPage,$filter);/**
     * Get all
     * @return mixed
     */
    public function getLowestPointByUsers($filter);

    /**
     * Create new
     * @param array $data
     * @return mixed
     */
    public function create($data);

    /**
     * Get by ID
     * @param interger $id
     * @return mixed
     */
    public function getByID($id);

    /**
     * Update  by ID
     * @param interger $id
     * @return mixed
     */
    public function update($id, $data);

    /**
     * Delete  by an array of id
     * @param array $ids
     * @return mixed
     */
    public function destroy($ids);


}
