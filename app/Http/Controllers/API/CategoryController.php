<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Constants\AuthConstants;
use App\Constants\CategoryConstants;
use App\Http\Requests\CategoryRequest;

class CategoryController extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->sendResponse(
            Category::ForUser()
                ->with('products')
                ->get()
                ->toArray(),
            ''
        );
    }

    /**
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->all());

        return $this->sendResponse($category->toArray(), CategoryConstants::STORE);
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        if ($category->user_id !== Auth::id()) {
            return $this->sendError(AuthConstants::UNAUTHORIZED);
        }

        return $this->sendResponse($category->toArray(), '');
    }

    /**
     * @param CategoryRequest $request
     * @param Category $category
     * @return JsonResponse
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        if ($category->user_id !== Auth::id()) {
            return $this->sendError(AuthConstants::UNAUTHORIZED);
        }

        $category->update($request->all());

        return $this->sendResponse($category->toArray(), CategoryConstants::UPDATE);
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        if ($category->user_id !== Auth::id()) {
            return $this->sendError(AuthConstants::UNAUTHORIZED);
        }

        $category->delete();

        return $this->sendResponse([], CategoryConstants::DESTROY);
    }
}
