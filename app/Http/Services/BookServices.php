<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BookServices extends BaseController
{
    public function getAllBooks(): JsonResponse
    {
        $books = Book::with('category')->paginate(10);
        $booksResource = BookResource::collection($books)->response()->getData(true);

        return $this->successPageHandler(
            'Daftar buku',
            $booksResource
        );
    }

    public function getBookById($id): JsonResponse
    {
        $book = Book::with('category')->find($id);

        if (!$book) {
            return $this->errorHandler('Buku tidak ditemukan', []);
        }

        return $this->successHandler('Detail buku', new BookResource($book));
    }

    public function createBook($request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $book = Book::create($request->validated());

            DB::commit();
            return $this->successHandler('Buku berhasil ditambahkan', new BookResource($book));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorHandler('Gagal menambahkan buku', []);
        }
    }

    public function updateBook($request, $id): JsonResponse
    {
        $book = Book::find($id);
        if (!$book) {
            return $this->errorHandler('Buku tidak ditemukan', []);
        }

        DB::beginTransaction();
        try {
            $book->update($request->validated());

            DB::commit();
            return $this->successHandler('Buku berhasil diperbarui', new BookResource($book));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorHandler('Gagal memperbarui buku', []);
        }
    }

    public function deleteBook($id): JsonResponse
    {
        $book = Book::find($id);
        if (!$book) {
            return $this->errorHandler('Buku tidak ditemukan', []);
        }

        $book->delete();
        return $this->successHandler('Buku berhasil dihapus', []);
    }

    public function restoreBook($id): JsonResponse
    {
        $book = Book::withTrashed()->find($id);
        if (!$book) {
            return $this->errorHandler('Buku tidak ditemukan', []);
        }

        $book->restore();
        return $this->successHandler('Buku berhasil dikembalikan', new BookResource($book));
    }
}
