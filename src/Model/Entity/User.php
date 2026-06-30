<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * User Entity (MySQL row).
 */
class User extends Entity
{
    protected array $_accessible = [
        'username' => true,
        'email' => true,
        'password_hash' => true,
        'created_at' => false,
        'modified_at' => false,
    ];

    // Never serialize the hash into any JSON response.
    protected array $_hidden = [
        'password_hash',
    ];
}
