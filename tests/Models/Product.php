<?php
/** actuallymab | 12.06.2016 - 14:17 */


namespace Actuallymab\LaravelComment\Tests\Models;

use Actuallymab\LaravelComment\Commentable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Commentable;

    protected $mustBeApproved = false;
    protected $canBeRated = false;

    protected $fillable = [
        'name'
    ];
    
    public $timestamps = false;

    /**
     * @param $mustBeApproved
     * @return $this
     */
    public function setMustBeApproved($mustBeApproved)
    {
        $this->mustBeApproved = $mustBeApproved;
        return $this;
    }

    /**
     * @param $canBeRated
     * @return $this
     */
    public function setCanBeRated($canBeRated)
    {
        $this->canBeRated = $canBeRated;
        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
