<?php
declare(strict_types=1);

namespace Actuallymab\LaravelComment;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * @property Collection $comments
 */
trait HasComments
{
    public function comments(): MorphMany
    {
        return $this->morphMany(config('comment.model'), 'commentable');
    }

    public function canBeRated(): bool
    {
        return false;
    }

    public function mustBeApproved(): bool
    {
        return false;
    }

    public function primaryId(): string
    {
        return (string)$this->getAttribute($this->primaryKey);
    }

    public function averageRate(int $round = 2): float
    {
        if (!$this->canBeRated()) {
            return 0;
        }

        /** @var Builder $rates */
        $rates = $this->comments()->approvedComments();

        if (!$rates->exists()) {
            return 0;
        }

        return round((float)$rates->avg('rate'), $round);
    }

    public function totalCommentsCount(): int
    {
        if (!$this->mustBeApproved()) {
            return $this->comments()->count();
        }

        return $this->comments()->approvedComments()->count();
    }
}
