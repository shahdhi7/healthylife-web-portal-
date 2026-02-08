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
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Manage Doctors</a>
                </li>
                <li><a href="admin/appointments"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Appointments</a>
                </li>
                <li><a href="admin/specialties"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Specialties</a>
                </li>
                <li><a href="dashboard/staff"
                        style="display: block; padding: 1rem; color: var(--color-primary); background: #eff6ff; border-left: 3px solid var(--color-primary); font-weight: bold;">Manage
                        Staff</a>
                </li>
                <li><a href="dashboard/feedback"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Feedback</a></li>
            </ul>
        </nav>
    </aside>

    <div style="flex: 1;">
        <h2 class="section-title">Add New Staff Member</h2>

        <div class="card" style="max-width: 800px;">
            <form action="admin/staff/create" method="POST">

                <div class="grid grid-2 gap-4">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-input" placeholder="Full Name" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <input type="text" name="role" class="form-input" placeholder="e.g. Nurse, Radiologist"
                            required>
                    </div>
                </div>

                <div class="grid grid-2 gap-4 mt-4">
                    <div class="form-group">
                        <label>Department</label>
                        <input type="text" name="department" class="form-input" placeholder="e.g. Cardiology Unit"
                            required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="form-input" placeholder="Phone Number" required>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-input" placeholder="Email Address" required
                        style="width: 100%;">
                </div>

                <div class="form-group mt-4">
                    <label>Description / Notes</label>
                    <textarea name="description" class="form-input" style="width: 100%; height: 120px;"
                        placeholder="Brief background or notes about the staff member..."></textarea>
                </div>

                <div class="flex gap-4 mt-8">
                    <button type="submit" class="btn btn-primary">Add Staff Member</button>
                    <a href="dashboard/staff" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../views/layouts/footer.php'; ?>