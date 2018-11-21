<?php
declare(strict_types=1);

namespace Actuallymab\LaravelComment;

use Actuallymab\LaravelComment\Contracts\Commentable;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait CanComment
{
    public function comment(Commentable $commentable, string $commentText = '', int $rate = 0): self
    {
        $commentModel = config('comment.model');

        $commentable->comments()->save(new $commentModel([
            'comment'        => $commentText,
            'rate'           => $commentable->canBeRated() ? $rate : null,
            'approved'       => $commentable->mustBeApproved() && !$this->canCommentWithoutApprove() ? false : true,
            'commented_id'   => $this->primaryId(),
            'commented_type' => get_class(),
        ]));

        return $this;
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
