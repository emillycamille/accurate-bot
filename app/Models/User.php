<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'psid', 'first_name', 'last_name', 'name', 'email',
        'access_token', 'refresh_token', 'host',
        'session',
    ];
}
