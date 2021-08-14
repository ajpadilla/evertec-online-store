<?php


namespace App\Http\Controllers\Exceptions;
use App\Models\User;
use Exception;

class OrderAssociatedWithoutUserException extends Exception
{
    /**
     * OrderAssociatedWithoutUserException constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $message = "There is no order associated with the user {$user->name}";
        parent::__construct($message);
    }
}
