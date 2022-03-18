<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'subCategory_id', 'amount', 'payer', 'dueOn', 'VAT' , 'is_VAT_inclusive'];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
