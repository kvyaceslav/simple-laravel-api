<?php

namespace App\Http\Controllers\API;

use App\Constants\AuthConstants;
use App\Constants\ProductConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Traits\Access;
use App\Http\Traits\HttpResponses;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use Access;
    use HttpResponses;

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->success(ProductResource::collection(Product::ForUser()->get()));
    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $product = auth()->user()->products()->create($request->all());

        if (isset($request->categories)) {
            $categories = Category::ForUserByIds($request->categories);

            if (!$categories->isEmpty()) {
                $product->categories()->attach($categories);
            }
        }

        return $this->success(new ProductResource($product), ProductConstants::STORE);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        if (!$this->canAccess($product)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        return $this->success(new ProductResource($product));
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        if (!$this->canAccess($product)) {
            return $this->error(AuthConstants::PERMISSION);
        }

        if (isset($request->categories)) {
            $categories = Category::ForUserByIds($request->categories);

            $product->categories()->detach();
            if (!$categories->isEmpty()) {
                $product->categories()->attach($categories);
            }
        }

        $product->update($request->all());

        return $this->success(new ProductResource($product), ProductConstants::UPDATE);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        if (!$this->canAccess($product)) {
            return $this->error(AuthConstants::PERMISSION);
        }

        $product->delete();

        return $this->success([], ProductConstants::DESTROY);
    }
}
