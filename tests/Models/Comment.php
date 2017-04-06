<?php

namespace Actuallymab\LaravelComment\Tests\Models;

use Actuallymab\LaravelComment\Commentable;
use \Actuallymab\LaravelComment\Models\Comment as BaseComment;

class Comment extends BaseComment
{
    use Commentable;

    protected $mustBeApproved = false;
    protected $canBeRated = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
