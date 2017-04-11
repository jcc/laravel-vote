# Laravel 5 Vote System

:tada: This package helps you to add user based vote system to your model.

> This project code is basically the same as [laravel-follow](https://github.com/overtrue/laravel-follow).

## Installation

You can install the package using composer

```sh
$ composer require jcc/laravel-vote -vvv
```

Then add the service provider to `config/app.php`

```php
Jcc\LaravelVote\VoteServiceProvider::class
```

Publish the migrations file:

```sh
$ php artisan vendor:publish --provider="Jcc\LaravelVote\VoteServiceProvider" --tag="migrations"
```

Finally, use VoteTrait in User model

```php
use Jcc\LaravelVote\VoteTrait;

class User extends Model
{
    use VoteTrait;
}
```

Or use VoterTrait in Post model

```php
use Jcc\LaravelVote\VoterTrait;

class Post extends Model
{
    use VoterTrait;
}
```

## Usage

### For User model

#### Vote a post or posts.

```php
$post = Post::find(1);

$user->upVote($post);
```

#### Unvote a post or posts.

```php
$post = Post::find(1);

$user->downVote($post);
```

#### Get user votings

```php
$user->votings();
```

#### Check if vote
```
$user->isVoting(1);
```

### For Post model

#### Get post voters

```php
$post->voters();
```

#### Check if voted by

```php
$post->isVotedBy(1);
```

## Reference

[laravel-follow](https://github.com/overtrue/laravel-follow)

## License

MIT
