<?php require_once '../views/layouts/header.php'; ?>

<div class="container section-padding">
    <div class="flex flex-wrap justify-center gap-4" style="align-items: flex-start;">

        <!-- Login Form -->
        <div class="card" style="flex: 1; min-width: 300px; max-width: 400px;">
            <h2 class="text-center section-title" style="margin-bottom: 1.5rem;">Sign In</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"
                    style="background: #FECACA; color: #991B1B; padding: 0.75rem; border-radius: var(--radius-sm); margin-bottom: 1rem;">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="login" method="POST" class="flex flex-col gap-4">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" required class="form-input"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #D1D5DB; border-radius: var(--radius-sm);">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required class="form-input"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #D1D5DB; border-radius: var(--radius-sm);">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>

                <p class="text-center text-muted">Don't have an account? <a href="register" class="highlight">Sign
                        Up</a>
                </p>
            </form>
        </div>

        <!-- Demo Credentials Sidebar -->
        <div class="card"
            style="flex: 1; min-width: 300px; max-width: 350px; background: #f0f9ff; border: 1px solid #bae6fd;">
            <h3 style="color: var(--color-primary); margin-bottom: 1rem;">Demo Credentials</h3>
            <p style="margin-bottom: 1rem; font-size: 0.9rem;">Click to copy details for testing:</p>

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div class="p-2 border rounded bg-white">
                    <strong>Admin:</strong><br>
                    <small>Email: admin@healthylife.lk</small><br>
                    <small>Pass: password123</small>
                </div>
                <div class="p-2 border rounded bg-white">
                    <strong>Doctor:</strong><br>
                    <small>Email: aruna@healthylife.lk</small><br>
                    <small>Pass: password123</small>
                </div>
                <div class="p-2 border rounded bg-white">
                    <strong>Receptionist:</strong><br>
                    <small>Email: reception@healthylife.lk</small><br>
                    <small>Pass: password123</small>
                </div>
                <div class="p-2 border rounded bg-white">
                    <strong>Patient:</strong><br>
                    <small>Email: patient@healthylife.lk</small><br>
                    <small>Pass: password123</small>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once '../views/layouts/footer.php'; ?>