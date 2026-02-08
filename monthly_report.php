<?php require_once '../views/layouts/header.php'; ?>

<div class="container section-padding flex" style="gap: 2rem; align-items: flex-start;">
    <!-- Sidebar -->
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
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Manage Staff</a>
                </li>
                <li><a href="dashboard/feedback"
                        style="display: block; padding: 1rem; color: #4b5563; text-decoration: none;">Feedback</a></li>
                <li><a href="admin/report/monthly"
                        style="display: block; padding: 1rem; color: var(--color-primary); background: #eff6ff; border-left: 3px solid var(--color-primary); font-weight: bold;">Monthly
                        Report</a></li>
            </ul>
        </nav>
    </aside>

    <div style="flex: 1;">
        <!-- Header with Month Selector -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="section-title">Monthly Report -
                <?= $report['month_name'] ?>
                <?= $report['year'] ?>
            </h1>
            <div class="flex gap-2">
                <a href="admin/report/export?month=<?= $report['month'] ?>&year=<?= $report['year'] ?>"
                    class="btn btn-secondary">
                    <i class="fas fa-download"></i> Export CSV
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>

        <!-- Month/Year Selector -->
        <div class="card mb-6">
            <form method="GET" action="<?= APPROOT ?>/admin/report/monthly" class="flex gap-4 items-end">
                <div class="form-group" style="margin: 0;">
                    <label>Month</label>
                    <select name="month" class="form-input">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $m == $report['month'] ? 'selected' : '' ?>>
                                <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group" style="margin: 0;">
                    <label>Year</label>
                    <select name="year" class="form-input">
                        <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                            <option value="<?= $y ?>" <?= $y == $report['year'] ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </form>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-4 gap-4 mb-6">
            <div class="card text-center" style="background: linear-gradient(135deg, #004aad, #528bdf); color: white;">
                <i class="fas fa-calendar-check" style="font-size: 2.5rem; margin-bottom: 1rem; opacity: 0.8;"></i>
                <h3 style="margin: 0; font-size: 2rem;">
                    <?= $report['appointments']['total'] ?>
                </h3>
                <p style="margin: 0.5rem 0 0;">Total Appointments</p>
            </div>
            <div class="card text-center" style="background: linear-gradient(135deg, #10b981, #34d399); color: white;">
                <i class="fas fa-user-plus" style="font-size: 2.5rem; margin-bottom: 1rem; opacity: 0.8;"></i>
                <h3 style="margin: 0; font-size: 2rem;">
                    <?= $report['patients']['new_patients'] ?>
                </h3>
                <p style="margin: 0.5rem 0 0;">New Patients</p>
            </div>
            <div class="card text-center" style="background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white;">
                <i class="fas fa-users" style="font-size: 2.5rem; margin-bottom: 1rem; opacity: 0.8;"></i>
                <h3 style="margin: 0; font-size: 2rem;">
                    <?= $report['patients']['active_patients'] ?>
                </h3>
                <p style="margin: 0.5rem 0 0;">Active Patients</p>
            </div>
            <div class="card text-center" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa); color: white;">
                <i class="fas fa-dollar-sign" style="font-size: 2.5rem; margin-bottom: 1rem; opacity: 0.8;"></i>
                <h3 style="margin: 0; font-size: 2rem;">
                    <?php if (isset($report['revenue']['total_revenue'])): ?>
                        $
                        <?= number_format($report['revenue']['total_revenue'], 2) ?>
                    <?php elseif (isset($report['revenue']['estimated_revenue'])): ?>
                        $
                        <?= number_format($report['revenue']['estimated_revenue'], 2) ?>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </h3>
                <p style="margin: 0.5rem 0 0;">Revenue</p>
            </div>
        </div>

        <!-- Appointment Status Breakdown -->
        <div class="grid grid-2 gap-6 mb-6">
            <div class="card">
                <h3 style="margin-bottom: 1.5rem; color: var(--color-primary);">Appointment Status</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Count</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['appointments']['by_status'] as $status): ?>
                            <tr>
                                <td>
                                    <span class="badge"
                                        style="background: <?= $status['status'] == 'completed' ? '#10b981' : ($status['status'] == 'pending' ? '#f59e0b' : '#6b7280') ?>; color: white;">
                                        <?= ucfirst(str_replace('_', ' ', $status['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= $status['count'] ?>
                                </td>
                                <td>
                                    <?= $report['appointments']['total'] > 0 ? round(($status['count'] / $report['appointments']['total']) * 100, 1) : 0 ?>%
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Specialty Performance -->
            <div class="card">
                <h3 style="margin-bottom: 1.5rem; color: var(--color-primary);">Appointments by Specialty</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Specialty</th>
                            <th>Appointments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($report['specialties'], 0, 5) as $specialty): ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($specialty['name']) ?>
                                </td>
                                <td><strong>
                                        <?= $specialty['appointment_count'] ?>
                                    </strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Performing Doctors -->
        <div class="card mb-6">
            <h3 style="margin-bottom: 1.5rem; color: var(--color-primary);">Top Performing Doctors</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Doctor Name</th>
                        <th>Specialty</th>
                        <th>Appointments</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank = 1;
                    foreach ($report['doctors']['top_doctors'] as $doctor): ?>
                        <tr>
                            <td>
                                <span class="badge"
                                    style="background: <?= $rank <= 3 ? '#fbbf24' : '#e5e7eb' ?>; color: <?= $rank <= 3 ? 'white' : '#4b5563' ?>;">
                                    #
                                    <?= $rank ?>
                                </span>
                            </td>
                            <td>Dr.
                                <?= htmlspecialchars($doctor['first_name'] . ' ' . $doctor['last_name']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($doctor['specialty']) ?>
                            </td>
                            <td><strong>
                                    <?= $doctor['appointment_count'] ?>
                                </strong></td>
                        </tr>
                        <?php $rank++; endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Daily Appointment Chart -->
        <div class="card">
            <h3 style="margin-bottom: 1.5rem; color: var(--color-primary);">Daily Appointments</h3>
            <div style="height: 300px; position: relative;">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Appointments Chart
    const dailyData = <?= json_encode($report['appointments']['daily']) ?>;
    const daysInMonth = new Date(<?= $report['year'] ?>, <?= $report['month'] ?>, 0).getDate();
    const labels = [];
    const data = [];

    for (let i = 1; i <= daysInMonth; i++) {
        labels.push(i);
        const dayData = dailyData.find(d => d.day == i);
        data.push(dayData ? dayData.count : 0);
    }

    const ctx = document.getElementById('dailyChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Appointments',
                data: data,
                borderColor: '#004aad',
                backgroundColor: 'rgba(0, 74, 173, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>

<style>
    @media print {

        aside,
        .btn,
        form {
            display: none !important;
        }
    }
</style>

<?php require_once '../views/layouts/footer.php'; ?>