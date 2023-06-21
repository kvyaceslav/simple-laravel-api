<?php

namespace App\Http\Controllers\API;

use App\Constants\AuthConstants;
use App\Constants\CategoryConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Traits\Access;
use App\Http\Traits\HttpResponses;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use Access;
    use HttpResponses;

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->success(
            CategoryResource::collection(Category::ForUser()->get())
        );
    }

    /**
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        return $this->success(
            new CategoryResource(auth()->user()->categories()->create($request->all())),
            CategoryConstants::STORE
        );
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        if (!$this->canAccess($category)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        return $this->success(new CategoryResource($category));
    }

    /**
     * @param CategoryRequest $request
     * @param Category $category
     * @return JsonResponse
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        if (!$this->canAccess($category)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        $category->update($request->all());

        return $this->success(
            new CategoryResource($category),
            CategoryConstants::UPDATE
        );
    }

    /**
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        if (!$this->canAccess($category)) {
            return $this->error([], AuthConstants::PERMISSION);
        }

        $category->delete();

        return $this->success([], CategoryConstants::DESTROY);
    }
}
