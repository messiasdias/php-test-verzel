<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $vehicles;

    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->vehicles = [
            [
                "name" => "Veículo 1",
                "description" => "Veículo teste 1",
                "active" => true,
                "brand" => "Marca",
                "model" => "Modelo 1",
                "price" => 18956.99,
                "image" => UploadedFile::fake()->create(""),
            ],
            [
                "name" => "Veículo 2",
                "description" => "Veículo teste 2",
                "active" => true,
                "brand" => "Marca",
                "model" => "Modelo 2",
                "price" => 87956.89,
                "image" => UploadedFile::fake()->create(""),
            ],
            [
                "name" => "Veículo 3",
                "description" => "Veículo teste 3",
                "active" => false,
                "brand" => "Marca",
                "model" => "Modelo 3",
                "price" => 50056.97,
                "image" => UploadedFile::fake()->create(""),
            ]
        ];
    }

    /**
     * Store a vehicle
     * @test
     * @return void
     */
    public function check_store_vehicle()
    {
        $this->withExceptionHandling();

        //Error dont logged
        $response = $this->post("/api/vehicles", $this->vehicles[0]);
        $response->assertStatus(403);

        $this->userLogin();

        //Error on add
        $response = $this->post("/api/vehicles", array_merge($this->vehicles[0], ["name" => null]), $this->getRequestHeaders());
        $response->assertStatus(302);

        //Sucess on add
        $response = $this->post("/api/vehicles", $this->vehicles[0], $this->getRequestHeaders());
        $response->assertStatus(200);
        $newVehicleId = $response->decodeResponseJson()["vehicle"]["id"];
        $this->assertEquals($this->vehicles[0]["name"], $response->decodeResponseJson()["vehicle"]["name"]);

        //Error on add, alread exists
        $response = $this->post("/api/vehicles", array_merge(["id" => 2], $this->vehicles[0]), $this->getRequestHeaders());
        $response->assertStatus(404);

        //Sucess update if alread exists
        $newVehicleName = "Veículo 4";
        $response = $this->post("/api/vehicles", array_merge($this->vehicles[0], [
            "id" => $newVehicleId,
            "name" => $newVehicleName,
            "description" => "Veículo teste 4",
        ]), $this->getRequestHeaders());
        $response->assertStatus(200);
        $this->assertEquals($newVehicleName, $response->decodeResponseJson()["vehicle"]["name"]);
    }



    /**
     * Delete a vehicle
     * @test
     * @depends check_store_vehicle
     * @return void
     */
    public function check_delete_vehicle()
    {
        //Error on delete
        $response = $this->delete("/api/vehicles", ["id" => 5]  , $this->getRequestHeaders());
        $response->assertStatus(403);

        //login
        $this->userLogin();

        //Sucess add
        $response = $this->post("/api/vehicles", $this->vehicles[0], $this->getRequestHeaders());
        $response->assertStatus(200);
        $newVehicleId = $response->decodeResponseJson()["vehicle"]["id"];

        //Sucess on delete
        $response = $this->delete("/api/vehicles",  ["id" => $newVehicleId] , $this->getRequestHeaders());
        $response->assertStatus(200);

        //Error on delete
        $response = $this->delete("/api/vehicles",  ["id" => $newVehicleId] , $this->getRequestHeaders());
        $response->assertStatus(404);
    }



    /**
     * Get one vehicle
     * @test
     * @depends check_store_vehicle
     * @return void
     */
    public function check_get_vehicle()
    {
        //Error on get
        $response = $this->get("/api/vehicles/5");
        $response->assertStatus(403);

        //login
        $this->userLogin();

        //Sucess add
        $response = $this->post("/api/vehicles", $this->vehicles[0], $this->getRequestHeaders());
        $response->assertStatus(200);
        $newVehicleId = $response->decodeResponseJson()["vehicle"]["id"];

        //Sucess on get
        $response = $this->get("/api/vehicles/{$newVehicleId}",  $this->getRequestHeaders());
        $response->assertStatus(200);
        $this->assertEquals($newVehicleId, $response->decodeResponseJson()["vehicle"]["id"]);
        $this->assertEquals($this->vehicles[0]["name"], $response->decodeResponseJson()["vehicle"]["name"]);

        //Error on get
        $response = $this->get("/api/vehicles/5", $this->getRequestHeaders());
        $response->assertStatus(404);
    }


    /**
     * Get one vehicle
     * @test
     * @depends check_store_vehicle
     * @return void
     */
    public function check_get_vehicles()
    {
        //Error
        $response = $this->get("/api/vehicles");
        $response->assertStatus(200);

        //login
        $this->userLogin();

        //get vehicles
        $response = $this->get("/api/vehicles", $this->getRequestHeaders());
        $this->assertEquals(0, count($response->decodeResponseJson()["data"]));

        //Sucess
        foreach ($this->vehicles as $v => $vehicle) {
            $response = $this->post("/api/vehicles", [
                "name" => $vehicle["name"],
                "description" => $vehicle["description"],
                "brand" => $vehicle["brand"],
                "model" => $vehicle["model"],
                "price" => $vehicle["price"],
                "image" => $vehicle["image"],
                "active" => $vehicle["active"],
            ], $this->getRequestHeaders());

            $this->assertEquals($this->vehicles[$v]["name"], $response->decodeResponseJson()["vehicle"]["name"]);
        }

        //get vehicles
        $response = $this->get("/api/vehicles?page=1", $this->getRequestHeaders());
        $this->assertEquals(1, $response->decodeResponseJson()["current_page"]);
        $this->assertEquals(3, count($response->decodeResponseJson()["data"]));

        $response = $this->get("/api/vehicles?page=2", $this->getRequestHeaders());
        $this->assertEquals(2, $response->decodeResponseJson()["current_page"]);
        $this->assertEquals(0, count($response->decodeResponseJson()["data"]));
        $this->assertEquals(3, $response->decodeResponseJson()["total"]);
    }


    /**
     * Search vehicles
     * @test
     * @depends check_get_vehicle
     * @return void
     */
    public function check_search_vehicles()
    {
        //Error
        $response = $this->get("/api/vehicles/search");
        $response->assertStatus(403);

        //login
        $this->userLogin();

        //get vehicles
        $response = $this->get("/api/vehicles/search?search=teste", $this->getRequestHeaders());
        $this->assertEquals(0, count($response->decodeResponseJson()["data"]));

        //Sucess
        foreach ($this->vehicles as $v => $vehicle) {
            $response = $this->post("/api/vehicles", [
                "name" => $vehicle["name"],
                "description" => $vehicle["description"],
                "brand" => $vehicle["brand"],
                "model" => $vehicle["model"],
                "price" => $vehicle["price"],
                "image" => $vehicle["image"],
                "active" => $vehicle["active"],
            ], $this->getRequestHeaders());

            $this->assertEquals($this->vehicles[$v]["name"], $response->decodeResponseJson()["vehicle"]["name"]);
        }

        //get vehicles
        $v = 'Veículo';
        $response = $this->get("/api/vehicles/search?search=teste",$this->getRequestHeaders());
        $this->assertEquals(1, $response->decodeResponseJson()["current_page"]);
        $this->assertEquals(3, count($response->decodeResponseJson()["data"]));
    }
}
