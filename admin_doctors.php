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
                        style="display: block; padding: 1rem; color: var(--color-primary); background: #eff6ff; border-left: 3px solid var(--color-primary); font-weight: bold;">Manage
                        Doctors</a></li>
                <li><a href="admin/appointments"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Appointments</a>
                </li>
                <li><a href="admin/specialties"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Specialties</a>
                </li>
                <li><a href="dashboard/staff"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Manage
                        Staff</a></li>
                <li><a href="dashboard/feedback"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Feedback</a></li>
            </ul>
        </nav>
    </aside>

    <div style="flex: 1;">
        <div class="flex justify-between items-center mb-4">
            <h2 class="section-title" style="margin:0;">Doctor Management</h2>
            <a href="admin/doctor/add" class="btn btn-primary btn-sm">Add New Doctor</a>
        </div>

        <div class="card">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left;">
                        <th style="padding: 1rem;">Doctor Name</th>
                        <th style="padding: 1rem;">Specialty</th>
                        <th style="padding: 1rem;">Experience</th>
                        <th style="padding: 1rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($doctors)): ?>
                        <tr>
                            <td colspan="4" class="text-center p-4 text-muted">No doctors found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($doctors as $doc): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 1rem; font-weight: 500;">
                                    Dr.
                                    <?= htmlspecialchars($doc['first_name'] . ' ' . $doc['last_name']) ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <span class="badge" style="background: #eff6ff; color: #1e40af;">
                                        <?= htmlspecialchars($doc['specialty_name']) ?>
                                    </span>
                                </td>
                                <td style="padding: 1rem;">
                                    <?= $doc['experience_years'] ?> Years
                                </td>
                                <td style="padding: 1rem;">
                                    <div class="flex gap-2">
                                        <a href="dashboard/availability?doctor_id=<?= $doc['id'] ?>"
                                            class="btn btn-secondary btn-sm">
                                            <i class="fas fa-clock"></i> Manage Availability
                                        </a>
                                        <a href="admin/doctor/edit?id=<?= $doc['id'] ?>" class="btn btn-secondary btn-sm">Edit
                                            Profile</a>
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