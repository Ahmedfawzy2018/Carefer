<?php

namespace Tests\Unit;

use App\Models\Reservation;
use App\Enums\{BusTypeEnum, ReservationStatusEnum, SeatStatusEnum};
use App\Http\Requests\ReserveBusRequest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use WithFaker;

    protected $user;


    protected function setUp(): void
    {
        parent::setUp();

        $this->user  = newUser();
    }

    public function test_should_user_can_login_successfully()
    {
        $this->postJson(route("user.login"), [
            'email' => $this->user->email,
            'password' => 'password',
        ])->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => ['token']]);
    }

    public function test_should_get_buses_successfully()
    {
        seedBus();
        $this->actingAs($this->user)
            ->getJson(route("buses.index"))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => ['records']]);
    }

    public function test_should_get_routes_successfully()
    {
        seedRoute();
        $this->actingAs($this->user)
            ->getJson(route("routes.index"))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => ['records']]);
    }

    public function test_should_get_reservations_successfully()
    {
        $this->actingAs($this->user)
            ->getJson(route("reservations.index"))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => ['records']]);
    }

    public function test_should_create_reservation_successfully()
    {
        $bus = newBus([
            'type' => BusTypeEnum::LONG_ROUTE,
        ]);

        $route = newRoute([
            'pickup_station' => 'Cairo',
            'destination_station' => 'Aswan',
            'distance' => 150,
        ]);


        $response = $this->actingAs($this->user)
            ->postJson(route("reservations.store"), [
                'bus_id' => $bus->id,
                'route_id' => $route->id,
                'seats' => $bus->seats()->take(6)->where('status', SeatStatusEnum::AVAILABLE)->pluck('id')->toArray(),
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => []]);

        $this->assertEquals($response->getData()->data->bus->id, $bus->id);
        $this->assertEquals($response->getData()->data->route->id, $route->id);
    }

    public function test_should_read_reservation_successfully()
    {
        $bus = newBus([
            'type' => BusTypeEnum::LONG_ROUTE,
        ]);

        $route = newRoute([
            'pickup_station' => 'Cairo',
            'destination_station' => 'Aswan',
            'distance' => 150,
        ]);


        $result = $this->actingAs($this->user)
            ->postJson(route("reservations.store"), [
                'bus_id' => $bus->id,
                'route_id' => $route->id,
                'seats' => $bus->seats()->take(6)->where('status', SeatStatusEnum::AVAILABLE)->pluck('id')->toArray(),
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => []]);

        $response = $this->actingAs($this->user)
            ->getJson(route("reservations.show", [
                'reservation' => $result->getData()->data->id
            ]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => []]);

        $this->assertEquals($response->getData()->data->bus->id, $bus->id);
        $this->assertEquals($response->getData()->data->route->id, $route->id);
    }

    public function test_should_update_reservation_successfully()
    {
        $bus = newBus([
            'type' => BusTypeEnum::LONG_ROUTE,
        ]);

        $route = newRoute([
            'pickup_station' => 'Cairo',
            'destination_station' => 'Aswan',
            'distance' => 150,
        ]);

        $result = $this->actingAs($this->user)
            ->postJson(route("reservations.store"), [
                'bus_id' => $bus->id,
                'route_id' => $route->id,
                'seats' => $bus->seats()->take(6)->where('status', SeatStatusEnum::AVAILABLE)->pluck('id')->toArray(),
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => []]);

        $this->actingAs($this->user)
            ->putJson(route("reservations.update",
                [
                    'reservation' => $result->getData()->data->id
                ]),[

                'status' => Reservation::CANCELLED,
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => []]);

        $response = $this->actingAs($this->user)
            ->getJson(route("reservations.show", [
                'reservation' => $result->getData()->data->id
            ]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => []]);

        $this->assertEquals($response->getData()->data->status, ReservationStatusEnum::CANCELLED);
        $this->assertNotEmpty($response->getData()->data->cancelled_date);
    }

    public function test_should_delete_reservation_successfully()
    {
        $bus = newBus([
            'type' => BusTypeEnum::LONG_ROUTE,
        ]);

        $route = newRoute([
            'pickup_station' => 'Cairo',
            'destination_station' => 'Aswan',
            'distance' => 150,
        ]);

        $result = $this->actingAs($this->user)
            ->postJson(route("reservations.store"), [
                'bus_id' => $bus->id,
                'route_id' => $route->id,
                'seats' => $bus->seats()->take(6)->where('status', SeatStatusEnum::AVAILABLE)->pluck('id')->toArray(),
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => []]);

        $this->actingAs($this->user)
            ->deleteJson(route("reservations.destroy",
                [
                    'reservation' => $result->getData()->data->id
                ]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data" => []]);

        $this->actingAs($this->user)
            ->getJson(route("reservations.show", [
                'reservation' => $result->getData()->data->id
            ]))
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_should_not_able_to_reserve_shortBus_with_longRoute_reservation()
    {
        $bus = newBus([
            'type' => BusTypeEnum::SHORT_ROUTE,
        ]);

        $route = newRoute([
            'pickup_station' => 'Cairo',
            'destination_station' => 'Aswan',
            'distance' => 150,
        ]);


        $this->actingAs($this->user)
            ->postJson(route("reservations.store"), [
                'bus_id' => $bus->id,
                'route_id' => $route->id,
                'seats' => $bus->seats()->take(6)->where('status', SeatStatusEnum::AVAILABLE)->pluck('id')->toArray(),
            ])
            ->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
