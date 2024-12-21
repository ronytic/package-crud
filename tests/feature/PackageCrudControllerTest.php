<?php

namespace Tests\PackageCrud\Feature;

use Tests\TestCase;
use ProcessMaker\Models\User;

class PackageCrudControllerTest extends TestCase
{

    public function testIndex()
    {
        $user = User::factory()->create([
            'is_administrator' => true
        ]);
        $response = $this->actingAs($user)->get(route('package.crud.index'));
        $response->assertStatus(200);
        $response->assertViewIs('package-crud::index');
    }

    public function testIndexNotAdmin()
    {
        $user = User::factory()->create(['is_administrator' => false]);
        $response = $this->actingAs($user)->get(route('package.crud.index'));
        $response->assertViewIs('errors.404');
    }
}
