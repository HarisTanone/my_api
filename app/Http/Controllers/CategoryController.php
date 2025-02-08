<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Services\CategoryServices;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryServices $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function store(CategoryRequest $request)
    {
        return $this->categoryService->createCategory($request);
    }

    public function index()
    {
        return $this->categoryService->getAllCategories();
    }

    public function show($id)
    {
        return $this->categoryService->getCategoryById($id);
    }

    public function update(CategoryRequest $request, $id)
    {
        return $this->categoryService->updateCategory($request, $id);
    }

    public function destroy($id)
    {
        return $this->categoryService->deleteCategory($id);
    }

    public function restore($id)
    {
        return $this->categoryService->restoreCategory($id);
    }
}
