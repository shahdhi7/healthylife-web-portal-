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
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Appointments</a>
                </li>
                <li><a href="admin/specialties"
                        style="display: block; padding: 1rem; color: var(--color-primary); background: #eff6ff; border-left: 3px solid var(--color-primary); font-weight: bold;">Specialties</a>
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
        <h2 class="section-title">Medical Specialties</h2>

        <div class="card mb-4">
            <h4>Add New Specialty</h4>
            <form action="admin/specialty/add" method="POST" class="flex gap-2">
                <input type="text" name="name" placeholder="Specialty Name (e.g. Oncology)" class="form-input" required
                    style="flex: 1;">
                <button type="submit" class="btn btn-primary">Add</button>
            </form>
        </div>

        <div class="grid grid-3 gap-4">
            <?php foreach ($specialties as $s): ?>
                <div class="card flex justify-between items-center">
                    <span>
                        <?= htmlspecialchars($s['name']) ?>
                    </span>
                    <form action="admin/specialty/delete" method="POST"
                        onsubmit="return confirm('Delete this specialty?');">
                        <input type="hidden" name="id" value="<?= $s['id'] ?>">
                        <button type="submit" style="background:none; border:none; color: #ef4444; cursor: pointer;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once '../views/layouts/footer.php'; ?>