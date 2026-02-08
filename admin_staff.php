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
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Manage
                        Patients</a>
                </li>
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
                        style="display: block; padding: 1rem; color: var(--color-primary); background: #eff6ff; border-left: 3px solid var(--color-primary); font-weight: bold;">Manage
                        Staff</a></li>
                <li><a href="dashboard/feedback"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Feedback</a></li>
            </ul>
        </nav>
    </aside>

    <div style="flex: 1;">
        <h2 class="section-title">Staff Management</h2>

        <div class="card">
            <div class="flex justify-between items-center mb-4">
                <form action="dashboard/staff" method="GET" class="flex gap-2">
                    <input type="text" name="q" placeholder="Search Staff..." class="form-input"
                        style="width: 300px;" value="<?= htmlspecialchars($query ?? '') ?>">
                    <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-search"></i></button>
                    <?php if (!empty($query)): ?>
                        <a href="dashboard/staff" class="btn btn-sm" style="background:#ddd; padding:0.4rem;">X</a>
                    <?php endif; ?>
                </form>
                <a href="admin/staff/add" class="btn btn-primary btn-sm">Add New Staff</a>
            </div>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left;">
                        <th style="padding: 1rem;">Name</th>
                        <th style="padding: 1rem;">Role</th>
                        <th style="padding: 1rem;">Department</th>
                        <th style="padding: 1rem;">Contact</th>
                        <th style="padding: 1rem;">Description</th>
                        <th style="padding: 1rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($staff)): ?>
                        <tr>
                            <td colspan="6" class="text-center p-4 text-muted">No staff records found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($staff as $s): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 1rem; font-weight: 500;">
                                    <?= htmlspecialchars($s['name']) ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <?= htmlspecialchars($s['role']) ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <?= htmlspecialchars($s['department'] ?? '-') ?>
                                </td>
                                <td style="padding: 1rem; font-size: 0.9rem;">
                                    <div><i class="fas fa-envelope"></i>
                                        <?= $s['email'] ?>
                                    </div>
                                    <div><i class="fas fa-phone"></i>
                                        <?= $s['phone'] ?>
                                    </div>
                                </td>
                                <td style="padding: 1rem; font-size: 0.9rem; color: #6b7280; max-width: 200px;">
                                    <?= htmlspecialchars($s['description'] ?? '') ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <div class="flex gap-2">
                                        <a href="admin/staff/edit?id=<?= $s['id'] ?>" class="btn btn-secondary btn-sm"
                                            style="padding: 0.25rem 0.5rem;">Edit</a>
                                        <form action="admin/staff/delete" method="POST"
                                            onsubmit="return confirm('Delete this staff member?');">
                                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                            <button type="submit" class="btn btn-sm"
                                                style="padding: 0.25rem 0.5rem; color: #ef4444; border: 1px solid #ef4444; background: white;"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../views/layouts/footer.php'; ?>