<?php require_once '../views/layouts/header.php'; ?>

<div class="container section-padding flex" style="gap: 2rem; align-items: flex-start;">
    <!-- Sidebar -->
    <aside class="card" style="width: 250px; padding: 0;">
        <div style="padding: 1.5rem; text-align: center; border-bottom: 1px solid #E5E7EB;">
            <div
                style="width: 80px; height: 80px; background: var(--color-secondary); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
                Dr.
            </div>
            <h4>
                <?= $_SESSION['user_name'] ?>
            </h4>
            <p class="text-muted">Doctor</p>
        </div>
        <nav style="padding: 1rem;">
            <ul style="list-style: none;">
                <li style="margin-bottom: 0.5rem;">
                    <a href="dashboard" class="btn <?= ($view === 'schedule') ? 'btn-primary' : 'btn-secondary' ?>"
                        style="width: 100%; text-align: left; background: <?= ($view === 'schedule') ? '' : 'none' ?>; color: <?= ($view === 'schedule') ? '' : 'var(--color-text)' ?>; border: none;"><i
                            class="fas fa-calendar-day"></i> Today's Schedule</a>
                </li>
                <li style="margin-bottom: 0.5rem;">
                    <a href="dashboard?view=history"
                        class="btn <?= ($view === 'history') ? 'btn-primary' : 'btn-secondary' ?>"
                        style="width: 100%; text-align: left; background: <?= ($view === 'history') ? '' : 'none' ?>; color: <?= ($view === 'history') ? '' : 'var(--color-text)' ?>; border: none;"><i
                            class="fas fa-history"></i> Past Appointments</a>
                </li>
                <li style="margin-bottom: 0.5rem;">
                    <a href="dashboard?view=profile"
                        class="btn <?= ($view === 'profile') ? 'btn-primary' : 'btn-secondary' ?>"
                        style="width: 100%; text-align: left; background: <?= ($view === 'profile') ? '' : 'none' ?>; color: <?= ($view === 'profile') ? '' : 'var(--color-text)' ?>; border: none;"><i
                            class="fas fa-user-circle"></i> My Profile</a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div style="flex: 1;">
        <?php if ($view === 'schedule' || $view === 'history'): ?>
            <div class="flex justify-between items-center mb-4">
                <h2 class="section-title" style="margin: 0;">
                    <?= ($view === 'schedule') ? 'Schedule for ' . date('M d, Y', strtotime($date)) : 'Appointment History' ?>
                </h2>
                <div class="text-muted">
                    Showing <?= count($appointments) ?> appointments
                </div>
            </div>

            <?php if (empty($appointments)): ?>
                <div class="card text-center text-muted">
                    <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <p>No appointments found.</p>
                </div>
            <?php else: ?>
                <div class="card" style="padding: 0; overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead style="background: #F9FAFB; border-bottom: 2px solid #E5E7EB;">
                            <tr>
                                <th style="padding: 1rem;">Patient Details</th>
                                <th style="padding: 1rem;">Gender/Age</th>
                                <th style="padding: 1rem;"><?= ($view === 'history') ? 'Date/Time' : 'Time' ?></th>
                                <th style="padding: 1rem;">Status</th>
                                <th style="padding: 1rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appt): ?>
                                <tr style="border-bottom: 1px solid #F3F4F6;">
                                    <td style="padding: 1rem;">
                                        <div style="font-weight: 600; font-size: 1.1rem; color: var(--color-text);">
                                            <?= htmlspecialchars($appt['first_name'] . ' ' . $appt['last_name']) ?>
                                        </div>
                                        <div style="font-size: 0.85rem; color: #6B7280;">
                                            <i class="fas fa-phone-alt"></i> <?= htmlspecialchars($appt['phone'] ?? 'N/A') ?>
                                        </div>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <div style="color: #4B5563;">
                                            <span class="badge" style="background: #F3F4F6; color: #1F2937; padding: 0.2rem 0.6rem; border-radius: 999px;">
                                                <?= htmlspecialchars($appt['gender']) ?>
                                            </span>
                                        </div>
                                        <div style="font-size: 0.9rem; margin-top: 0.3rem;">
                                            <?php 
                                            if ($appt['date_of_birth']) {
                                                echo date_diff(date_create($appt['date_of_birth']), date_create('now'))->y . " years";
                                            } else {
                                                echo "N/A";
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <?php if ($view === 'history'): ?>
                                            <div style="font-weight: 500;"><?= date('M d, Y', strtotime($appt['appointment_date'])) ?></div>
                                        <?php endif; ?>
                                        <div style="color: var(--color-primary); font-weight: 600;">
                                            <?= date('h:i A', strtotime($appt['time_slot'])) ?>
                                        </div>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <span class="badge" style="
                                            padding: 0.3rem 0.8rem; 
                                            border-radius: 4px; 
                                            font-size: 0.85rem; 
                                            text-transform: capitalize;
                                            background: <?= ($appt['status'] === 'completed') ? '#D1FAE5' : (($appt['status'] === 'cancelled') ? '#FEE2E2' : '#EFF6FF') ?>;
                                            color: <?= ($appt['status'] === 'completed') ? '#065F46' : (($appt['status'] === 'cancelled') ? '#991B1B' : '#1E40AF') ?>;
                                        ">
                                            <?= htmlspecialchars($appt['status']) ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <div class="flex gap-2">
                                            <?php if ($appt['status'] !== 'completed' && $appt['status'] !== 'cancelled'): ?>
                                                <form action="appointment/complete" method="POST" style="margin: 0;">
                                                    <input type="hidden" name="appointment_id" value="<?= $appt['id'] ?>">
                                                    <input type="hidden" name="redirect" value="dashboard<?= ($view === 'history') ? '?view=history' : '' ?>">
                                                    <button type="submit" class="btn btn-primary btn-sm" title="Mark as Complete">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if ($appt['status'] !== 'cancelled'): ?>
                                                <button class="btn btn-secondary btn-sm" onclick="showUploadModal(<?= $appt['id'] ?>)" title="Upload Report">
                                                    <i class="fas fa-file-upload"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        <?php elseif ($view === 'profile'): ?>
            <h2 class="section-title">My Profile</h2>
            <div class="card" style="max-width: 600px;">
                <?php if (isset($_GET['success'])): ?>
                    <div style="background: #DEF7EC; color: #03543F; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                        Profile updated successfully!
                    </div>
                <?php endif; ?>

                <form action="dashboard/profile/update" method="POST">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-input" value="<?= $user['first_name'] ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-input" value="<?= $user['last_name'] ?>"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-input" value="<?= $user['email'] ?>" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-input" required>
                                <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                <option value="Other" <?= $user['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-input"
                                value="<?= $user['date_of_birth'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="form-input" value="<?= $user['phone'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-input" rows="3"><?= $user['address'] ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Save Changes</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Upload Modal -->
<div id="upload-modal" class="modal hidden"
    style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1000;">
    <div class="card" style="width: 400px; position: relative; margin: auto;">
        <span onclick="closeModal()"
            style="position: absolute; top: 1rem; right: 1rem; cursor: pointer; font-size: 1.5rem;">&times;</span>
        <h3>Upload Medical Report</h3>
        <form action="appointment/upload" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="appointment_id" id="modal-appt-id">
            <input type="hidden" name="redirect" value="dashboard<?= ($view === 'history') ? '?view=history' : '' ?>">
            <div class="form-group mb-4">
                <label>Select File (PDF/PNG)</label>
                <input type="file" name="report_file" required class="form-input" accept=".pdf,.png,.jpg">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Upload</button>
        </form>
    </div>
</div>

<script>
    function showUploadModal(id) {
        document.getElementById('modal-appt-id').value = id;
        document.getElementById('upload-modal').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('upload-modal').style.display = 'none';
    }
</script>

<?php require_once '../views/layouts/footer.php'; ?>