<?php

namespace App\Actions;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterAction
{
    protected $request;

    public function __construct(RegisterUserRequest $request)
    {
        $this->request = $request->only('name', 'email', 'password', 'age') ;
    }

    public function execute()
    {
        $this->request['password'] = Hash::make($this->request['password']);
        return User::create($this->request);
    }
}
