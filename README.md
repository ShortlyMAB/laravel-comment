# Laravel Comment

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/actuallymab/laravel-comment/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/actuallymab/laravel-comment/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/actuallymab/laravel-comment/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/actuallymab/laravel-comment/?branch=master)

Just another comment system for your awesome Laravel project.

## Version Compatibility

 Laravel  | Laravel Comment
:---------|:----------
 5.0.x    | 0.1.x
 5.1.x    | 0.1.x
 5.2.x    | 0.1.x
 5.3.x    | 0.2.x
 5.4.x    | 0.3.x
 
For `>5.5` you can use `^1.0.0` version.

## Install

Via Composer

``` bash
$ composer require actuallymab/laravel-comment
```

If you don't use auto-discovery, or using Laravel version < 5.5 Add service provider to your app.php file

``` php
\Actuallymab\LaravelComment\LaravelCommentServiceProvider::class
```

Publish configurations and migrations, then migrate comments table.

``` bash
$ php artisan vendor:publish
$ php artisan migrate
```

Add `CanComment` trait to your User model.

``` php
use Actuallymab\LaravelComment\CanComment;
```

Add `Commentable` interface and `HasComments` trait to your commentable model(s).

``` php
use Actuallymab\LaravelComment\Contracts\Commentable;
use Actuallymab\LaravelComment\HasComments;

class Product extends Model implements Commentable
{
    use HasComments;
    
    // ...   
}
```

If you want to have your own Comment Model create a new one and extend my Comment model.

``` php
use Actuallymab\LaravelComment\Models\Comment as LaravelComment;

class Comment extends LaravelComment
{
    // ...
}
```

and dont forget to update the model name in the `config/comment.php` file.

Comment package comes with several modes.

1- If you want to users can rate your commentable models;

``` php
class Product extends Model implements Commentable 
{
    use HasComments;

    public function canBeRated(): bool
    {
        return true; // default false
    }

    //...
}
```

2- If you want to approve comments for your commentable models;

``` php
class Product extends Model implements Commentable 
{
    use HasComments;

    public function mustBeApproved(): bool
    {
        return true; // default false
    }

    // ...
}
```

3- Sometimes you don't want to approve comments for all users;

``` php
class User extends Model 
{
    use CanComment;
  
    protected $fillable = [
        'isAdmin',
        // ..
    ];

    public function canCommentWithoutApprove(): bool
    {
        return $this->isAdmin;
    }

    // ..
}
```

## Usage

``` php
$user = App\User::first();
$product = App\Product::first();

// $user->comment(Commentable $model, $comment = '', $rate = 0);
$user->comment($product, 'Lorem ipsum ..', 3);

// approve it -- if the user model `canCommentWithoutApprove()` or you don't use `mustBeApproved()`, it is not necessary
$product->comments[0]->approve();

// get avg rating -- it calculates approved average rate.
$product->averageRate();

// get total comments count -- it calculates approved comments count.
$product->totalCommentsCount();
```

> Tip: You might want to look at the tests/CommentTest.php file to check all potential usages. 

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email mehmet.aydin.bahadir@gmail.com instead of using the issue tracker.

## Credits

- [Mehmet Aydın Bahadır][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/actuallymab/laravel-comment.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/actuallymab/laravel-comment/master.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/actuallymab/laravel-comment.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/actuallymab/laravel-comment
[link-travis]: https://travis-ci.org/actuallymab/laravel-comment
[link-downloads]: https://packagist.org/packages/actuallymab/laravel-comment
[link-author]: https://github.com/actuallymab
[link-contributors]: ../../contributors
