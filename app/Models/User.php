<?php

namespace App\Models;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'bio',
        'avatar_path',
        'github',
        'linkedin',
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function getRouteKeyName()
    {
        return 'username';
    }

}
