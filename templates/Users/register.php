<?php $this->assign('title', 'GUVI | Register'); ?>

<div class="auth-page d-flex align-items-center justify-content-center min-vh-100">
    <div class="card auth-card shadow-sm">
        <div class="card-body p-4 p-md-5">

            <div class="text-center mb-4">
                <svg class="auth-icon" width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12Zm0 2.4c-3.3 0-9.8 1.6-9.8 4.9v2.4h19.6v-2.4c0-3.3-6.5-4.9-9.8-4.9Z" fill="currentColor"/>
                </svg>
                <h1 class="auth-title">Create your account</h1>
            </div>

            <div id="register-alert" class="alert d-none" role="alert"></div>

            <form id="register-form" novalidate>
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" minlength="3" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" minlength="8" required>
                </div>
                <button type="submit" class="btn btn-primary w-100" id="register-submit">Create account</button>
            </form>

            <p class="text-center mt-4 mb-0 auth-footnote">
                Already have an account? <a href="/login">Log in</a>
            </p>
        </div>
    </div>
</div>

<?php $this->append('script'); ?>
<script src="/js/register.js"></script>
<?php $this->end(); ?>
