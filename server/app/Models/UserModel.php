<?php 

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = ['email', 'name', 'tel', 'password', 'is_admin', 'rating'];

    public function ratings()
    {
        return $this->hasMany('App\Models\RatingModel', 'user', 'id');
    }
}
