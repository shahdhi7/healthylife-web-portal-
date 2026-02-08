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
                        Patients</a></li>
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
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Manage Staff</a>
                </li>
                <li><a href="dashboard/feedback"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Feedback</a></li>
            </ul>
        </nav>
    </aside>

    <div style="flex: 1;">
        <h2 class="section-title">Edit Doctor Profile</h2>

        <div class="card" style="max-width: 800px;">
            <form action="admin/doctor/update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="doctor_id" value="<?= $doctor['id'] ?>">
                <input type="hidden" name="user_id" value="<?= $doctor['user_id'] ?>">

                <h3
                    style="margin-bottom: 1rem; color: var(--color-primary); border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">
                    Basic Information</h3>

                <div class="form-group">
                    <label>Profile Image</label>
                    <?php if (!empty($doctor['image_path'])): ?>
                        <div style="margin-bottom: 1rem;">
                            <img src="uploads/<?= htmlspecialchars($doctor['image_path']) ?>" alt="Current Profile"
                                style="max-width: 150px; border-radius: 8px; border: 2px solid #e5e7eb;">
                            <p class="text-muted" style="font-size: 0.85rem; margin-top: 0.5rem;">Current profile image</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="profile_image" id="profile_image" class="form-input" accept="image/*"
                        style="width: 100%;" onchange="previewImage(event)">
                    <small class="text-muted">Upload new image to replace current (leave empty to keep existing)</small>
                    <div id="image-preview" style="margin-top: 1rem; display: none;">
                        <img id="preview" src="" alt="Preview"
                            style="max-width: 200px; border-radius: 8px; border: 2px solid #e5e7eb;">
                    </div>
                </div>

                <div class="grid grid-2 gap-4">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="form-input"
                            value="<?= htmlspecialchars($doctor['first_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-input"
                            value="<?= htmlspecialchars($doctor['last_name']) ?>" required>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-input"
                        value="<?= htmlspecialchars($doctor['email']) ?>" required style="width: 100%;">
                </div>

                <div class="grid grid-2 gap-4 mt-4">
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" class="form-input" style="width: 100%;">
                            <option value="Male" <?= $doctor['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $doctor['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                            <option value="Other" <?= $doctor['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-input" value="<?= $doctor['date_of_birth'] ?>"
                            required style="width: 100%;">
                    </div>
                </div>

                <h3
                    style="margin: 2rem 0 1rem; color: var(--color-primary); border-bottom: 1px solid #eee; padding-bottom: 0.5rem;">
                    Clinical Information</h3>

                <div class="grid grid-2 gap-4">
                    <div class="form-group">
                        <label>Specialty</label>
                        <select name="specialty_id" class="form-input" style="width: 100%;" required>
                            <?php foreach ($specialties as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= $s['id'] == $doctor['specialty_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Experience (Years)</label>
                        <input type="number" name="experience" class="form-input"
                            value="<?= $doctor['experience_years'] ?>" required style="width: 100%;">
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label>Biography / Professional Background</label>
                    <textarea name="bio" class="form-input"
                        style="width: 100%; height: 120px;"><?= htmlspecialchars($doctor['bio'] ?? '') ?></textarea>
                </div>

                <div class="flex gap-4 mt-8">
                    <button type="submit" class="btn btn-primary">Save Profile Changes</button>
                    <a href="dashboard/doctors" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('image-preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
</script>

<?php require_once '../views/layouts/footer.php'; ?>