<?php require_once '../views/layouts/header.php'; ?>

<div class="container section-padding flex" style="gap: 2rem; align-items: flex-start;">
    <?php if (!$is_admin): ?>
        <!-- Doctor Sidebar (Only if NOT admin) -->
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
                        <a href="dashboard" class="btn btn-secondary"
                            style="width: 100%; text-align: left; background: none; color: var(--color-text); border: none;"><i
                                class="fas fa-calendar-day"></i> Schedule</a>
                    </li>
                    <li style="margin-bottom: 0.5rem;">
                        <a href="dashboard?view=profile" class="btn btn-secondary"
                            style="width: 100%; text-align: left; background: none; color: var(--color-text); border: none;"><i
                                class="fas fa-user-circle"></i> My Profile</a>
                    </li>
                </ul>
            </nav>
        </aside>
    <?php else: ?>
        <!-- Admin Back Link -->
        <aside class="card" style="width: 250px; padding: 1rem;">
            <a href="dashboard/doctors" class="btn btn-secondary" style="width: 100%;"><i class="fas fa-arrow-left"></i>
                Back
                to Doctors</a>
        </aside>
    <?php endif; ?>

    <!-- Main Content -->
    <div style="flex: 1;">
        <h2 class="section-title"><?= $is_admin ? 'Manage Doctor Availability' : 'My Weekly Availability' ?></h2>

        <?php if (isset($_GET['success'])): ?>
            <div style="background: #DEF7EC; color: #03543F; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                Availability updated successfully!
            </div>
        <?php endif; ?>

        <form action="dashboard/availability" method="POST">
            <input type="hidden" name="doctor_id" value="<?= $doctor_id ?>">
            <div class="card" style="padding: 0; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead style="background: #F9FAFB; border-bottom: 1px solid #E5E7EB;">
                        <tr>
                            <th style="padding: 1rem;">Day</th>
                            <th style="padding: 1rem;">Available</th>
                            <th style="padding: 1rem;">Start Time</th>
                            <th style="padding: 1rem;">End Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $avail_map = [];
                        foreach ($availability as $a) {
                            $avail_map[$a['day_of_week']] = $a;
                        }

                        foreach ($days as $day):
                            $data = $avail_map[$day] ?? ['is_available' => 0, 'start_time' => '09:00:00', 'end_time' => '17:00:00'];
                            ?>
                            <tr style="border-bottom: 1px solid #F3F4F6;">
                                <td style="padding: 1rem; font-weight: bold;">
                                    <?= $day ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <input type="checkbox" name="available[<?= $day ?>]" value="1" <?= $data['is_available'] ? 'checked' : '' ?>>
                                </td>
                                <td style="padding: 1rem;">
                                    <input type="time" name="start_time[<?= $day ?>]" class="form-control"
                                        value="<?= date('H:i', strtotime($data['start_time'])) ?>">
                                </td>
                                <td style="padding: 1rem;">
                                    <input type="time" name="end_time[<?= $day ?>]" class="form-control"
                                        value="<?= date('H:i', strtotime($data['end_time'])) ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-primary mt-6">Save Availability</button>
        </form>
    </div>
</div>

<?php require_once '../views/layouts/footer.php'; ?>