<?php

namespace Jpeters8889\Architect\Tests\AppClasses\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $casts = ['active' => 'bool'];
}
