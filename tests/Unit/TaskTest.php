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
use Illuminate\Http\Response;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use WithFaker;

    protected $user;
    public function test_should_user_can_login_successfully()
    {
        $user  = newUser();

        $this->postJson(route("user.login"), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => ['token']]);
    }

    public function test_should_get_buses_successfully()
    {
        $this->user  = newUser();
        seedBus();
        $this->actingAs($this->user)
            ->getJson(route("buses.index"))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => ['records']]);
    }

    public function test_should_get_routes_successfully()
    {
        $this->user  = newUser();
        seedRoute();
        $this->actingAs($this->user)
            ->getJson(route("routes.index"))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => ['records']]);
    }

    public function test_should_get_reservations_successfully()
    {
        $this->user  = newUser();

        $this->actingAs($this->user)
            ->getJson(route("reservations.index"))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => ['records']]);
    }



}
