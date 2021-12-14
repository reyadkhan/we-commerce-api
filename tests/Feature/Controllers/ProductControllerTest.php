<?php

namespace Tests\Feature\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @test
     * @return void
     */
    public function all_user_can_access_product_list()
    {
        $response = $this->actingAs($this->getUser())->getJson('/api/products');
        $response->assertStatus(200);

        $response = $this->actingAs($this->getAdminUser())->getJson('/api/products');
        $response->assertStatus(200);
    }

    /**
     * @test
     * @return void
     */
    public function all_user_can_access_specific_product()
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->getUser())->getJson('/api/products/' . $product->id);
        $response->assertStatus(200);

        $response = $this->actingAs($this->getAdminUser())->getJson('/api/products/' . $product->id);
        $response->assertStatus(200);
    }



    /**
     * @test
     *
     * @return void
     */
    public function only_admin_can_create_product()
    {
        $productReq = [
            'name' => $this->faker->unique()->name(),
            'qty' => $this->faker->numberBetween(10, 100),
            'price' => $this->faker->numberBetween(10, 100)
        ];
        $res = $this->actingAs($this->getUser())->postJson('/api/products', $productReq);
        $res->assertStatus(403);
        $res = $this->actingAs($this->getAdminUser())->postJson('/api/products', $productReq);
        $res->assertStatus(201);
    }

    /**
     * @test
     * @return void
     */
    public function only_admin_can_update_product()
    {
        $product = Product::factory()->create();
        $productReq = [
            'name' => $product->name,
            'qty' => $this->faker->numberBetween(10, 100),
            'price' => $this->faker->numberBetween(10, 100)
        ];
        $res = $this->actingAs($this->getAdminUser())->putJson('/api/products/' . $product->id, $productReq);
        $res->assertStatus(200);

        $res = $this->actingAs($this->getUser())->putJson('/api/products/' . $product->id, $productReq);
        $res->assertStatus(403);
    }

    /**
     * @test
     * @return void
     */
    public function only_admin_can_delete_product()
    {
        $product = Product::factory()->create();
        $res = $this->actingAs($this->getAdminUser())->deleteJson('/api/products/' . $product->id);
        $res->assertStatus(200);

        $res = $this->actingAs($this->getUser())->deleteJson('/api/products/' . $product->id);
        $res->assertStatus(403);
    }
}
