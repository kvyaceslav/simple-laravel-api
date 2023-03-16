<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Response;

class CategoryControllerTest extends TestCase
{
    /**
     * @return void
     */
    public function test_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('api/category');

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function test_store(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('api/category', Category::factory()->raw());

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function test_show(): void
    {
        $user = User::factory()->create();

        $category = $user->categories()->create(Category::factory()->make()->toArray());

        $response = $this->actingAs($user)->get('api/category/' . $category->id);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function test_update(): void
    {
        $user = User::factory()->create();

        $category = $user->categories()->create(Category::factory()->make()->toArray());

        $response = $this->actingAs($user)->patchJson('api/category/' . $category->id, [
            'name' => fake()->name(),
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function test_destroy(): void
    {
        $user = User::factory()->create();

        $category = $user->categories()->create(Category::factory()->make()->toArray());

        $response = $this->actingAs($user)->delete('api/category/' . $category->id);

        $response->assertStatus(Response::HTTP_OK);
    }
}
