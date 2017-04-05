<?php
/** actuallymab | 12.06.2016 - 02:13 */


namespace Actuallymab\LaravelComment;

use Actuallymab\LaravelComment\Models\Comment;

trait Commentable
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * @return bool
     */
    public function getCanBeRated()
    {
        return (isset($this->canBeRated)) ? $this->canBeRated : false;
    }

    /**
     * @return bool
     */
    public function mustBeApproved()
    {
        return (isset($this->mustBeApproved)) ? $this->mustBeApproved : false;
    }

    /**
     * @return mixed
     */
    public function totalCommentCount()
    {
        return ($this->mustBeApproved()) ? $this->comments()->where('approved', true)->count() : $this->comments()->count();
    }

    /**
     * @return float
     */
    public function averageRate()
    {
        return ($this->getCanBeRated()) ? $this->comments()->where('approved', true)->avg('rate') : 0;
    }
}
