<?php

namespace Tests\Feature\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function only_authenticated_user_can_access_order_list()
    {
        $res = $this->getJson('/api/orders');
        $res->assertStatus(401);

        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('/api/orders');
        $response->assertStatus(200);
    }

    /**
     * @test
     * @return void
     */
    public function user_can_not_access_un_authorized_order()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create();
        $order->user()->associate($user)->save();
        $user2 = User::factory()->create();

        $res = $this->actingAs($user2)->getJson('/api/orders/' . $order->id);
        $res->assertStatus(404);
    }

    /**
     * @test
     * @return void
     */
    public function user_can_create_order()
    {
        $products = Product::factory()->count(2)->create();
        $orderReq = [
            'products' => [
                [
                    'id' => $products->get(0)->id,
                    'quantity' => 2
                ],
                [
                    'id' => $products->get(1)->id,
                    'quantity' => 3
                ]
            ]
        ];
        $user = User::factory()->create();

        $res = $this->actingAs($user)->postJson('/api/orders', $orderReq);
        $res->assertStatus(201);
    }

    /**
     * @test
     * @return void
     */
    public function user_can_delete_own_order_if_not_accepted()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create();
        $order->user()->associate($user)->save();

        $res = $this->actingAs($user)->deleteJson("/api/orders/" . $order->id);
        $res->assertStatus(200);
    }

    /**
     * @test
     * @return void
     */
    public function user_can_not_delete_not_delivered_order_after_approved()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['status' => OrderStatus::APPROVED()]);
        $order->user()->associate($user)->save();

        $res = $this->actingAs($user)->deleteJson("/api/orders/" . $order->id);
        $res->assertStatus(403);
    }
}
