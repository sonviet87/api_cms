<?php
namespace App\Interfaces;

interface KpiSettingsInterface {
    /**
     * Get all
     * @param interger $perPage
     * @return mixed
     */
    public function getList();

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
