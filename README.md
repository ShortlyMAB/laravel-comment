# Laravel Comment

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

Just another comment system for laravel projects.

## Version Compatibility

 Laravel  | Laravel Comment
:---------|:----------
 5.0.x    | 0.1.x
 5.1.x    | 0.1.x
 5.2.x    | 0.1.x
 5.3.x    | 0.2.x
 5.4.x    | 0.3.x
 5.5.x    | 0.4.x 

## Install

Via Composer

``` bash
$ composer require actuallymab/laravel-comment
```

Add service provider to your app.php file

``` php
\Actuallymab\LaravelComment\LaravelCommentServiceProvider::class
```

Publish & Migrate comments table.
``` bash
$ php artisan vendor:publish
$ php artisan migrate
```

Add `CanComment` trait to your User model.
``` php
use Actuallymab\LaravelComment\CanComment;
```

Add `Commentable` trait to your commentable model(s).
``` php
use Actuallymab\LaravelComment\Commentable;
```

If you want to have your own Comment Model create a new one and extend my Comment model.
``` php
class Comment extends Actuallymab\LaravelComment\Comment
{
  ...
}
```

Comment package comes with several modes.

1- If you want to Users can rate your model(s) with comment set `canBeRated` to true in your `Commentable` model.
``` php
class Product extends Model {
  use Commentable;

  protected $canBeRated = true;

  ...
}
```

2- If you want to approve comments for your commentable models, you must set `mustBeApproved` to true in your `Commentable` model.
``` php
class Product extends Model {
  use Commentable;

  protected $mustBeApproved = true;

  ...
}
```

3- You don't want to approve comments for all users (think this as you really want to approve your own comments?). So add your `User` model an `isAdmin` method and return it true if user is admin.

``` php
class User extends Model {
  use CanComment;
  
  protected $fillable = [
    'isAdmin',
    ....
  ];

  public function isAdmin() {
    return $this->isAdmin;
  }

  ...
}
```

## Usage

``` php
$user = App\User::find(1);
$product = App\Product::find(1);

// $user->comment(Commentable $model, $comment = '', $rate = 0);
$user->comment($product, 'Lorem ipsum ..', 3);

// approve it -- if you are admin or you don't use mustBeApproved option, it is not necessary
$product->comments[0]->approve();

// get avg rating -- it calculates approved average rate.
$product->averageRate();

// get total comment count -- it calculates approved comments count.
$product->totalCommentCount();
```

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
