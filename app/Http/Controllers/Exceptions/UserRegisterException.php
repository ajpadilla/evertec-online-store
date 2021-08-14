<?php


namespace App\Http\Controllers\Exceptions;
use Throwable;

class UserRegisterException extends \Exception
{
    protected $email;

    public function __construct($email)
    {
        $message = "Error when registering the user with the email {$email}";
        parent::__construct($message);
    }
}
