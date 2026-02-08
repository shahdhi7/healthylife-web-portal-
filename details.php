<?php require_once '../views/layouts/header.php'; ?>

<section class="section-padding" style="background-color: var(--bg-body);">
    <div class="container">
        <div class="flex items-center mb-6">
            <a href="doctors" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Specialists
            </a>
        </div>

        <div class="grid grid-3 gap-6">
            <!-- Left: Doctor Info Card -->
            <div class="col-span-1">
                <div class="card text-center" style="padding: 2rem;">
                    <div class="doctor-image-wrapper mb-4">
                        <img src="<?= $doctor['image_path'] ? 'uploads/' . $doctor['image_path'] : 'images/default-doctor.png' ?>"
                            alt="<?= $doctor['first_name'] ?>"
                            style="width: 180px; height: 180px; border-radius: 50%; object-fit: cover; border: 6px solid var(--color-primary-light);">
                    </div>
                    <h2 style="color: var(--color-primary-dark); margin-bottom: 0.5rem;">
                        Dr.
                        <?= htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']) ?>
                    </h2>
                    <div class="mb-4">
                        <span class="badge"
                            style="background: #DBEAFE; color: #1E40AF; padding: 0.5rem 1rem; border-radius: 999px; font-size: 1rem; font-weight: 600;">
                            <?= htmlspecialchars($doctor['specialty_name']) ?>
                        </span>
                    </div>

                    <div class="doctor-stats flex justify-around"
                        style="margin: 1.5rem 0; padding: 1rem 0; border-top: 1px solid #E5E7EB; border-bottom: 1px solid #E5E7EB;">
                        <div>
                            <strong style="display: block; font-size: 1.2rem; color: var(--color-primary);">
                                <?= $doctor['experience_years'] ?>+
                            </strong>
                            <span class="text-muted" style="font-size: 0.8rem;">Years Exp.</span>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 1.2rem; color: var(--color-success);">
                                <?= $doctor['success_rate'] ?>%
                            </strong>
                            <span class="text-muted" style="font-size: 0.8rem;">Success</span>
                        </div>
                    </div>

                    <a href="book?doctor_id=<?= $doctor['id'] ?>" class="btn btn-primary btn-lg" style="width: 100%;">
                        Book Appointment
                    </a>
                </div>

                <div class="card mt-4">
                    <h3>Contact Information</h3>
                    <ul style="list-style: none; padding: 0; margin-top: 1rem;">
                        <li style="margin-bottom: 0.75rem;">
                            <i class="fas fa-envelope" style="color: var(--color-primary); margin-right: 0.5rem;"></i>
                            <?= htmlspecialchars($doctor['email']) ?>
                        </li>
                        <?php if ($doctor['phone']): ?>
                            <li style="margin-bottom: 0.75rem;">
                                <i class="fas fa-phone" style="color: var(--color-primary); margin-right: 0.5rem;"></i>
                                <?= htmlspecialchars($doctor['phone']) ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Right: Biography & More -->
            <div class="col-span-2">
                <div class="card" style="height: 100%;">
                    <h2 class="section-title" style="text-align: left; margin-bottom: 1.5rem;">Professional Biography
                    </h2>
                    <div class="bio-content" style="line-height: 1.8; color: var(--text-main); font-size: 1.1rem;">
                        <?= nl2br(htmlspecialchars($doctor['bio'])) ?>
                    </div>

                    <div class="mt-8" style="padding-top: 2rem; border-top: 1px solid #E5E7EB;">
                        <h3>Specializations & Expertise</h3>
                        <p class="text-muted mt-2">
                            Dr.
                            <?= htmlspecialchars($doctor['last_name']) ?> is highly skilled in providing expert medical
                            care
                            within the field of
                            <?= htmlspecialchars($doctor['specialty_name']) ?>.
                            With
                            <?= $doctor['experience_years'] ?> years of dedicated practice, patients can expect thorough
                            and compassionate treatment using the latest medical advancements.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .col-span-1 {
        grid-column: span 1;
    }

    .col-span-2 {
        grid-column: span 2;
    }

    @media (max-width: 768px) {

        .col-span-1,
        .col-span-2 {
            grid-column: span 3;
        }
    }
</style>

<?php require_once '../views/layouts/footer.php'; ?>