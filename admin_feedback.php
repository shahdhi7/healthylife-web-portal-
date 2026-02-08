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
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Manage Staff</a>
                </li>
                <li><a href="dashboard/feedback"
                        style="display: block; padding: 1rem; color: var(--color-primary); background: #eff6ff; border-left: 3px solid var(--color-primary); font-weight: bold;">Feedback</a>
                </li>
            </ul>
        </nav>
    </aside>

    <div style="flex: 1;">
        <h2 class="section-title">Feedback & Inquiries</h2>

        <div class="flex flex-col gap-4">
            <?php if (empty($feedbacks)): ?>
                <div class="card text-center text-muted">No feedback messages found.</div>
            <?php else: ?>
                <?php foreach ($feedbacks as $fb): ?>
                    <div class="card"
                        style="border-left: 4px solid <?= $fb['status'] == 'new' ? 'var(--color-warning)' : 'var(--color-success)' ?>;">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 style="margin:0;">
                                    <?= htmlspecialchars($fb['subject'] ?? 'No Subject') ?>
                                </h4>
                                <p class="text-muted" style="margin: 0.25rem 0;">From: <strong>
                                        <?= htmlspecialchars($fb['name']) ?>
                                    </strong> (
                                    <?= $fb['email'] ?>)
                                </p>
                                <small class="text-muted">
                                    <?= date('M d, Y h:i A', strtotime($fb['created_at'])) ?>
                                </small>
                            </div>
                            <span class="badge" style="text-transform: capitalize; background: #f3f4f6;">
                                <?= $fb['status'] ?>
                            </span>
                        </div>

                        <div style="margin-top: 1rem; padding: 1rem; background: #f9fafb; border-radius: 8px;">
                            <?= nl2br(htmlspecialchars($fb['message'])) ?>
                        </div>

                        <?php if ($fb['status'] == 'new'): ?>
                            <div style="margin-top: 1rem;">
                                <form action="dashboard/feedback/reply" method="POST">
                                    <input type="hidden" name="id" value="<?= $fb['id'] ?>">
                                    <textarea name="response" placeholder="Type your reply here..." class="form-input"
                                        style="width: 100%; height: 80px; margin-bottom: 0.5rem;" required></textarea>
                                    <button type="submit" class="btn btn-primary btn-sm">Send Reply</button>
                                </form>
                            </div>
                        <?php elseif ($fb['response']): ?>
                            <div style="margin-top: 1rem; padding-left: 1rem; border-left: 2px solid var(--color-success);">
                                <strong>Admin Response:</strong>
                                <p>
                                    <?= nl2br(htmlspecialchars($fb['response'])) ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../views/layouts/footer.php'; ?>