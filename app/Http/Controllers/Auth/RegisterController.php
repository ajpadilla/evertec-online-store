<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\services\User\UserService;
use Exception;
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
        } catch (Exception $exception) {
            DB::rollBack();

            return redirect()->back()->withErrors($exception->getMessage());
        }

        return redirect()->back()->with('alert_success', 'User Register Successful. You can now login');
    }
}
