<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends RestfulController
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        parent::__construct();
        $this->accountService = $accountService;

    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input("per_page", 20);
            $product = $this->accountService->getListPaginate($perPage);
            $product->appends($request->except(['page', '_token']));
            $paginator = $this->getPaginator($product);
            $pagingArr = $product->toArray();
            return $this->_response([
                'pagination' => $paginator,
                'account' => $pagingArr['data']
            ]);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
}
