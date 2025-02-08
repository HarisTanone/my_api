<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'returns';

    protected $fillable = [
        'loan_id',
        'return_date',
        'fine'
    ];

    protected $casts = [
        'return_date' => 'date',
        'fine' => 'decimal:2'
    ];

    protected $dates = ['deleted_at'];

    // Relasi dengan Loan
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
