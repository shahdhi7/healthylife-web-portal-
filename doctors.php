<?php require_once '../views/layouts/header.php'; ?>

<section class="section-padding" style="background-color: var(--bg-body);">
    <div class="container">
        <h1 class="text-center section-title">Discover Medical Specialists</h1>

        <!-- Filter Section -->
        <div class="flex justify-center mb-4" style="margin-bottom: 2rem; gap: 1rem; flex-wrap: wrap;">
            <button class="btn btn-primary" onclick="filterDoctors('all')">All</button>
            <?php foreach ($specialties as $specialty): ?>
                <button class="btn btn-secondary" onclick="filterDoctors('<?= $specialty['name'] ?>')">
                    <?= $specialty['name'] ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-3 gap-4" id="doctors-grid">
            <?php if (empty($doctors)): ?>
                <div class="card text-center" style="grid-column: 1 / -1;">
                    <p>No doctors found at the moment. Please contact admin to seed data.</p>
                </div>
            <?php else: ?>
                <?php foreach ($doctors as $doctor): ?>
                    <div class="card doctor-card" data-specialty="<?= $doctor['specialty_name'] ?>">
                        <div class="doctor-image-wrapper" style="text-align: center; margin-bottom: 1rem;">
                            <!-- Dynamic image or placeholder -->
                            <img src="<?= $doctor['image_path'] ? 'uploads/' . $doctor['image_path'] : 'images/default-doctor.png' ?>"
                                alt="<?= $doctor['first_name'] ?>"
                                style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--color-primary-light);">
                        </div>

                        <h3 class="text-center" style="color: var(--color-primary-dark); margin-bottom: 0.5rem;">
                            Dr.
                            <?= $doctor['first_name'] . ' ' . $doctor['last_name'] ?>
                        </h3>

                        <div class="text-center" style="margin-bottom: 1rem;">
                            <span class="badge"
                                style="background: #DBEAFE; color: #1E40AF; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.875rem; font-weight: 600;">
                                <?= $doctor['specialty_name'] ?>
                            </span>
                        </div>

                        <div class="doctor-stats flex justify-between"
                            style="margin-bottom: 1rem; font-size: 0.9rem; color: var(--text-muted); border-top: 1px solid #E5E7EB; border-bottom: 1px solid #E5E7EB; padding: 0.5rem 0;">
                            <div>
                                <strong style="display: block; color: var(--text-main);">
                                    <?= $doctor['experience_years'] ?>+ Years
                                </strong>
                                Experience
                            </div>
                            <div>
                                <strong style="display: block; color: var(--text-main);">
                                    <?= $doctor['success_rate'] ?>%
                                </strong>
                                Success
                            </div>
                        </div>

                        <p class="text-muted" style="font-size: 0.9rem; margin-bottom: 1rem; line-height: 1.4;">
                            <?= substr($doctor['bio'], 0, 100) ?>...
                        </p>

                        <div class="flex gap-2">
                            <a href="doctors/details?id=<?= $doctor['id'] ?>" class="btn btn-secondary"
                                style="width: 100%;">View Profile</a>
                            <a href="book?doctor_id=<?= $doctor['id'] ?>" class="btn btn-primary" style="width: 100%;">Book
                                Appointment</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    function filterDoctors(category) {
        const cards = document.querySelectorAll('.doctor-card');
        cards.forEach(card => {
            if (category === 'all' || card.dataset.specialty === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>

<?php require_once '../views/layouts/footer.php'; ?>