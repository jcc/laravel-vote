<?php

namespace Jcc\LaravelVote\Tests;

use Illuminate\Database\Eloquent\Model;
use Jcc\LaravelVote\Traits\Votable;

class Book extends Model
{
    use Votable;

    protected $fillable = ['title'];
}
