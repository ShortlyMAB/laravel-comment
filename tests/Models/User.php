<?php
/** actuallymab | 12.06.2016 - 14:16 */


namespace Ufutx\LaravelComment\Tests\Models;

use Ufutx\LaravelComment\CanComment;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use CanComment;

    public $isAdmin = false;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }
}
