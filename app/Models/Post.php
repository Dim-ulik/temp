<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['header', 'text', 'image1', 'image2', 'image3'];
    protected $hidden = ['pivot', 'updated_at'];

    private function makeUrl($value) {
        $host = config('app.url') . ':' . Request::getPort() . '/storage/uploads/';

        if ($value == null) {
            return $value;
        }

        return $host . $value;
    }

    public function getImage1Attribute($value)
    {
        return $this->makeUrl($value);
    }

    public function getImage2Attribute($value)
    {
        return $this->makeUrl($value);
    }

    public function getImage3Attribute($value)
    {
        return $this->makeUrl($value);
    }
}
