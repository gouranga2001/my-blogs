<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Comment;

class Blog extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'markdown_content',
        'html_content',
        'published_at',
        'featured_image',
        'thumbnail_image'
    ];

    protected $casts = [
    'published_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
