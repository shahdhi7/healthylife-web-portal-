<?php require_once '../views/layouts/header.php'; ?>

<div class="container section-padding flex" style="gap: 2rem; align-items: flex-start;">
    <!-- Sidebar -->
    <aside class="card" style="width: 250px; padding: 0;">
        <div style="padding: 1.5rem; text-align: center; border-bottom: 1px solid #E5E7EB;">
            <div
                style="width: 80px; height: 80px; background: var(--color-primary-light); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white;">
                <?= substr($_SESSION['user_name'], 0, 1) ?>
            </div>
            <h4>
                <?= $_SESSION['user_name'] ?>
            </h4>
            <p class="text-muted">Patient</p>
        </div>
        <nav style="padding: 1rem;">
            <ul style="list-style: none;">
                <li style="margin-bottom: 0.5rem;">
                    <a href="dashboard?view=appointments"
                        class="btn <?= $view === 'appointments' ? 'btn-primary' : 'btn-secondary' ?>"
                        style="width: 100%; text-align: left; background: <?= $view === 'appointments' ? '' : 'none' ?>; color: <?= $view === 'appointments' ? '' : 'var(--color-text)' ?>; border: <?= $view === 'appointments' ? '' : 'none' ?>;">
                        <i class="fas fa-calendar-alt"></i> Appointments
                    </a>
                </li>
                <li style="margin-bottom: 0.5rem;">
                    <a href="dashboard?view=reports"
                        class="btn <?= $view === 'reports' ? 'btn-primary' : 'btn-secondary' ?>"
                        style="width: 100%; text-align: left; background: <?= $view === 'reports' ? '' : 'none' ?>; color: <?= $view === 'reports' ? '' : 'var(--color-text)' ?>; border: <?= $view === 'reports' ? '' : 'none' ?>;">
                        <i class="fas fa-file-medical"></i> Medical Reports
                    </a>
                </li>
                <li style="margin-bottom: 0.5rem;">
                    <a href="dashboard?view=payments"
                        class="btn <?= $view === 'payments' ? 'btn-primary' : 'btn-secondary' ?>"
                        style="width: 100%; text-align: left; background: <?= $view === 'payments' ? '' : 'none' ?>; color: <?= $view === 'payments' ? '' : 'var(--color-text)' ?>; border: <?= $view === 'payments' ? '' : 'none' ?>;">
                        <i class="fas fa-file-invoice-dollar"></i> Payments
                    </a>
                </li>
                <li style="margin-bottom: 0.5rem;">
                    <a href="dashboard?view=profile"
                        class="btn <?= $view === 'profile' ? 'btn-primary' : 'btn-secondary' ?>"
                        style="width: 100%; text-align: left; background: <?= $view === 'profile' ? '' : 'none' ?>; color: <?= $view === 'profile' ? '' : 'var(--color-text)' ?>; border: <?= $view === 'profile' ? '' : 'none' ?>;">
                        <i class="fas fa-user-edit"></i> My Profile
                    </a>
                </li>
                <li style="margin-bottom: 0.5rem;">
                    <a href="dashboard?view=inquiries"
                        class="btn <?= $view === 'inquiries' ? 'btn-primary' : 'btn-secondary' ?>"
                        style="width: 100%; text-align: left; background: <?= $view === 'inquiries' ? '' : 'none' ?>; color: <?= $view === 'inquiries' ? '' : 'var(--color-text)' ?>; border: <?= $view === 'inquiries' ? '' : 'none' ?>;">
                        <i class="fas fa-question-circle"></i> My Inquiries
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div style="flex: 1;">

        <?php if ($view === 'appointments'): ?>
            <div class="flex justify-between items-center mb-6">
                <h2 class="section-title" style="margin: 0;">My Appointments</h2>
                <a href="doctors" class="btn btn-primary">Book New Appointment</a>
            </div>

            <?php if (empty($appointments)): ?>
                <div class="card text-center text-muted" style="padding: 4rem 2rem;">
                    <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                    <p>You have no appointments yet.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-2 gap-4">
                    <?php foreach ($appointments as $appt): ?>
                        <div class="card"
                            style="border-left: 5px solid <?= $appt['status'] == 'confirmed' ? 'var(--color-success)' : ($appt['status'] == 'completed' ? 'var(--color-primary)' : 'var(--color-warning)') ?>;">
                            <div class="flex justify-between items-center mb-4">
                                <h4 style="margin: 0;">
                                    <?= date('M d, Y', strtotime($appt['appointment_date'])) ?>
                                </h4>
                                <span class="badge"
                                    style="background: #F3F4F6; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem; text-transform: uppercase;">
                                    <?= $appt['status'] ?>
                                </span>
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                <i class="fas fa-clock text-primary"></i> <strong>Time:</strong>
                                <?= date('h:i A', strtotime($appt['time_slot'])) ?>
                            </div>
                            <div style="margin-bottom: 0.5rem;">
                                <i class="fas fa-user-md text-primary"></i> <strong>Doctor:</strong> Dr.
                                <?= $appt['doc_fname'] . ' ' . $appt['doc_lname'] ?>
                            </div>
                            <div style="margin-bottom: 1rem;">
                                <i class="fas fa-stethoscope text-primary"></i> <strong>Specialty:</strong>
                                <?= $appt['specialty'] ?>
                            </div>

                            <?php if (!empty($appt['room_number'])): ?>
                                <div
                                    style="margin-bottom: 1rem; padding: 0.5rem; background: var(--color-primary-light); color: white; border-radius: 4px; font-size: 0.9rem;">
                                    <i class="fas fa-door-open"></i> <strong>Room:</strong> <?= $appt['room_number'] ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($appt['status'] == 'completed'): ?>
                                <a href="dashboard?view=reports" class="btn btn-secondary"
                                    style="width: 100%; font-size: 0.8rem; padding: 0.5rem; display: inline-block; text-align: center;">View
                                    Medical Report</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php elseif ($view === 'reports'): ?>
            <h2 class="section-title">Medical Reports</h2>
            <?php if (empty($reports)): ?>
                <div class="card text-center text-muted" style="padding: 4rem 2rem;">
                    <i class="fas fa-file-medical-alt" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                    <p>No medical reports available yet.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-1 gap-4">
                    <?php foreach ($reports as $report): ?>
                        <div class="card flex justify-between items-center">
                            <div>
                                <h4 style="margin: 0;"><?= $report['file_name'] ?></h4>
                                <p class="text-muted" style="margin: 0.25rem 0;">Doctor: Dr.
                                    <?= $report['doc_fname'] . ' ' . $report['doc_lname'] ?>
                                </p>
                                <small class="text-muted">Uploaded: <?= date('M d, Y', strtotime($report['uploaded_at'])) ?></small>
                                <?php if (!empty($report['description'])): ?>
                                    <p style="margin-top: 0.5rem; font-size: 0.9rem;"><?= $report['description'] ?></p>
                                <?php endif; ?>
                            </div>
                            <a href="<?= $report['file_path'] ?>" class="btn btn-primary" download>
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php elseif ($view === 'payments'): ?>
            <h2 class="section-title">Payment History</h2>
            <?php if (empty($payments)): ?>
                <div class="card text-center text-muted" style="padding: 4rem 2rem;">
                    <i class="fas fa-receipt" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                    <p>No payment records found.</p>
                </div>
            <?php else: ?>
                <div class="card" style="padding: 0; overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead style="background: #F9FAFB; border-bottom: 1px solid #E5E7EB;">
                            <tr>
                                <th style="padding: 1rem;">Date</th>
                                <th style="padding: 1rem;">Doctor</th>
                                <th style="padding: 1rem;">Amount</th>
                                <th style="padding: 1rem;">Method</th>
                                <th style="padding: 1rem;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                                <tr style="border-bottom: 1px solid #F3F4F6;">
                                    <td style="padding: 1rem;"><?= date('M d, Y', strtotime($payment['appointment_date'])) ?></td>
                                    <td style="padding: 1rem;">Dr. <?= $payment['doc_fname'] . ' ' . $payment['doc_lname'] ?></td>
                                    <td style="padding: 1rem;">$<?= number_format($payment['amount'], 2) ?></td>
                                    <td style="padding: 1rem; text-transform: capitalize;">
                                        <?= str_replace('_', ' ', $payment['payment_method']) ?>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <span class="badge"
                                            style="background: <?= $payment['status'] == 'paid' ? '#DEF7EC; color: #03543F' : '#FDE8E8; color: #9B1C1C' ?>; padding: 0.25rem 0.5rem; border-radius: 4px;">
                                            <?= ucfirst($payment['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        <?php elseif ($view === 'profile'): ?>
            <h2 class="section-title">My Profile</h2>
            <?php if (isset($_GET['success'])): ?>
                <div style="background: #DEF7EC; color: #03543F; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    Profile updated successfully!
                </div>
            <?php endif; ?>
            <div class="card">
                <form action="dashboard/profile/update" method="POST">
                    <div class="grid grid-2 gap-4">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?= $user['first_name'] ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?= $user['last_name'] ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?= $user['phone'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender" class="form-control" required>
                                <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                <option value="Other" <?= $user['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="date_of_birth" class="form-control"
                                value="<?= $user['date_of_birth'] ?>" required>
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Address</label>
                            <textarea name="address" class="form-control" rows="3"><?= $user['address'] ?></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Update Profile</button>
                </form>
            </div>
        <?php elseif ($view === 'inquiries'): ?>
            <h2 class="section-title">My Inquiries & Feedback</h2>
            <div class="flex flex-col gap-4">
                <?php if (empty($feedbacks)): ?>
                    <div class="card text-center text-muted">You haven't sent any inquiries yet.</div>
                <?php else: ?>
                    <?php foreach ($feedbacks as $fb): ?>
                        <div class="card"
                            style="border-left: 4px solid <?= $fb['status'] == 'new' ? 'var(--color-warning)' : 'var(--color-success)' ?>;">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 style="margin:0;">
                                        <?= htmlspecialchars($fb['subject'] ?? 'No Subject') ?>
                                    </h4>
                                    <small class="text-muted">
                                        Sent on: <?= date('M d, Y h:i A', strtotime($fb['created_at'])) ?>
                                    </small>
                                </div>
                                <span class="badge" style="text-transform: capitalize; background: #f3f4f6;">
                                    <?= $fb['status'] ?>
                                </span>
                            </div>

                            <div style="margin-top: 1rem; padding: 1rem; background: #f9fafb; border-radius: 8px; font-size: 0.9rem;">
                                <strong>My Message:</strong><br>
                                <?= nl2br(htmlspecialchars($fb['message'])) ?>
                            </div>

                            <?php if ($fb['response']): ?>
                                <div style="margin-top: 1rem; padding: 1rem; border-left: 2px solid var(--color-success); background: #f0fdf4; border-radius: 4px;">
                                    <strong>Admin Response:</strong>
                                    <p style="margin-top:0.5rem; font-size: 0.95rem;">
                                        <?= nl2br(htmlspecialchars($fb['response'])) ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <div style="margin-top: 1rem; color: #6b7280; font-size: 0.85rem; font-style: italic;">
                                    Waiting for administrative response...
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../views/layouts/footer.php'; ?>