<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $categories = Category::GetListForUser()
            ->paginate($request->get('paginate') ?: '');

        return new JsonResponse([
            'success' => true,
            'categories' => $categories,
        ]);
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        if ($category->user_id !== Auth::id()) {
            return new JsonResponse(['error' => trans('api.category_permission')], Response::HTTP_BAD_REQUEST);
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
            return new JsonResponse(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
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
        ], Response::HTTP_CREATED);
    }

    /**
     * @param Category $category
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Category $category, Request $request): JsonResponse
    {
        if ($category->user_id !== Auth::id()) {
            return new JsonResponse([
                'error' => trans('api.category_permission'),
            ], Response::HTTP_BAD_REQUEST);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'min:4',
            'category_id' => 'numeric',
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        if ($request->get('name')) {
            $existCategories = Category::GetByNameForUser($request->get('name'))->get();

            if (!$existCategories->isEmpty()) {
                return new JsonResponse([
                    'error' => trans('api.category_exist'),
                ], Response::HTTP_BAD_REQUEST);
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
            'category' => $category,
        ]);
    }

    /**
     * @param Category $category
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(Category $category): JsonResponse
    {
        if ($category->user_id !== Auth::id()) {
            return new JsonResponse([
                'error' => trans('api.category_permission'),
            ], Response::HTTP_BAD_REQUEST);
        }

        $category->delete();

        return new JsonResponse(['success' => true]);
    }
}
