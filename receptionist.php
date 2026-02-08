<?php require_once '../views/layouts/header.php'; ?>

<div class="container section-padding flex" style="gap: 2rem; align-items: flex-start;">
    <!-- Sidebar -->
    <aside class="card" style="width: 250px; padding: 0; position: sticky; top: 2rem;">
        <div style="padding: 1rem; border-bottom: 1px solid #eee;">
            <h4 style="margin:0;">Receptionist Panel</h4>
        </div>
        <nav>
            <ul style="list-style: none;">
                <li><a href="dashboard?view=appointments"
                        style="display: block; padding: 1rem; color: <?= $view === 'appointments' ? 'var(--color-primary)' : '#4b5563' ?>; background: <?= $view === 'appointments' ? '#eff6ff' : 'transparent' ?>; border-left: <?= $view === 'appointments' ? '3px solid var(--color-primary)' : 'none' ?>; font-weight: <?= $view === 'appointments' ? 'bold' : 'normal' ?>; text-decoration: none;">Room
                        Allocation</a></li>
                <li><a href="dashboard?view=billing"
                        style="display: block; padding: 1rem; color: <?= $view === 'billing' ? 'var(--color-primary)' : '#4b5563' ?>; background: <?= $view === 'billing' ? '#eff6ff' : 'transparent' ?>; border-left: <?= $view === 'billing' ? '3px solid var(--color-primary)' : 'none' ?>; font-weight: <?= $view === 'billing' ? 'bold' : 'normal' ?>; text-decoration: none;">Billing
                        & Invoices</a></li>
                <li><a href="dashboard?view=payments"
                        style="display: block; padding: 1rem; color: <?= $view === 'payments' ? 'var(--color-primary)' : '#4b5563' ?>; background: <?= $view === 'payments' ? '#eff6ff' : 'transparent' ?>; border-left: <?= $view === 'payments' ? '3px solid var(--color-primary)' : 'none' ?>; font-weight: <?= $view === 'payments' ? 'bold' : 'normal' ?>; text-decoration: none;">Payment
                        History</a></li>
            </ul>
        </nav>
    </aside>

    <div style="flex: 1;">
        <?php if ($view === 'appointments'): ?>
            <!-- Room Allocation View -->
            <h1 class="section-title">Room Allocation</h1>

            <div class="card">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date/Time</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Status</th>
                            <th>Room Assignment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($appointments)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No appointments found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($appointments as $apt): ?>
                                <tr>
                                    <td>
                                        <?= date('M d', strtotime($apt['appointment_date'])) ?>
                                        <br>
                                        <small class="text-muted"><?= $apt['time_slot'] ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($apt['patient_first'] . ' ' . $apt['patient_last']) ?></td>
                                    <td>Dr. <?= htmlspecialchars($apt['doctor_first'] . ' ' . $apt['doctor_last']) ?></td>
                                    <td>
                                        <span class="badge"
                                            style="background: <?= $apt['status'] === 'confirmed' ? '#10b981' : '#f59e0b' ?>; color: white;">
                                            <?= ucfirst($apt['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="<?= APPROOT ?>/appointment/assign_room"
                                            class="flex gap-2 items-center">
                                            <input type="hidden" name="appointment_id" value="<?= $apt['id'] ?>">
                                            <input type="hidden" name="redirect" value="dashboard?view=appointments">
                                            <input type="text" name="room_number"
                                                value="<?= htmlspecialchars($apt['room_number'] ?? '') ?>" class="form-input"
                                                style="width: 80px; padding: 0.25rem;" placeholder="Room">
                                            <button type="submit" class="btn btn-sm btn-primary" title="Assign Room">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($view === 'billing'): ?>
            <!-- Billing & Invoices View -->
            <h1 class="section-title">Billing & Invoice Management</h1>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                    <?php if ($_GET['success'] === 'created'): ?>
                        Invoice generated successfully!
                    <?php elseif ($_GET['success'] === 'paid'): ?>
                        Payment processed successfully!
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error" style="margin-bottom: 1.5rem;">
                    <?php if ($_GET['error'] === 'exists'): ?>
                        Invoice already exists for this appointment.
                    <?php else: ?>
                        An error occurred. Please try again.
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <h3>Appointments Requiring Billing</h3>
                    <div>
                        <span class="badge" style="background: #10b981; color: white;">Completed</span>
                        <span class="badge" style="background: #f59e0b; color: white;">Pending</span>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Specialty</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($appointments)): ?>
                            <tr>
                                <td colspan="8" class="text-center">No appointments found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($appointments as $apt): ?>
                                <tr>
                                    <td><?= date('M d, Y', strtotime($apt['appointment_date'])) ?></td>
                                    <td><?= $apt['time_slot'] ?></td>
                                    <td><?= htmlspecialchars($apt['patient_first'] . ' ' . $apt['patient_last']) ?></td>
                                    <td>Dr. <?= htmlspecialchars($apt['doctor_first'] . ' ' . $apt['doctor_last']) ?></td>
                                    <td><?= htmlspecialchars($apt['specialty_name']) ?></td>
                                    <td>
                                        <span class="badge"
                                            style="background: <?= $apt['status'] === 'completed' ? '#10b981' : '#f59e0b' ?>; color: white;">
                                            <?= ucfirst($apt['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($apt['payment_id']): ?>
                                            <span class="badge"
                                                style="background: <?= $apt['payment_status'] === 'paid' ? '#10b981' : '#ef4444' ?>; color: white;">
                                                $<?= number_format($apt['amount'], 2) ?> -
                                                <?= ucfirst($apt['payment_status']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge" style="background: #6b7280; color: white;">No Invoice</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!$apt['payment_id']): ?>
                                            <button class="btn btn-sm btn-primary" onclick="showInvoiceModal(<?= $apt['id'] ?>)">
                                                Generate Invoice
                                            </button>
                                        <?php elseif ($apt['payment_status'] === 'unpaid'): ?>
                                            <button class="btn btn-sm btn-success" onclick="processPayment(<?= $apt['payment_id'] ?>)">
                                                Mark as Paid
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted">Paid</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <?php elseif ($view === 'payments'): ?>
            <!-- Payment History View -->
            <h1 class="section-title">Payment History</h1>

            <div class="card">
                <h3 class="mb-4">All Payments</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Payment Date</th>
                            <th>Patient</th>
                            <th>Appointment Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($payments)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No payments found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?= date('M d, Y H:i', strtotime($payment['payment_date'])) ?></td>
                                    <td><?= htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']) ?></td>
                                    <td><?= date('M d, Y', strtotime($payment['appointment_date'])) ?> -
                                        <?= $payment['time_slot'] ?>
                                    </td>
                                    <td><strong>$<?= number_format($payment['amount'], 2) ?></strong></td>
                                    <td><?= ucfirst($payment['payment_method'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge"
                                            style="background: <?= $payment['status'] === 'paid' ? '#10b981' : ($payment['status'] === 'unpaid' ? '#ef4444' : '#6b7280') ?>; color: white;">
                                            <?= ucfirst($payment['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Invoice Modal -->
<div id="invoiceModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 500px;">
        <h3>Generate Invoice</h3>
        <form method="POST" action="<?= APPROOT ?>/billing/generate">
            <input type="hidden" name="appointment_id" id="modal_appointment_id">

            <div class="form-group">
                <label>Amount ($)</label>
                <input type="number" name="amount" class="form-input" step="0.01" min="0" required placeholder="100.00">
            </div>

            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method" class="form-input">
                    <option value="cash">Cash</option>
                    <option value="card">Credit/Debit Card</option>
                    <option value="insurance">Insurance</option>
                    <option value="check">Check</option>
                </select>
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Generate Invoice</button>
                <button type="button" class="btn btn-secondary" onclick="closeInvoiceModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function showInvoiceModal(appointmentId) {
        document.getElementById('modal_appointment_id').value = appointmentId;
        document.getElementById('invoiceModal').style.display = 'flex';
    }

    function closeInvoiceModal() {
        document.getElementById('invoiceModal').style.display = 'none';
    }

    function processPayment(paymentId) {
        if (confirm('Mark this payment as paid?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= APPROOT ?>/billing/process';

            const paymentInput = document.createElement('input');
            paymentInput.type = 'hidden';
            paymentInput.name = 'payment_id';
            paymentInput.value = paymentId;

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'paid';

            form.appendChild(paymentInput);
            form.appendChild(statusInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Close modal when clicking outside
    window.onclick = function (event) {
        const modal = document.getElementById('invoiceModal');
        if (event.target === modal) {
            closeInvoiceModal();
        }
    }
</script>

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .alert {
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1rem;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #10b981;
    }

    .alert-error {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #ef4444;
    }
</style>

<?php require_once '../views/layouts/footer.php'; ?>