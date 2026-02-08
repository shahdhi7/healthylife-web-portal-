<?php require_once '../views/layouts/header.php'; ?>

<div class="container section-padding flex" style="gap: 2rem; align-items: flex-start;">
    <!-- Simple Sidebar -->
    <aside class="card" style="width: 250px; padding: 0; position: sticky; top: 2rem;">
        <div style="padding: 1rem; border-bottom: 1px solid #eee;">
            <h4 style="margin:0;">Admin Panel</h4>
        </div>
        <nav>
            <ul style="list-style: none;">
                <li><a href="dashboard"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Overview</a></li>
                <li><a href="dashboard/users"
                        style="display: block; padding: 1rem; color: var(--color-primary); background: #eff6ff; border-left: 3px solid var(--color-primary); font-weight: bold;">Manage
                        Users</a></li>
                <li><a href="admin/appointments"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Appointments</a>
                </li>
                <li><a href="admin/specialties"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Specialties</a>
                </li>
                <li><a href="dashboard/staff"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Manage Staff</a>
                </li>
                <li><a href="dashboard/feedback"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Feedback</a></li>
            </ul>
        </nav>
    </aside>

    <div style="flex: 1;">
        <h2 class="section-title">Edit User #<?= htmlspecialchars($user['id']) ?></h2>

        <div class="card" style="max-width: 800px;">
            <form action="admin/user/update" method="POST">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                <div class="grid grid-2 gap-4">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-input"
                            value="<?= htmlspecialchars($user['first_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-input"
                            value="<?= htmlspecialchars($user['last_name']) ?>" required>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-input" value="<?= htmlspecialchars($user['email']) ?>"
                        required style="width: 100%;">
                </div>

                <!-- Password Field Optional on Edit -->
                <div class="form-group mt-2">
                    <label>Password (Leave blank to keep current)</label>
                    <input type="password" name="password" class="form-input" placeholder="New Password"
                        style="width: 100%;">
                </div>

                <div class="grid grid-2 gap-4 mt-2">
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-input" style="width: 100%;">
                            <option value="patient" <?= $user['role'] == 'patient' ? 'selected' : '' ?>>Patient</option>
                            <option value="doctor" <?= $user['role'] == 'doctor' ? 'selected' : '' ?>>Doctor</option>
                            <option value="receptionist" <?= $user['role'] == 'receptionist' ? 'selected' : '' ?>>
                                Receptionist</option>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-input" style="width: 100%;">
                            <option value="Male" <?= ($user['gender'] ?? '') == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= ($user['gender'] ?? '') == 'Female' ? 'selected' : '' ?>>Female
                            </option>
                            <option value="Other" <?= ($user['gender'] ?? '') == 'Other' ? 'selected' : '' ?>>Other
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group mt-2">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" class="form-input" value="<?= $user['date_of_birth'] ?? '' ?>"
                        required style="width: 100%;">
                </div>

                <div class="flex gap-4 mt-4">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="../../dashboard/users" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../views/layouts/footer.php'; ?>