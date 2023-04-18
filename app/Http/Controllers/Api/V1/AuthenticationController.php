<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ValidateLoginRequest;
use App\Services\AuthenticationService;
class AuthenticationController extends Controller
{
    protected $service;

    public function __construct(AuthenticationService $authService) {
        $this->service = $authService;
    }

    public function store(RegisterUserRequest $request)
    {
        return $this->service->store($request);
    }

    public function login(ValidateLoginRequest $request)
    {
        return $this->service->login($request->validated());
    }

}
