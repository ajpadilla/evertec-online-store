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
        $message = "{$user->name} does not have an associated order ";
        parent::__construct($message);
    }
}
