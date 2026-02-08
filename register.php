<?php require_once '../views/layouts/header.php'; ?>

<div class="container section-padding flex justify-center">
    <div class="card" style="max-width: 500px; width: 100%;">
        <h2 class="text-center section-title" style="margin-bottom: 1.5rem;">Patient Registration</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"
                style="background: #FECACA; color: #991B1B; padding: 0.75rem; border-radius: var(--radius-sm); margin-bottom: 1rem;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="register" method="POST" class="flex flex-col gap-4">
            <div class="flex gap-4">
                <div class="form-group" style="flex: 1;">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" required class="form-input"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #D1D5DB; border-radius: var(--radius-sm);">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" required class="form-input"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #D1D5DB; border-radius: var(--radius-sm);">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required class="form-input"
                    style="width: 100%; padding: 0.75rem; border: 1px solid #D1D5DB; border-radius: var(--radius-sm);">
            </div>

            <div class="flex gap-4">
                <div class="form-group" style="flex: 1;">
                    <label for="dob">Date of Birth</label>
                    <input type="date" name="dob" id="dob" required class="form-input"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #D1D5DB; border-radius: var(--radius-sm);">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" required class="form-input"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #D1D5DB; border-radius: var(--radius-sm);">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required class="form-input"
                    style="width: 100%; padding: 0.75rem; border: 1px solid #D1D5DB; border-radius: var(--radius-sm);">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>

            <p class="text-center text-muted">Already have an account? <a href="login" class="highlight">Sign In</a></p>
        </form>
    </div>
</div>

<?php require_once '../views/layouts/footer.php'; ?>