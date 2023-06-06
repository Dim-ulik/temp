<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['image'];

    protected $hidden = ['pivot', 'created_at', 'updated_at'];

    public function getImageAttribute($value)
    {
        $host = config('app.url') . ':' . Request::getPort() . '/storage/uploads/';

        if ($value == null) {
            return $value;
        }

        return $host . $value;
    }
}
