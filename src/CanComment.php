<?php
/**
 * Created by PhpStorm.
 * User: actuallymab
 * Date: 12.06.2016
 * Time: 02:13
 */

namespace Actuallymab\LaravelComment;

use Actuallymab\LaravelComment\Models\Comment;

trait CanComment
{

    /**
     * @param $commentable
     * @param string $commentText
     * @param int $rate
     * @return $this
     */
    public function comment($commentable, $commentText = '', $rate = 0,$name_sender = null,$email_sender = null)
    {
        $comment = new Comment([
            'comment'        => $commentText,
            'rate'           => ($commentable->getCanBeRated()) ? $rate : null,
            'approved'       => ($commentable->mustBeApproved() && ! $this->isAdmin()) ? false : true,
            'commented_id'   => empty($this->id) ? null : $this->id ,
            'email_sender'   => $email_sender ,
            'name_sender'   =>  $name_sender ,
            'commented_type' => get_class()
        ]);

        $commentable->comments()->save($comment);

        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commented');
    }
}
