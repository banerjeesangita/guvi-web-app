<?php $this->assign('title', 'GUVI | Log In'); ?>

<div class="auth-page d-flex align-items-center justify-content-center min-vh-100">
    <div class="card auth-card shadow-sm">
        <div class="card-body p-4 p-md-5">

            <div class="text-center mb-4">
                <svg class="auth-icon" width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M10 17v-3H3v-4h7V7l5 5-5 5Zm9 3H12v-2h7V4h-7V2h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2Z" fill="currentColor"/>
                </svg>
                <h1 class="auth-title">Welcome back</h1>
            </div>

            <div id="login-alert" class="alert d-none" role="alert"></div>

            <form id="login-form" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100" id="login-submit">Log in</button>
            </form>

            <p class="text-center mt-4 mb-0 auth-footnote">
                Don't have an account? <a href="/register">Register</a>
            </p>
        </div>
    </div>
</div>

<?php $this->append('script'); ?>
<script src="/js/login.js"></script>
<?php $this->end(); ?>
