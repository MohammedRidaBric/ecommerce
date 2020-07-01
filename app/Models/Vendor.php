<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use Notifiable;

    protected $table = 'vendors';

    protected $fillable = ['name', 'mobile', 'address', 'email', 'password', 'active', 'logo', 'category_id', 'created_at', 'updated_at'];

    protected $hidden = ['category_id', 'password', 'created_at', 'updated_at'];

    //
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) $this->attributes['password'] = bcrypt($value);
    }

    //

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل' : 'غير مفعل';

    }


    public function scopeSelection($query)
    {
        return $query->select('id', 'category_id', 'active', 'name', 'address', 'email', 'logo', 'mobile');
    }


    //
    public function categories()
    {

        return $this->belongsTo(MainCategory::class, 'category_id');
    }
}
