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
                        style="display: block; padding: 1rem; color: var(--color-primary); background: #eff6ff; border-left: 3px solid var(--color-primary); font-weight: bold;">Overview</a>
                </li>
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
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Manage Staff</a>
                </li>
                <li><a href="dashboard/feedback"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Feedback</a></li>
                <li><a href="admin/report/monthly"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Monthly Report</a>
                </li>

            </ul>
        </nav>
    </aside>

    <div style="flex: 1;">
        <h1 class="section-title">Dashboard Overview</h1>

        <!-- Stats Overview -->
        <div class="grid grid-3" style="gap: 1.5rem; margin-bottom: 3rem;">
            <div class="card flex items-center gap-4"
                style="background: linear-gradient(135deg, #004aad, #528bdf); color: white;">
                <i class="fas fa-users" style="font-size: 3rem; opacity: 0.8;"></i>
                <div>
                    <h3>Total Patients</h3>
                    <p style="font-size: 2rem; font-weight: 700;"><?= $data['total_patients'] ?? 0 ?></p>
                </div>
            </div>
            <div class="card flex items-center gap-4"
                style="background: linear-gradient(135deg, #00b894, #55efc4); color: white;">
                <i class="fas fa-user-md" style="font-size: 3rem; opacity: 0.8;"></i>
                <div>
                    <h3>Total Doctors</h3>
                    <p style="font-size: 2rem; font-weight: 700;"><?= $data['total_doctors'] ?? 0 ?></p>
                </div>
            </div>
            <div class="card flex items-center gap-4"
                style="background: linear-gradient(135deg, #6c5ce7, #a29bfe); color: white;">
                <i class="fas fa-calendar-check" style="font-size: 3rem; opacity: 0.8;"></i>
                <div>
                    <h3>Appointments</h3>
                    <p style="font-size: 2rem; font-weight: 700;"><?= $data['total_appointments'] ?? 0 ?></p>
                </div>
            </div>
        </div>

        <!-- Recent Logs -->
        <h3 style="margin-bottom: 1rem;">System Activity Logs</h3>
        <div class="card" style="margin-bottom: 3rem;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left;">
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0;">Date</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0;">User</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0;">Role</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0;">Action</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0;">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['recent_logs'])): ?>
                        <?php foreach ($data['recent_logs'] as $log): ?>
                            <tr>
                                <td style="padding: 1rem; border-bottom: 1px solid #e2e8f0; color: #64748b;">
                                    <?= date('M d, H:i', strtotime($log['created_at'])) ?>
                                </td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e2e8f0; font-weight: 500;">
                                    <?= htmlspecialchars($log['first_name'] . ' ' . $log['last_name']) ?>
                                </td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e2e8f0;">
                                    <span class="badge"
                                        style="
                                        background: <?= $log['role'] == 'admin' ? '#e2e8f0' : ($log['role'] == 'doctor' ? '#dbeafe' : '#dcfce7') ?>; 
                                        color: <?= $log['role'] == 'admin' ? '#475569' : ($log['role'] == 'doctor' ? '#1e40af' : '#166534') ?>;
                                        padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.85rem; text-transform: capitalize;">
                                        <?= $log['role'] ?>
                                    </span>
                                </td>
                                <td
                                    style="padding: 1rem; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: var(--color-primary);">
                                    <?= htmlspecialchars($log['action']) ?>
                                </td>
                                <td style="padding: 1rem; border-bottom: 1px solid #e2e8f0; color: #64748b;">
                                    <?= htmlspecialchars($log['details']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="padding: 2rem; text-align: center; color: #94a3b8;">No recent activity
                                logs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../views/layouts/footer.php'; ?>