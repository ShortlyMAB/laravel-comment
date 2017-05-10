# Laravel Comment (forked from actuallymab/laravel-comment)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

**Just another comment system for laravel projects.

## Version Compatibility

 Laravel  | Laravel Comment
:---------|:----------
 5.4.x    | 1.1.x
 5.2.x    | 0.3.x

## Install

Via Composer

``` bash
$ composer require ufutx/laravel-comment
```

Add service provider to your app.php file

``` php
\Ufutx\LaravelComment\LaravelCommentServiceProvider::class
```

Publish & Migrate comments table.
``` bash
$ php artisan vendor:publish
$ php artisan migrate
```

Add `CanComment` trait to your User model.
``` php
use Ufutx\LaravelComment\CanComment;
```

Add `Commentable` trait to your commentable model(s).
``` php
use Ufutx\LaravelComment\Commentable;
```

If you want to have your own Comment Model create a new one and extend my Comment model.
``` php
class Comment extends Ufutx\LaravelComment\Comment
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

// $user->comment(Commentable $model, $comment = '', $rate = 0, $pic = null);
$user->comment($product, 'Lorem ipsum ..', 3, 'http://images.ufutx.com/201702/09/49fe31fdf2d4f74709f2cb00e1a9c49a.jpeg@1e_1c_2o_1l_200h_200w_90q.src');

// approve it -- if you are admin or you don't use mustBeApproved option, it is not necessary
$product->comments[0]->approve();

//comment user model record 
$product->comments[0]->user

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

If you discover any security related issues, please email mehmet.aydin.bahadir@gmail.com or zglore  instead of using the issue tracker.

## Credits

- [Mehmet Aydın Bahadır][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/ufutx/laravel-comment.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/ufutx/laravel-comment/master.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ufutx/laravel-comment.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/ufutx/laravel-comment
[link-travis]: https://travis-ci.org/ufutx/laravel-comment
[link-downloads]: https://packagist.org/packages/ufutx/laravel-comment
[link-author]: https://github.com/glore
[link-contributors]: ../../contributors
