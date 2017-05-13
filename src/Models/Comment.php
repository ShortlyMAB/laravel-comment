<?php
/** actuallymab | 12.06.2016 - 01:51 */

namespace Ufutx\LaravelComment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * Class Comment
 * @package Ufutx\LaravelComment\Models
 *
 * @property string $comment
 * @property float $rate
 * @property boolean $approved
 * @property integer $commentable_id
 * @property string $commentable_type
 * @property integer $commented_id
 * @property string $commented_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Comment extends Model
{
    protected $fillable = [
        'comment',
        'rate',
        'pic',
        'approved',
        'commented_id',
        'commented_type'
    ];

    protected $casts = [
        'approved' => 'boolean'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commented()
    {
        return $this->morphTo();
    }

    /**
     * @return $this
     */
    public function approve()
    {
        $this->approved = true;
        $this->save();

        return $this;
    }

    public function user(){
        /**
         * todo: add config to set user model
         */
        $model = Config::get('auth.providers.users.model');
        return $this->belongsTo($model, 'commented_id', 'uid');
    }

}
