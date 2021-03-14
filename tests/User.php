<?php

namespace Jcc\LaravelVote\Tests;

use Illuminate\Database\Eloquent\Model;
use Jcc\LaravelVote\Traits\Votable;
use Jcc\LaravelVote\Traits\Voter;

class User extends Model
{
    use Voter;
    use Votable;

    protected $fillable = ['name'];
}
