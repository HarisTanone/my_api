<?php
namespace App\Http\Controllers;

use App\Http\Requests\LoanRequest;
use App\Http\Resources\LoanResource;
use App\Http\Services\LoanService;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function index(Request $request)
    {
        return $this->loanService->getLoanHistory($request->user()->id);
    }

    public function store(LoanRequest $request)
    {
        $loan = $this->loanService->createLoan($request->validated());
        return new LoanResource($loan);
    }

    public function rBook($id)
    {
        return $this->loanService->returnBook($id);
    }
}
