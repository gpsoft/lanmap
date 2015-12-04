<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

class User extends Entity
{
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    protected function _setPassword($value) {
        return User::hashedPassword($value);
    }

    public static function hashedPassword($pw) {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($pw);   // 内部でpassword_hash()を使う。
    }
    public static function passwordMatch($pw, $hashedPw) {
        $hasher = new DefaultPasswordHasher();
        return $hasher->check($pw, $hashedPw);
    }
}
