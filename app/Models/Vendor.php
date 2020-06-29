<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';

    protected $fillable = ['name', 'mobile', 'address', 'email', 'active', 'logo', 'category_id', 'created_at', 'updated_at'];

    protected $hidden = ['category_id', 'created_at', 'updated_at'];

    //

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeSelection($query)
    {
        return $query->select('name', 'mobile', 'logo', 'category_id');
    }


    //
    public function categories()
    {

        return $this->belongsTo(MainCategory::class, 'category_id');
    }
}
