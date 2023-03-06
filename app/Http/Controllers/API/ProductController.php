<?php

namespace App\Http\Controllers\API;

use App\Constants\AuthConstants;
use App\Constants\ProductConstants;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductController extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->sendResponse(
            Product::ForUser()
                ->with('categories')
                ->get()
                ->toArray(),
            ''
        );
    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $product = Product::create($request->all());

        if (isset($request->categories)) {
            $categories = Category::ForUserByIds($request->categories);

            if (!$categories->isEmpty()) {
                $product->categories()->attach($categories);
            }
        }

        return $this->sendResponse(
            $product
                ->load('categories')
                ->toArray(),
            ProductConstants::STORE
        );
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        if ($product->user_id !== Auth::id()) {
            return $this->sendError(AuthConstants::UNAUTHORIZED);
        }

        return $this->sendResponse($product->load('categories')->toArray(), '');
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        if ($product->user_id !== Auth::id()) {
            return $this->sendError(AuthConstants::UNAUTHORIZED);
        }

        if (isset($request->categories)) {
            $categories = Category::ForUserByIds($request->categories);

            if (!$categories->isEmpty()) {
                $product->categories()->detach();
                $product->categories()->attach($categories);
            } else {
                $product->categories()->detach();
            }
        }

        $product->update($request->all());

        return $this->sendResponse(
            $product
                ->load('categories')
                ->toArray(),
            ProductConstants::UPDATE
        );
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        if ($product->user_id !== Auth::id()) {
            return $this->sendError(AuthConstants::UNAUTHORIZED);
        }

        $product->delete();

        return $this->sendResponse([], ProductConstants::DESTROY);
    }
}
