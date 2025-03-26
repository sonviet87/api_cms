<?php
namespace App\Interfaces;

interface KpiSettingStaffManagerInterface {


    /**
     * Get all  with paginate
     * @param interger $perPage
     * @return mixed
     */
    public function getListPaginate($perPage = 20,$filter);

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
     * Get first kpi staff
     * @param interger $id
     * @param interger $kpi_id
     * @param string $type
     * @return mixed
     */
    public function findFirstKpiStaff($user_id,$kpi_id,$type);


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
