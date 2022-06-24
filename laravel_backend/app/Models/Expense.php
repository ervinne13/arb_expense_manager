<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'entry_date' => 'datetime'
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category', 'name');
    }

    public function scopeOwnedBy($query, $id)
    {
        return $query->where('author_id', '=', $id);
    }

    public function scopeCategoryTotals($query)
    {
        return $query->groupBy('category')->selectRaw('category, sum(amount_in_cents) as amount_in_cents');
    }
}
