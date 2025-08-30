<?php

namespace App\Models;

use Attribute;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'category_id',
        'date_transaction',
        'note',
        'amount',
        'image'
    ];


    /**
     * Get the user that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeExpanses($query)
    {
        return $query->whereHas('category', function($query){
            $query->where('is_expense',true);
        });
    }

        public function scopeIncomes($query)
    {
        return $query->whereHas('category', function($query){
            $query->where('is_expense',false);
        });
    }


    
}
