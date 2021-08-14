<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Services\User\UserService;
use Exception;
use Illuminate\Database\QueryException;
use PDOException;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{

    /** @var UserService */
    private $userService;

    /**
     * RegisterController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $this->userService->registerNewUser($request->all());

            DB::commit();
        } catch (QueryException | PDOException $e) {
            DB::rollBack();
            logger($e->getMessage());
            logger($e->getTraceAsString());

            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->back()->with('alert_success', 'User Register Successful. You can now login');
    }
}
