<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_proyect_active_see()
    {
        $response = $this->get('/usuarios/activos/proyectos');

        $response->assertStatus(200);
        $response->assertJson(["usuario" => true]);
    }
}
