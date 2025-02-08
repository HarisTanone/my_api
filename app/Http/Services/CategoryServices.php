<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryServices extends BaseController
{
    public function createCategory($request): JsonResponse
    {
        $category = Category::create([
            'name' => $request->name,
        ]);

        return $this->successHandler('Kategori berhasil ditambahkan', $category);
    }

    public function getAllCategories(): JsonResponse
    {
        $categories = Category::paginate(10);
        $categoriesResource = CategoryResource::collection($categories)->response()->getData(true);

        return $this->successPageHandler(
            'Daftar kategori',
            $categoriesResource
        );
    }

    public function getCategoryById($id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorHandler('Kategori tidak ditemukan', []);
        }

        return $this->successHandler('Detail kategori', $category);
    }

    public function updateCategory($request, $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorHandler('Kategori tidak ditemukan', []);
        }

        $category->update([
            'name' => $request->name,
        ]);

        return $this->successHandler('Kategori berhasil diperbarui', $category);
    }

    public function deleteCategory($id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorHandler('Kategori tidak ditemukan', []);
        }

        $category->delete();

        return $this->successHandler('Kategori berhasil dihapus', []);
    }

    public function restoreCategory($id): JsonResponse
    {
        $category = Category::onlyTrashed()->find($id);

        if (!$category) {
            return $this->errorHandler('Kategori tidak ditemukan atau tidak dalam status terhapus', []);
        }

        $category->restore();

        return $this->successHandler('Kategori berhasil dikembalikan', $category);
    }
}
