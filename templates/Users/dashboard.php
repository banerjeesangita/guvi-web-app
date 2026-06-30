<?php $this->assign('title', 'GUVI | Dashboard'); ?>

<nav class="navbar navbar-expand bg-white border-bottom">
    <div class="container">
        <span class="navbar-brand auth-title mb-0">Dashboard</span>
        <button id="logout-btn" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M16 17v-3H9v-4h7V7l5 5-5 5ZM14 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h9v2H5v14h9v2Z" fill="currentColor"/>
            </svg>
            Log out
        </button>
    </div>
</nav>

<div class="container py-5">
    <div id="dashboard-loading" class="text-center text-muted">Loading your dashboard...</div>

    <div id="dashboard-content" class="d-none">
        <h1 class="auth-title mb-2">Hello, <span id="dashboard-username"></span></h1>
        <p class="text-muted mb-4">Welcome back to your dashboard.</p>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="auth-title h5 mb-3">Account details</h2>
                        <dl class="row mb-0">
                            <dt class="col-5 text-muted">User ID</dt>
                            <dd class="col-7" id="mysql-id"></dd>

                            <dt class="col-5 text-muted">Username</dt>
                            <dd class="col-7" id="mysql-username"></dd>

                            <dt class="col-5 text-muted">Email</dt>
                            <dd class="col-7" id="mysql-email"></dd>

                            <dt class="col-5 text-muted">Created</dt>
                            <dd class="col-7" id="mysql-created"></dd>

                            <dt class="col-5 text-muted">Last updated</dt>
                            <dd class="col-7" id="mysql-modified"></dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h2 class="auth-title h5 mb-3">Profile details</h2>
                        <dl class="row mb-0">
                            <dt class="col-5 text-muted">Full name</dt>
                            <dd class="col-7" id="mongo-fullname"></dd>

                            <dt class="col-5 text-muted">Profile created</dt>
                            <dd class="col-7" id="mongo-created"></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>	

    <div id="dashboard-error" class="alert alert-danger d-none" role="alert"></div>
</div>

<?php $this->append('script'); ?>
<script src="/js/dashboard.js"></script>
<?php $this->end(); ?>
