<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Services\User\UserService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
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
     * @return Application|Factory|View
     */
    public function show(): View
    {
        return view('auth.register');
    }

    /**
     * @param StoreUserRequest $request
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request): RedirectResponse
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
