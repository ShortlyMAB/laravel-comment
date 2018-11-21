<?php
declare(strict_types=1);

namespace Actuallymab\LaravelComment\Models;

use Actuallymab\LaravelComment\Contracts\Commentable;
use Actuallymab\LaravelComment\HasComments;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $comment
 * @property float $rate
 * @property boolean $approved
 * @property string $commentable_id
 * @property string $commentable_type
 * @property Model $commentable
 * @property string $commented_id
 * @property string $commented_type
 * @property Model $commented
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Comment extends Model implements Commentable
{
    use HasComments;

    protected $guarded = [];

    protected $casts = [
        'approved' => 'boolean'
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function commented(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeApprovedComments(Builder $query): Builder
    {
        return $query->where('approved', true);
    }

    public function approve(): self
    {
        $this->approved = true;
        $this->save();

        return $this;
    }
}
