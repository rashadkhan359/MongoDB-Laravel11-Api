<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'blogs';
    protected $primaryKey = '_id';
    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'content',
    ];

    
}
