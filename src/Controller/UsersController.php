<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\MongoService;
use App\Service\RedisService;
use Cake\Controller\Controller;

/**
 * UsersController
 *
 * Two kinds of actions:
 *  1. Page actions (register/login/dashboard) — render the Bootstrap
 *     shell only. They contain NO business logic and NO PHP session
 *     usage; they just hand a static page to the browser.
 *  2. API actions (apiRegister/apiLogin/apiLogout/apiDashboard) —
 *     called exclusively via jQuery $.ajax(). They return JSON only
 *     and never render a view.
 *
 * Auth model: browser holds an opaque token in localStorage. Every
 * API action that needs identity reads it from the X-Auth-Token
 * request header and resolves it via Redis. No $_SESSION anywhere.
 */
class UsersController extends Controller
{
    private RedisService $redis;
    private MongoService $mongo;

    public function initialize(): void
    {
        parent::initialize();
        // Note: Cake's Authentication plugin is intentionally not used —
        // identity is resolved manually via Redis (see resolveToken()).
        $this->redis = new RedisService();
        $this->mongo = new MongoService();

        // Page actions render templates; API actions render JSON and
        // skip the layout entirely.
        $apiActions = ['apiRegister', 'apiLogin', 'apiLogout', 'apiDashboard'];
        if (in_array($this->request->getParam('action'), $apiActions, true)) {
            $this->viewBuilder()->setClassName('Json');
        }
    }

    // -----------------------------------------------------------------
    // PAGE ACTIONS — just deliver the shell. No logic, no session.
    // -----------------------------------------------------------------

    public function register(): void
    {
        // Intentionally empty — templates/Users/register.php is static markup.
    }

    public function login(): void
    {
        // Intentionally empty — templates/Users/login.php is static markup.
    }

    public function dashboard(): void
    {
        // Intentionally empty. The page itself contains no user data;
        // dashboard.js fetches it via apiDashboard() after checking
        // localStorage for a token.
    }

    // -----------------------------------------------------------------
    // API ACTIONS — JSON in, JSON out, called only via jQuery AJAX.
    // -----------------------------------------------------------------

    /**
     * POST /users/api-register
     * Body (JSON): { username, email, password, full_name }
     */
    public function apiRegister(): void
    {
        $this->request->allowMethod(['post']);
        $data = $this->request->getData();

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->newEmptyEntity();

        $passwordHash = password_hash((string)($data['password'] ?? ''), PASSWORD_BCRYPT);

        $user = $usersTable->patchEntity($user, [
            'username' => $data['username'] ?? null,
            'email' => $data['email'] ?? null,
            'password_hash' => $passwordHash,
        ]);

        if ($user->getErrors()) {
            $this->response = $this->response->withStatus(422);
            $this->set([
                'success' => false,
                'errors' => $user->getErrors(),
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
            return;
        }

        $saved = $usersTable->save($user); // ORM save => prepared statement insert

        if (!$saved) {
            $this->response = $this->response->withStatus(500);
            $this->set([
                'success' => false,
                'errors' => ['general' => 'Could not create account. Please try again.'],
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
            return;
        }

        $fullName = trim((string)($data['full_name'] ?? $data['username'] ?? ''));
        $this->mongo->createProfile((string)$saved->id, $fullName !== '' ? $fullName : (string)$saved->username);

        $this->set([
            'success' => true,
            'message' => 'Account created. You can now log in.',
        ]);
        $this->viewBuilder()->setOption('serialize', ['success', 'message']);
    }

    /**
     * POST /users/api-login
     * Body (JSON): { username, password }
     * Response: { success, token } on success.
     */
    public function apiLogin(): void
    {
        $this->request->allowMethod(['post']);
        $data = $this->request->getData();

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->findByUsernameForLogin((string)($data['username'] ?? ''));

        $valid = $user !== null && password_verify((string)($data['password'] ?? ''), $user->password_hash);

        if (!$valid) {
            $this->response = $this->response->withStatus(401);
            $this->set([
                'success' => false,
                'errors' => ['general' => 'Invalid username or password.'],
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
            return;
        }

        $token = $this->redis->issueToken((string)$user->id);

        $this->set([
            'success' => true,
            'token' => $token,
        ]);
        $this->viewBuilder()->setOption('serialize', ['success', 'token']);
    }

    /**
     * POST /users/api-logout
     * Header: X-Auth-Token
     */
    public function apiLogout(): void
    {
        $this->request->allowMethod(['post']);
        $token = $this->request->getHeaderLine('X-Auth-Token');

        if ($token !== '') {
            $this->redis->revokeToken($token);
        }

        $this->set(['success' => true]);
        $this->viewBuilder()->setOption('serialize', ['success']);
    }

    /**
     * GET /users/api-dashboard
     * Header: X-Auth-Token
     * Response: { success, username, full_name } on valid token.
     */
     public function apiDashboard(): void
    {
        $this->request->allowMethod(['get']);
        $token = $this->request->getHeaderLine('X-Auth-Token');
        $userId = $this->redis->resolveToken($token);

        if ($userId === null) {
            $this->response = $this->response->withStatus(401);
            $this->set([
                'success' => false,
                'errors' => ['general' => 'Session expired or invalid. Please log in again.'],
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
            return;
        }

        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->find()
            ->select(['id', 'username', 'email', 'created_at', 'modified_at'])
            ->where(['id' => $userId])
            ->first();

        if ($user === null) {
            $this->response = $this->response->withStatus(401);
            $this->set([
                'success' => false,
                'errors' => ['general' => 'Account no longer exists.'],
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
            return;
        }

        $profile = $this->mongo->findProfileByUserId((string)$userId);

        $this->set([
            'success' => true,
            'mysql' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'created_at' => $user->created_at?->toIso8601String(),
                'modified_at' => $user->modified_at?->toIso8601String(),
            ],
            'mongo' => [
                'full_name' => $profile['full_name'] ?? $user->username,
                'created_at' => $profile['created_at'] ?? null,
            ],
        ]);
        $this->viewBuilder()->setOption('serialize', ['success', 'mysql', 'mongo']);
    }
}
