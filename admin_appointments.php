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
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Manage Users</a>
                </li>
                <li><a href="admin/appointments"
                        style="display: block; padding: 1rem; color: var(--color-primary); background: #eff6ff; border-left: 3px solid var(--color-primary); font-weight: bold;">Appointments</a>
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
        <h2 class="section-title">All Appointments</h2>

        <div class="card">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left;">
                        <th style="padding: 0.75rem;">ID</th>
                        <th style="padding: 0.75rem;">Patient</th>
                        <th style="padding: 0.75rem;">Doctor</th>
                        <th style="padding: 0.75rem;">Date/Time</th>
                        <th style="padding: 0.75rem;">Status/Room</th>
                        <th style="padding: 0.75rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $a): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 0.75rem;">#
                                <?= $a['id'] ?>
                            </td>
                            <td style="padding: 0.75rem;">
                                <strong>
                                    <?= $a['p_fname'] . ' ' . $a['p_lname'] ?>
                                </strong><br>
                                <span class="text-muted">
                                    <?= $a['p_email'] ?>
                                </span>
                            </td>
                            <td style="padding: 0.75rem;">
                                Dr.
                                <?= $a['d_fname'] . ' ' . $a['d_lname'] ?><br>
                                <small>
                                    <?= $a['specialty'] ?>
                                </small>
                            </td>
                            <td style="padding: 0.75rem;">
                                <?= $a['appointment_date'] ?><br>
                                <?= $a['time_slot'] ?>
                            </td>
                            <td style="padding: 0.75rem;">
                                <span class="badge" style="background: #f3f4f6;">
                                    <?= $a['status'] ?>
                                </span>
                                <?php if ($a['room_number']): ?>
                                    <div style="margin-top:0.25rem; font-weight:bold; color: var(--color-primary);">Room:
                                        <?= $a['room_number'] ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 0.75rem;">
                                <?php if ($a['status'] === 'pending' || $a['status'] === 'confirmed'): ?>
                                    <form action="admin/appointment/cancellation" method="POST"
                                        onsubmit="return confirm('Cancel this appointment?');">
                                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                        <button type="submit" class="btn btn-sm"
                                            style="color: #f97316; border: 1px solid #f97316; background: white;">Cancellation</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../views/layouts/footer.php'; ?>