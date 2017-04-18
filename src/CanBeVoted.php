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
    public function isVotedBy($user)
    {
        return $this->voters->contains($user);
    }

    /**
     * Count the number of up votes.
     *
     * @return int
     */
    public function countUpVoters()
    {
        return $this->countVoters('up_vote');
    }

    /**
     * Count the number of down votes.
     *
     * @return int
     */
    public function countDownVoters()
    {
        return $this->countVoters('down_vote');
    }

    /**
     * Count the number of voters.
     *
     * @param  string $type
     *
     * @return int
     */
    public function countVoters($type = null)
    {
        $voters = $this->voters();

        if(!is_null($type)) $voters->wherePivot('type', $type);

        return $voters->count();
    }

    /**
     * Return voters.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function voters()
    {
        $property = property_exists($this, 'vote') ? $this->vote : __CLASS__;

        return $this->morphToMany($property, 'votable', 'votes');
    }
}
