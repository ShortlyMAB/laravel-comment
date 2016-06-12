<?php
/** actuallymab | 12.06.2016 - 14:16 */


namespace Actuallymab\LaravelComment\Tests\Models;


use Actuallymab\LaravelComment\CanComment;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use CanComment;

    protected $isAdmin = false;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    /**
     * @param $isAdmin
     * @return $this
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }
}