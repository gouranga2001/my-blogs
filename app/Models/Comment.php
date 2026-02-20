<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'blog_id',
        'author_name',
        'author_email',
        'text',
    ];
}
