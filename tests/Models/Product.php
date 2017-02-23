<?php
/** actuallymab | 12.06.2016 - 14:17 */


namespace Ufutx\LaravelComment\Tests\Models;

use Ufutx\LaravelComment\Commentable;
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
}
