<?php

namespace Jcc\LaravelVote;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Jcc\LaravelVote\Events\CancelVoted;
use Jcc\LaravelVote\Events\DownVoted;
use Jcc\LaravelVote\Events\UpVoted;

class Vote extends Model
{
	protected $guarded = [];

	protected $dispatchesEvents = [
		'upVoted'   => UpVoted::class,
		'downVoted' => DownVoted::class,
		'deleted'   => CancelVoted::class,
	];

	protected $observables = [
		'upVoted', 'downVoted',
	];

	/**
	 * @param array $attributes
	 */
	public function __construct(array $attributes = [])
	{
		$this->table = \config('vote.votes_table');

		parent::__construct($attributes);
	}

	protected static function boot()
	{
		parent::boot();

		self::creating(function (Vote $vote) {
			$userForeignKey = \config('vote.user_foreign_key');
			$vote->{$userForeignKey} = $vote->{$userForeignKey} ?: Auth::id();
		});

		$eventCallback = function (Vote $vote) {
			if ($vote->isUp()) {
				$vote->fireModelEvent('upVoted', false);
			}
			if ($vote->isDown()) {
				$vote->fireModelEvent('downVoted', false);
			}
		};
		self::created($eventCallback);
		self::updated($eventCallback);
	}

	public function votable(): MorphTo
	{
		return $this->morphTo();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(\config('auth.providers.users.model'), \config('vote.user_foreign_key'));
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function voter()
	{
		return $this->user();
	}

	/**
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param string                                $type
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWithType(Builder $query, string $type)
	{
		return $query->where('votable_type', \app($type)->getMorphClass());
	}

	public function isUp()
	{
		return $this->type === VoteItems::UP;
	}

	public function isDown()
	{
		return $this->type === VoteItems::DOWN;
	}
}
