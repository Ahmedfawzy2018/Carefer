<?php

namespace App\Services;
use App\Actions\RegisterAction;
use App\Http\Requests\RegisterUserRequest;
use App\Traits\ApiResponseTrait;

class AuthenticationService
{
    use ApiResponseTrait;

    public function store(RegisterUserRequest $request)
    {
        try {
            (new RegisterAction($request))->execute() ;
            return $this->respond([
                    'status' => true,
                    'message' => 'Created Successfully'
                ]);
        } catch (\Exception $e) {
            $this->respondBadRequest($e->getMessage());
        }
    }
    public function login($credentials)
    {
        try {
            if (!$token = auth('users')->attempt($credentials)) {
                return response()->json(['error' => 'Your Username/Password is Invalid!'], 401);
            }

            return $this->respond(['token' => $token]);

        } catch (\Exception $e) {
            $this->respondBadRequest($e->getMessage());
        }
    }
}
