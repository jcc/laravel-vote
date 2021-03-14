<?php

namespace Jcc\LaravelVote\Events;

use Illuminate\Database\Eloquent\Model;

class Event
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $vote;

    /**
     * Event constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $vote
     */
    public function __construct(Model $vote)
    {
        $this->vote = $vote;
    }
}
