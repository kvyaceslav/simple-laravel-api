<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Response;

class ProductControllerTest extends TestCase
{
    /**
     * @return void
     */
    public function test_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('api/product');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function test_store(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('api/product', Product::factory()->raw());

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function test_show(): void
    {
        $user = User::factory()->create();

        $product = $user->products()->create(Product::factory()->make()->toArray());

        $response = $this->actingAs($user)->get('api/product/' . $product->id);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function test_update(): void
    {
        $user = User::factory()->create();

        $product = $user->products()->create(Product::factory()->make()->toArray());

        $response = $this->actingAs($user)->patchJson('api/product/' . $product->id, [
            'name' => fake()->name(),
            'price' => fake()->numberBetween(1, 100),
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function test_destroy(): void
    {
        $user = User::factory()->create();

        $product = $user->products()->create(Product::factory()->make()->toArray());

        $response = $this->actingAs($user)->delete('api/product/' . $product->id);

        $response->assertStatus(Response::HTTP_OK);
    }
}
