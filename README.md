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
use Jcc\LaravelVote\Vote;

class User extends Model
{
    use Vote;
}
```

Or use CanBeVoted in Post model

```php
use Jcc\LaravelVote\CanBeVoted;

class Post extends Model
{
    use CanBeVoted;
}
```

## Usage

### For User model

#### Up vote a post or posts.

```php
$post = Post::find(1);

$user->upVote($post);
```

#### Cancel vote a post or posts.

```php
$post = Post::find(1);

$user->cancelVote($post);
```

#### Get user has voted items

```php
$user->votedItems();
```

#### Check if voting
```
$post = Post::find(1);

$user->hasVoted($post);
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
