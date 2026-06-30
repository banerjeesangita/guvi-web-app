<?php
declare(strict_types=1);

namespace App\Service;

use Cake\Core\Configure;
use Predis\Client;

/**
 * RedisService
 *
 * Backend store for the auth token model requested:
 *   - Client (browser) keeps the opaque token in localStorage.
 *   - Server keeps "auth_token:<token>" => user_id in Redis with a TTL.
 *   - Every authenticated AJAX request sends the token via the
 *     X-Auth-Token header; this service resolves it back to a user_id.
 *
 * No PHP $_SESSION is used anywhere in this app — this class (plus
 * the browser's localStorage) is the entire session mechanism.
 *
 * Security note (flagged to the developer, not hidden): because the
 * token sits in localStorage rather than an HttpOnly cookie, it is
 * readable by any JS that runs on the page, including injected via
 * XSS. This is a standard trade-off for token-based/SPA-style auth;
 * mitigate with strict output escaping, a tight CSP, and short TTLs.
 */
class RedisService
{
    private Client $client;
    private string $keyPrefix;
    private int $tokenTtl;

    public function __construct()
    {
        $config = Configure::read('Redis');

        $this->client = new Client([
            'scheme' => 'tcp',
            'host' => $config['host'],
            'port' => $config['port'],
            'password' => $config['password'] ?? null,
        ]);

        $this->keyPrefix = $config['keyPrefix'];
        $this->tokenTtl = (int)$config['tokenTtl'];
    }

    /**
     * Issue a new token for a user and store it in Redis with a TTL.
     */
    public function issueToken(string $userId): string
    {
        $token = bin2hex(random_bytes(32)); // 64-char opaque token

        $this->client->set($this->keyPrefix . $token, $userId, 'EX', $this->tokenTtl);

        return $token;
    }

    /**
     * Resolve a token to a user_id, or null if missing/expired.
     */
    public function resolveToken(?string $token): ?string
    {
        if ($token === null || $token === '') {
            return null;
        }

        $userId = $this->client->get($this->keyPrefix . $token);

        return $userId ?: null;
    }

    /**
     * Revoke a token immediately (logout).
     */
    public function revokeToken(string $token): void
    {
        $this->client->del([$this->keyPrefix . $token]);
    }
}
