<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Table (MySQL).
 *
 * IMPORTANT: every query method here uses CakePHP's Query Builder
 * (find(), select(), where() with array conditions, etc.). The
 * Query Builder ALWAYS binds parameters internally — i.e. every
 * query below compiles to a prepared statement. Never replace these
 * with $connection->execute("...$var...") string concatenation.
 */
class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('users');
        $this->setPrimaryKey('id');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('username')
            ->minLength('username', 3)
            ->maxLength('username', 50)
            ->requirePresence('username', 'create')
            ->notEmptyString('username')
            ->add('username', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'That username is already taken.',
            ])

            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email')
            ->add('email', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => 'That email is already registered.',
            ])

            ->scalar('password_hash')
            ->minLength('password_hash', 60) // bcrypt hash length
            ->requirePresence('password_hash', 'create')
            ->notEmptyString('password_hash');

        return $validator;
    }

    /**
     * Find a single active user by username — used at login.
     * Built entirely with the Query Builder => prepared statement.
     */
    public function findByUsernameForLogin(string $username): ?\App\Model\Entity\User
    {
        return $this->find()
            ->select(['id', 'username', 'email', 'password_hash'])
            ->where(['username' => $username])
            ->first();
    }
}
