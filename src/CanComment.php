<?php
declare(strict_types=1);

namespace Actuallymab\LaravelComment;

use Actuallymab\LaravelComment\Contracts\Commentable;
use Actuallymab\LaravelComment\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait CanComment
{
    public function comment(Commentable $commentable, string $commentText = '', int $rate = 0): Comment
    {
        $commentModel = config('comment.model');

        $comment = new $commentModel([
            'comment'        => $commentText,
            'rate'           => $commentable->canBeRated() ? $rate : null,
            'approved'       => $commentable->mustBeApproved() && !$this->canCommentWithoutApprove() ? false : true,
            'commented_id'   => $this->primaryId(),
            'commented_type' => get_class(),
        ]);

        $commentable->comments()->save($comment);

        return $comment;
    }

    public function canCommentWithoutApprove(): bool
    {
        return false;
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(config('comment.model'), 'commented');
    }

    public function hasCommentsOn(Commentable $commentable): bool
    {
        return $this->comments()
            ->where([
                'commentable_id'   => $commentable->primaryId(),
                'commentable_type' => get_class($commentable),
            ])
            ->exists();
    }

    private function primaryId(): string
    {
        return (string)$this->getAttribute($this->primaryKey);
    }
}
