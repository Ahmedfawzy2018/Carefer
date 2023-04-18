<?php

namespace Tests\Unit;

use App\Http\Requests\AddMovieRequest;
use App\Http\Requests\AddWatchListRequest;
use App\Http\Requests\RateMovieRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Movies;
use App\Models\User;
use App\Services\AuthenticationService;
use App\Services\MovieServie;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use WithFaker;
    public function test_should_register_new_user_successfully()
    {
        $request = new RegisterUserRequest([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'password',
        ]);

        $response = (new AuthenticationService())
            ->store($request);
        $this->assertEquals($response->status(), 200);
    }


}
