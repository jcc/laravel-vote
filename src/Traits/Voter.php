<?php

namespace Jcc\LaravelVote\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jcc\LaravelVote\Vote;
use Jcc\LaravelVote\VoteItems;

/**
 * Trait Voter
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait Voter
{
	/**
	 * @param Model $object
	 * @param string $type
	 * @return \Jcc\LaravelVote\Vote
	 * @throws \Jcc\LaravelVote\Exceptions\UnexpectValueException
	 */
	protected function vote(Model $object, string $type): Vote
	{
		$attributes = [
			'votable_type' => $object->getMorphClass(),
			'votable_id' => $object->getKey(),
			\config('vote.user_foreign_key') => $this->getKey(),
		];

		/* @var \Illuminate\Database\Eloquent\Model $vote */
		$vote = \app(\config('vote.vote_model'));

		$type = (string)new VoteItems($type);

		/* @var \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder $vote */
		return tap($vote->where($attributes)->firstOr(
			function () use ($vote, $attributes, $type) {
				$vote->unguard();

				if ($this->relationLoaded('votes')) {
					$this->unsetRelation('votes');
				}

				return $vote->create(\array_merge($attributes, [
					'type' => $type
				]));
			}
		), function (Model $model) use ($type) {
			$model->update(['type' => $type]);
		});
	}

	/**
	 * @param  \Illuminate\Database\Eloquent\Model  $object
	 *
	 * @return bool
	 */
	public function hasVoted(Model $object, ?string $type = null): bool
	{
		return ($this->relationLoaded('votes') ? $this->votes : $this->votes())
				->where('votable_id', $object->getKey())
				->where('votable_type', $object->getMorphClass())
				->when(\is_string($type), function ($builder) use ($type) {
					$builder->where('type', (string)new VoteItems($type));
				})
				->count() > 0;
	}

	/**
	 * @param Model $object
	 * @return bool
	 * @throws \Exception
	 */
	public function cancelVote(Model $object): bool
	{
		/* @var \Jcc\LaravelVote\Vote $relation */
		$relation = \app(\config('vote.vote_model'))
			->where('votable_id', $object->getKey())
			->where('votable_type', $object->getMorphClass())
			->where(\config('vote.user_foreign_key'), $this->getKey())
			->first();

		if ($relation) {
			if ($this->relationLoaded('votes')) {
				$this->unsetRelation('votes');
			}

			return $relation->delete();
		}

		return true;
	}

	/**
	 * @return HasMany
	 */
	public function votes(): HasMany
	{
		return $this->hasMany(\config('vote.vote_model'), \config('vote.user_foreign_key'), $this->getKeyName());
	}

	/**
	 * Get Query Builder for votes
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function getVotedItems(string $model, ?string $type = null)
	{
		return \app($model)->whereHas(
			'voters',
			function ($builder) use ($type) {
				return $builder->where(\config('vote.user_foreign_key'), $this->getKey())->when(\is_string($type), function ($builder) use ($type) {
					$builder->where('type', (string)new VoteItems($type));
				});
			}
		);
	}

	public function upVote(Model $object): Vote
	{
		return $this->vote($object, VoteItems::UP);
	}

	public function downVote(Model $object): Vote
	{
		return $this->vote($object, VoteItems::DOWN);
	}

	public function hasUpVoted(Model $object)
	{
		return $this->hasVoted($object, VoteItems::UP);
	}

	public function hasDownVoted(Model $object)
	{
		return $this->hasVoted($object, VoteItems::DOWN);
	}

	public function toggleUpVote(Model $object)
	{
		return $this->hasUpVoted($object) ? $this->cancelVote($object) : $this->upVote($object);
	}

	public function toggleDownVote(Model $object)
	{
		return $this->hasDownVoted($object) ? $this->cancelVote($object) : $this->downVote($object);
	}

	public function getUpVotedItems(string $model)
	{
		return $this->getVotedItems($model, VoteItems::UP);
	}

	public function getDownVotedItems(string $model)
	{
		return $this->getVotedItems($model, VoteItems::DOWN);
	}
}
