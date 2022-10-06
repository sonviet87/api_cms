<?php
namespace App\Interfaces;

interface UserInterface {
    /**
     * Get all
     * @return mixed
     */
    public function getList();

    /**
     * Get a user by email
     * @param string $email
     * @return collection
     */
    public function getUserByEmail($email);

    /**
     * Get a user by phone
     * @param string $phone
     * @return collection
     */
    public function getUserByPhone($phone);

    /**
     * Create new user by "register by email"
     * @param array $data
     * @return collection
     */
    public function createNewUserByEmail($data);
    /**
     * Get all user with paginate
     * @param interger $perPage
     * @return mixed
     */
    public function getListPaginate($perPage = 20,$filter = []);

    /**
     * Create new user
     * @param array $data
     * @return mixed
     */
    public function createNewUser($data);

    /**
     * Get a user by ID
     * @param interger $id
     * @return mixed
     */
    public function getUserByID($id);

    /**
     * Update a user by ID
     * @param interger $id
     * @return mixed
     */
    public function updateUserByID($id, $data);

    /**
     * Delete a list of users by an array of user id
     * @param array $ids
     * @return mixed
     */
    public function destroyUsersByIDs($ids);

    public function getBySimilarPhone($phone);
}
