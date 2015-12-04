<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Log\LogTrait;

/**
 * Users Model
 *
 */
class UsersTable extends Table
{
    use LogTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('users');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name')
            ->add('name', 'unique', [
                'rule'=>'validateUnique',
                'provider'=>'table',
                'message'=>'The user name is already used']);

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password', null, 'create');

        $validator
            ->notEmpty(
                'cur_password',
                'Input the current password to change it',
                [$this, 'changingPassword'])    // パスワード変更時のみチェック。
            ->add('cur_password', 'valid', [
                'rule'=>[$this, 'passwordShouldMatch'],
                'on'=>[$this, 'changingPassword'],
                'message'=>'Current password does not match']);

        $validator
            ->add('admin', 'valid', ['rule' => 'boolean'])
            ->requirePresence('admin', 'create')
            ->notEmpty('admin');

        return $validator;
    }

    public function changingPassword($ctx) {
        return !empty($ctx['data']['id']) && !empty($ctx['data']['password']);
    }

    // 現在パスワードが正しいこと。
    public function passwordShouldMatch($val, $ctx) {
        if ( !is_numeric($ctx['data']['id']) ) return true;
        $user = $this->get($ctx['data']['id']);
        return User::passwordMatch($val, $user->password);
    }
}
