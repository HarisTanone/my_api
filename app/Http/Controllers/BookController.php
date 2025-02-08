<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;
use App\Http\Services\BookServices;

class BookController extends Controller
{
    protected $bookServices;

    public function __construct(BookServices $bookServices)
    {
        $this->bookServices = $bookServices;
    }


    public function store(BookRequest $request)
    {
        return $this->bookServices->createBook($request);
    }

    public function index()
    {
        return $this->bookServices->getAllBooks();
    }

    public function show($id)
    {
        return $this->bookServices->getBookById($id);
    }

    public function update(BookRequest $request, $id)
    {
        return $this->bookServices->updateBook($request, $id);
    }

    public function destroy($id)
    {
        return $this->bookServices->deleteBook($id);
    }

    public function restore($id)
    {
        return $this->bookServices->restoreBook($id);
    }
}
