<?php

/*
 * This file is part of the jcc/laravel-vote.
 *
 * (c) jcc <changejian@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Jcc\LaravelVote;

trait CanBeVoted
{
    /**
     * Check if user is voted by given user.
     *
     * @param $user
     *
     * @return bool
     */
    public function isVotedBy($item)
    {
        return $this->voters->contains($user);
    }

    /**
     * Return voters.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function voters()
    {
        $property = property_exists($this, 'vote') ? $this->vote : __CLASS__;

        return $this->morphToMany($property, 'votable');
    }
}