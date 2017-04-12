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

trait Vote
{
    protected $voteRelation = __CLASS__;

    /**
     * Up vote a item or items.
     *
     * @param int|array|\Illuminate\Database\Eloquent\Model $item
     *
     * @return int
     */
    public function upVote($item)
    {
        $item = $this->checkVoteItem($item);

        return $this->votedItems($this->voteRelation)->sync((array)$item, false);
    }

    /**
     * Down vote a item or items.
     *
     * @param int|array|\Illuminate\Database\Eloquent\Model $item
     *
     * @return int
     */
    public function downVote($item)
    {
        $item = $this->checkVoteItem($item);

        return $this->votedItems($this->voteRelation)->detach((array)$item);
    }

    /**
     * Check if user has voted item.
     *
     * @param $item
     *
     * @return bool
     */
    public function hasVoted($item)
    {
        $item = $this->checkVoteItem($item);

        return $this->votedItems($this->voteRelation)->get()->contains($item);
    }

    /**
     * Return the user what has items.
     *
     * @param class $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function votedItems($class = __CLASS__)
    {
        return $this->morphedByMany($this->voteRelation, 'votable', 'votes')->withTimestamps();
    }

    /**
     * Determine whether $item is an instantiated object of \Illuminate\Database\Eloquent\Model
     *
     * @param $item
     *
     * @return int
     */
    protected function checkVoteItem($item)
    {
        if ($item instanceof \Illuminate\Database\Eloquent\Model) {
            $this->voteRelation = get_class($item);
            return $item->id;
        };

        return $item;
    }
}