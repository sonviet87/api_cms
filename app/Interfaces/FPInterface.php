<?php
namespace App\Interfaces;

interface FPInterface {


    /**
     * Get all  with paginate
     * @param interger $perPage
     * @return mixed
     */
    public function getListPaginate($perPage = 20);

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
     * Update a user by ID
     * @param interger $id
     * @return mixed
     */
    public function update($id, $data);

    /**
     * Delete a list of users by an array of user id
     * @param array $ids
     * @return mixed
     */
    public function destroy($ids);


}
