<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = Product::GetListForUser()->get();

        return new JsonResponse([
            'success' => true,
            'products' => $products,
        ], 200);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        if ($product->user_id !== Auth::id()) {
            return new JsonResponse(['error' => 'You don`t have permission for this product'], 400);
        }

        return new JsonResponse([
            'success' => true,
            'product' => $product->load(['categories']),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:4',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['error' => $validator->errors()], 400);
        }

        $product = new Product();
        $product->name = $request->get('name');
        $product->price = $request->get('price');
        $product->user_id = Auth::id();
        $product->save();

        return new JsonResponse([
            'success' => true,
            'product' => $product
        ], 201);
    }

    /**
     * @param Product $product
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Product $product, Request $request): JsonResponse
    {
        if ($product->user_id !== Auth::id()) {
            return new JsonResponse(['error' => 'You don`t have permission for this product'], 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'min:4',
            'price' => 'numeric',
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['error' => $validator->errors()], 400);
        }

        if ($request->get('name')) {
            $existProducts = Product::GetByNameForUser($request->get('name'))->get();

            if (!$existProducts->isEmpty()) {
                return new JsonResponse(['error' => 'Product with same name already exist'], 400);
            }

            $product->name = $request->get('name');
        }

        if ($request->get('price')) {
            $product->price = $request->get('price');
        }

        if ($request->get('categories')) {
            $categories = Category::find($request->get('categories'));

            $product->categories()->attach($categories);
        }

        $product->save();

        return new JsonResponse([
            'success' => true,
            'product' => $product
        ], 200);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Product $product): JsonResponse
    {
        if ($product->user_id !== Auth::id()) {
            return new JsonResponse(['error' => 'You don`t have permission for this product'], 400);
        }

        $product->delete();

        return new JsonResponse(['success' => true], 200);
    }

    /**
     * @param Product $product
     * @param Category $category
     * @return JsonResponse
     */
    public function removeCategory(Product $product, Category $category): JsonResponse
    {
        if ($product->user_id !== Auth::id()) {
            return new JsonResponse(['error' => 'You don`t have permission for this product'], 400);
        }

        if ($category->user_id !== Auth::id()) {
            return new JsonResponse(['error' => 'You don`t have permission for this category'], 400);
        }

        $product->categories()->detach($category);

        return new JsonResponse([
            'success' => true,
            'product' => $product,
        ], 200);
    }
}
