<?php

namespace Jcc\LaravelVote;

use Jcc\LaravelVote\Exceptions\UnexpectValueException;
use Stringable;

final class VoteItems implements Stringable
{
	/**
	 * @var string
	 */
	protected $value;

	public const UP = 'up_vote';

	public const DOWN = 'down_vote';

	public function __construct(string $value)
	{
		if (!in_array($value, self::getValues(), true)) {
			throw new UnexpectValueException("Unexpect Value: {$value}");
		}
		$this->value = $value;
	}

	public static function getValues()
	{
		return [self::UP, self::DOWN];
	}

	public function __toString()
	{
		return $this->value;
	}
}
