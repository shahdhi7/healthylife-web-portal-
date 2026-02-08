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
                        Patients</a></li>
                <li><a href="dashboard/doctors"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Manage Doctors</a>
                </li>
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
        <h2 class="section-title">User Management</h2>

        <div class="card">
            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <form action="" method="GET" class="flex gap-2">
                        <input type="text" name="q" placeholder="Search by Name or Email..." class="form-input"
                            style="width: 300px;" value="<?= htmlspecialchars($query ?? '') ?>">
                        <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-search"></i></button>
                        <?php if (!empty($query)): ?>
                            <a href="dashboard/users" class="btn btn-sm" style="background:#ddd; padding:0.4rem;">X</a>
                        <?php endif; ?>
                    </form>
                    <a href="admin/user/add" class="btn btn-primary btn-sm">Add New User</a>
                </div>

                <div class="table-responsive">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8fafc; text-align: left;">
                                <th style="padding: 1rem;">ID</th>
                                <th style="padding: 1rem;">Name</th>
                                <th style="padding: 1rem;">Email</th>
                                <th style="padding: 1rem;">Role</th>
                                <th style="padding: 1rem;">Joined</th>
                                <th style="padding: 1rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr style="border-bottom: 1px solid #eee;">
                                    <td style="padding: 1rem;">#<?= $u['id'] ?></td>
                                    <td style="padding: 1rem; font-weight: 500;">
                                        <?= $u['first_name'] . ' ' . $u['last_name'] ?>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <?= $u['email'] ?>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <span class="badge" style="text-transform: capitalize; background: #f3f4f6;">
                                            <?= $u['role'] ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; color: #6b7280; font-size: 0.9rem;">
                                        <?= date('M d, Y', strtotime($u['created_at'])) ?>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <a href="admin/user/edit?id=<?= $u['id'] ?>" class="btn btn-secondary btn-sm"
                                            style="padding: 0.25rem 0.5rem;">Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../views/layouts/footer.php'; ?>