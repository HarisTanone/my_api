<?php

namespace App\Http\Services;

use App\Http\Controllers\BaseController;
use App\Http\Resources\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanService extends BaseController
{

    public function createLoan(array $validated)
    {
        return DB::transaction(function () use ($validated) {
            try {
                $book = Book::findOrFail($validated['book_id']);
                $requestedQty = $validated['qty'] ?? 1;

                if ($book->stock < $requestedQty) {
                    $this->errorHandler('Stok buku tidak mencukupi. Stok tersedia: {$book->stock}', []);
                }

                $activeLoan = Loan::where('user_id', $validated['user_id'])
                    ->where('book_id', $validated['book_id'])
                    ->where('status', 'dipinjam')
                    ->first();

                if ($activeLoan) {
                    $this->errorHandler('User masih memiliki peminjaman aktif untuk buku ini', []);
                }

                $loan = Loan::create([
                    'user_id' => $validated['user_id'],
                    'book_id' => $validated['book_id'],
                    'qty' => $requestedQty,
                    'loan_date' => Carbon::now(),
                    'return_date' => Carbon::now()->addDays($validated['duration'] ?? 14),
                    'status' => 'dipinjam'
                ]);

                $book->decrement('stock', $requestedQty);
                return $loan->load('book', 'user');

            } catch (ModelNotFoundException $e) {
                $this->errorHandler('Buku tidak Tersedia', []);
            }
        });
    }
    public function returnBook($loanId)
    {
        return DB::transaction(function () use ($loanId) {
            $loan = Loan::findOrFail($loanId);

            if ($loan->status === 'dikembalikan') {
                $this->errorHandler('Book has already been returned', []);
            }

            $fine = 0;
            if (Carbon::now()->gt($loan->return_date)) {
                $daysLate = Carbon::now()->diffInDays($loan->return_date);
                $fine = $daysLate * 1000 * $loan->qty;
            }

            $return = $loan->bookReturn()->create([
                'return_date' => Carbon::now(),
                'fine' => $fine
            ]);

            $loan->update(['status' => 'dikembalikan']);
            $loan->book->increment('stock', $loan->qty);

            return $return;
        });
    }

    public function getLoanHistory($userId = null): JsonResponse
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            $userId = $user->id;
        }

        $query = Loan::with(['book', 'bookReturn'])->latest();

        if ($user->role !== 'admin') {
            $query->where('user_id', $userId);
        }

        $loans = $query->paginate(10);
        $loanResource = LoanResource::collection($loans)->response()->getData(true);

        return $this->successPageHandler(
            'Daftar peminjaman',
            $loanResource
        );
    }

}