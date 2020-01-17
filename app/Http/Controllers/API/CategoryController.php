<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = Category::GetListForUser()->get();

        return new JsonResponse([
            'success' => true,
            'categories' => $categories,
        ], 200);
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        if ($category->user_id !== Auth::id()) {
            return new JsonResponse(['error' => 'You don`t have permission for this category'], 400);
        }

        return new JsonResponse([
            'success' => true,
            'category' => $category->load(['products']),
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
            'category_id' => 'numeric',
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['error' => $validator->errors()], 400);
        }

        $category = new Category();
        $category->name = $request->get('name');
        $category->user_id = Auth::id();

        if ($request->get('category_id')) {
            $parentCategory = Category::find($request->get('category_id'));

            if ($parentCategory) {
                $category->category_id = $parentCategory->id;
            }
        }

        $category->save();

        return new JsonResponse([
            'success' => true,
            'category' => $category,
        ], 201);
    }

    /**
     * @param Category $category
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Category $category, Request $request): JsonResponse
    {
        if ($category->user_id !== Auth::id()) {
            return new JsonResponse(['error' => 'You don`t have permission for this category'], 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'min:4',
            'category_id' => 'numeric',
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['error' => $validator->errors()], 400);
        }

        if ($request->get('name')) {
            $existCategories = Category::GetByNameForUser($request->get('name'))->get();

            if (!$existCategories->isEmpty()) {
                return new JsonResponse(['error' => 'Category with same name already exist'], 400);
            }

            $category->name = $request->get('name');
        }

        if ($request->get('category_id')) {
            $parentCategory = Category::find($request->get('category_id'));

            if ($parentCategory) {
                $category->category_id = $parentCategory->id;
            }
        }

        $category->save();

        return new JsonResponse([
            'success' => true,
            'category' => $category
        ], 200);
    }

    /**
     * @param Category $category
     * @return JsonResponse
     * @throws \Exception
     */
    public function delete(Category $category): JsonResponse
    {
        if ($category->user_id !== Auth::id()) {
            return new JsonResponse(['error' => 'You don`t have permission for this category'], 400);
        }

        $category->delete();

        return new JsonResponse(['success' => true], 200);
    }
}
