<?php

namespace Tests\PackageCrud\Feature;

use Tests\TestCase;
use ProcessMaker\Models\User;

class CrudControllerTest extends TestCase
{

    public function setupPackage()
    {
        // Run non registered migration
        $this->skipTeardownPDOException = true;
        $this->artisan('migrate', [
            '--path' => 'vendor/processmaker/package-crud/database/migrations/'
        ])->run();
    }

    public function uninstallPackage()
    {
        // Run non registered migration
        $this->skipTeardownPDOException = true;
        $this->artisan('migrate:rollback', [
            '--path' => 'vendor/processmaker/package-crud/database/migrations/'
        ])->run();
    }

    public function testNonAdminUserCannotAccess()
    {
        // Arrange
        $user = User::factory()->create([
            'is_administrator' => false,
        ]);
        $this->actingAs($user, 'api');

        // Act
        $response = $this->get(route('api.package-crud.crud.index'));

        // Assert status code
        $response->assertStatus(403);

        //Act
        $response = $this->post(route('api.package-crud.crud.store'), [
            'name' => 'Test',
            'description' => 'Test description',
            'code' => 'test',
            'status' => 'active',
        ]);

        // Assert status code
        $response->assertStatus(403);

        //Act
        $response = $this->put(route('api.package-crud.crud.update', 'invalid-uuid'), [
            'name' => 'Test 2',
            'description' => 'Test description 2',
            'code' => 'test 2',
            'status' => 'inactive',
        ]);

        // Assert status code
        $response->assertStatus(403);

        //Act

        $response = $this->delete(route('api.package-crud.crud.destroy', 'invalid-uuid'));

        // Assert status code
        $response->assertStatus(403);
    }


    public function testAdminUserCanAccess()
    {
        // Arrange
        $user = User::factory()->create([
            'is_administrator' => true,
        ]);
        $this->actingAs($user, 'api');

        // Act
        $response = $this->get(route('api.package-crud.crud.index'));

        // Assert status code
        $response->assertStatus(200);
    }

    public function testCreateRecord()
    {
        // Arrange
        $user = User::factory()->create([
            'is_administrator' => true,
        ]);
        $this->actingAs($user, 'api');

        $this->setupPackage();

        $response = $this->post(route('api.package-crud.crud.store'), [
            'name' => 'Test',
            'description' => 'Test description',
            'code' => 'test',
            'status' => 'active',
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'name',
                'description',
                'code',
                'status',
                'created_at',
                'updated_at',
            ]
        ]);
        $response->assertJson([
            'data' => [
                'name' => 'Test',
                'description' => 'Test description',
                'code' => 'test',
                'status' => 'active',
            ]
        ]);

        $response->assertJson([
            'status' => 'success',
            'message' => 'The request was has been processed successfully'
        ]);
    }

    public function testUpdateRecord()
    {
        // Arrange
        $user = User::factory()->create([
            'is_administrator' => true,
        ]);
        $this->actingAs($user, 'api');

        $this->setupPackage();

        $response = $this->post(route('api.package-crud.crud.store'), [
            'name' => 'Test',
            'description' => 'Test description',
            'code' => 'test',
            'status' => 'active',
        ]);

        $uuid = $response->json('data.uuid');

        $response = $this->put(route('api.package-crud.crud.update', $uuid), [
            'name' => 'Test 2',
            'description' => 'Test description 2',
            'code' => 'test 2',
            'status' => 'inactive',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'uuid',
                'name',
                'description',
                'code',
                'status',
                'created_at',
                'updated_at',
            ]
        ]);

        $response->assertJson([
            'data' => [
                'name' => 'Test 2',
                'description' => 'Test description 2',
                'code' => 'test 2',
                'status' => 'inactive',
            ]
        ]);

        $response->assertJson([
            'status' => 'success',
            'message' => 'The request was has been processed successfully'
        ]);
    }

    public function testDeleteRecord()
    {
        // Arrange
        $user = User::factory()->create([
            'is_administrator' => true,
        ]);
        $this->actingAs($user, 'api');

        $this->setupPackage();

        $response = $this->post(route('api.package-crud.crud.store'), [
            'name' => 'Test',
            'description' => 'Test description',
            'code' => 'test',
            'status' => 'active',
        ]);

        $uuid = $response->json('data.uuid');

        $response = $this->delete(route('api.package-crud.crud.destroy', $uuid));

        $response->assertStatus(204);
    }

    public function testRecordNotFound()
    {
        // Arrange
        $user = User::factory()->create([
            'is_administrator' => true,
        ]);
        $this->actingAs($user, 'api');

        $this->setupPackage();

        $response = $this->put(route('api.package-crud.crud.update', 'invalid-uuid'), [
            'name' => 'Test 2',
            'description' => 'Test description 2',
            'code' => 'test 2',
            'status' => 'inactive',
        ]);

        $response->assertStatus(404);
    }

    public function testRecordNotFoundDelete()
    {
        // Arrange
        $user = User::factory()->create([
            'is_administrator' => true,
        ]);
        $this->actingAs($user, 'api');

        $this->setupPackage();

        $response = $this->delete(route('api.package-crud.crud.destroy', 'invalid-uuid'));

        $response->assertStatus(404);
    }

    public function testValidationErrors()
    {
        // Arrange
        $user = User::factory()->create([
            'is_administrator' => true,
        ]);
        $this->actingAs($user, 'api');

        $this->setupPackage();

        $response = $this->post(route('api.package-crud.crud.store'), [
            'name' => '',
            'description' => 'Test description',
            'code' => 'test',
            'status' => 'active',
        ]);

        $response->assertStatus(500);
    }

    public function testValidationErrorsUpdate()
    {
        // Arrange
        $user = User::factory()->create([
            'is_administrator' => true,
        ]);
        $this->actingAs($user, 'api');

        $this->setupPackage();

        $response = $this->post(route('api.package-crud.crud.store'), [
            'name' => 'Test',
            'description' => 'Test description',
            'code' => 'test',
            'status' => 'active',
        ]);

        $uuid = $response->json('data.uuid');

        $response = $this->put(route('api.package-crud.crud.update', $uuid), [
            'name' => '',
            'description' => 'Test description 2',
            'code' => 'test 2',
            'status' => 'inactive',
        ]);

        $response->assertStatus(500);
    }

    public function testValidationErrorsStore()
    {
        // Arrange
        $user = User::factory()->create([
            'is_administrator' => true,
        ]);
        $this->actingAs($user, 'api');

        $this->setupPackage();

        $response = $this->post(route('api.package-crud.crud.store'), [
            'name' => '',
            'description' => 'Test description',
            'code' => 'test',
            'status' => 'invalid',
        ]);

        $response->assertStatus(500);
    }

    public function testGetRecords()
    {
        // Arrange
        $user = User::factory()->create([
            'is_administrator' => true,
        ]);
        $this->actingAs($user, 'api');

        $this->setupPackage();

        $response = $this->post(route('api.package-crud.crud.store'), [
            'name' => 'Test',
            'description' => 'Test description',
            'code' => 'test',
            'status' => 'active',
        ]);

        $response = $this->get(route('api.package-crud.crud.index'));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'uuid',
                    'name',
                    'description',
                    'code',
                    'status',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }

    public function testGetRecordsByFilter()
    {
        // Arrange
        $user = User::factory()->create([
            'is_administrator' => true,
        ]);
        $this->actingAs($user, 'api');

        $this->setupPackage();

        $response = $this->post(route('api.package-crud.crud.store'), [
            'name' => 'Test',
            'description' => 'Test description',
            'code' => 'test',
            'status' => 'active',
        ]);

        $response = $this->get(route('api.package-crud.crud.index', [
            'filter' => 'Test',
        ]));

        $response->assertStatus(200);
    }

    public function testDestroyRecord500Error()
    {
        // Arrange
        $user = User::factory()->create([
            'is_administrator' => true,
        ]);
        $this->actingAs($user, 'api');

        $this->uninstallPackage();

        $response = $this->delete(route('api.package-crud.crud.destroy', 'a'));
        $response->assertStatus(500);
    }
}
