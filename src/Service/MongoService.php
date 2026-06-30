<?php
declare(strict_types=1);

namespace App\Service;

use Cake\Core\Configure;
use MongoDB\Client;
use MongoDB\Collection;

/**
 * MongoService
 *
 * Wraps all MongoDB access for user PROFILE documents.
 * MySQL owns credentials; Mongo owns everything about the *person*
 * (full name today, easy to extend with bio/avatar/preferences later
 * without a MySQL migration — that schema flexibility is the reason
 * this data lives here instead of in the users table).
 *
 * Profile documents look like:
 * {
 *   "user_id": "14",        // string form of MySQL users.id
 *   "full_name": "Jane Doe",
 *   "created_at": ISODate
 * }
 */
class MongoService
{
    private Collection $profiles;

    public function __construct()
    {
        $config = Configure::read('Mongo');
        $client = new Client($config['uri']);
        $db = $client->selectDatabase($config['database']);
        $this->profiles = $db->selectCollection($config['profilesCollection']);
    }

    public function createProfile(string $userId, string $fullName): void
    {
        $this->profiles->insertOne([
            'user_id' => $userId,
            'full_name' => $fullName,
            'created_at' => new \MongoDB\BSON\UTCDateTime(),
        ]);
    }

    public function findFullNameByUserId(string $userId): ?string
    {
        $doc = $this->profiles->findOne(['user_id' => $userId]);

        if ($doc === null) {
            return null;
        }

        return $doc['full_name'] ?? null;
    }

    public function findProfileByUserId(string $userId): ?array
    {
        $doc = $this->profiles->findOne(['user_id' => $userId]);

        if ($doc === null) {
            return null;
        }

        $createdAt = null;
        if (isset($doc['created_at']) && $doc['created_at'] instanceof \MongoDB\BSON\UTCDateTime) {
            $createdAt = $doc['created_at']->toDateTime()->format(\DateTime::ATOM);
        }

        return [
            'full_name' => $doc['full_name'] ?? null,
            'created_at' => $createdAt,
        ];
    }
}
