<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionCollection;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends RestfulController
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        parent::__construct();
        $this->permissionService = $permissionService;
        //$this->middleware(['role:admin','permission:account-list|account-create|account-edit|account-delete']);
    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $permissions = $this->permissionService->getList();
            return new PermissionCollection(($permissions));
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

}
