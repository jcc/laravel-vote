<?php

namespace Jcc\LaravelVote\Traits;

use Illuminate\Database\Eloquent\Model;
use Jcc\LaravelVote\VoteItems;

/**
 * Trait Votable
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait Votable
{
	/**
	 * @param \Illuminate\Database\Eloquent\Model $user
	 *
	 * @return bool
	 */
	public function isVotedBy(Model $user, ?string $type = null): bool
	{
		if (\is_a($user, config('auth.providers.users.model'))) {
			if ($this->relationLoaded('voters')) {
				return $this->voters->contains($user);
			}

			return $this->voters()
				->where(\config('vote.user_foreign_key'), $user->getKey())
				->when(is_string($type), function ($builder) use ($type) {
					$builder->where('vote_type', (string)new VoteItems($type));
				})
				->exists();
		}

		return false;
	}

	/**
	 * Return voters.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function voters(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(
			config('auth.providers.users.model'),
			config('vote.votes_table'),
			'votable_id',
			config('vote.user_foreign_key')
		)
			->where('votable_type', $this->getMorphClass());
	}

	/**
	 * @param \Illuminate\Database\Eloquent\Model $user
	 *
	 * @return bool
	 */
	public function isUpVotedBy(Model $user)
	{
		return $this->isVotedBy($user, VoteItems::UP);
	}
	
	/**
	 * Return up voters.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function upVoters(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return  $this->voters()->where('vote_type', VoteItems::UP);
	}

	/**
	 * @param \Illuminate\Database\Eloquent\Model $user
	 *
	 * @return bool
	 */
	public function isDownVotedBy(Model $user)
	{
		return $this->isVotedBy($user, VoteItems::DOWN);
	}

	/**
	 * Return down voters.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function downVoters(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return  $this->voters()->where('vote_type', VoteItems::DOWN);
	}
}
