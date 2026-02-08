<?php require_once '../views/layouts/header.php'; ?>

<div class="container section-padding flex justify-center">
    <div class="card" style="max-width: 800px; width: 100%;">
        <h2 class="text-center section-title">Book an Appointment</h2>

        <!-- Step Indicator -->
        <div class="flex justify-between"
            style="margin-bottom: 2rem; border-bottom: 1px solid #E5E7EB; padding-bottom: 1rem;">
            <div class="step active" id="step-1-indicator">1. Choose Doctor</div>
            <div class="step" id="step-2-indicator">2. Select Date</div>
            <div class="step" id="step-3-indicator">3. Confirm Slot</div>
        </div>

        <form id="booking-form" action="book/confirm" method="POST">
            <!-- Step 1: Doctor Selection -->
            <div id="step-1">
                <div class="form-group mb-4">
                    <label>Filter by Specialization</label>
                    <select id="specialty-filter" class="form-input" onchange="filterDoctors()">
                        <option value="all">All Specialties</option>
                        <?php foreach ($specialties as $s): ?>
                            <option value="<?= $s['id'] ?>">
                                <?= $s['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-2 gap-4" id="doctor-list" style="margin-top: 1rem;">
                    <?php foreach ($doctors as $d): ?>
                        <div class="doctor-select-card card" data-specialty="<?= $d['specialty_id'] ?>"
                            onclick="selectDoctor(<?= $d['id'] ?>, '<?= $d['first_name'] . ' ' . $d['last_name'] ?>')"
                            style="cursor: pointer; border: 2px solid transparent;">
                            <h4>Dr.
                                <?= $d['first_name'] . ' ' . $d['last_name'] ?>
                            </h4>
                            <p class="text-muted">
                                <?= $d['specialty_name'] ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="doctor_id" id="doctor_id" value="<?= $selected_doctor ?? '' ?>" required>
            </div>

            <!-- Step 2: Date Selection -->
            <div id="step-2" class="hidden">
                <h3 class="mb-4">Select Date for <span id="selected-doctor-name" class="highlight"></span></h3>
                <input type="date" name="date" id="date" class="form-input" min="<?= date('Y-m-d') ?>"
                    onchange="fetchSlots()" required style="width: 100%;">
            </div>

            <!-- Step 3: Slot Selection -->
            <div id="step-3" class="hidden">
                <h3 class="mb-4" id="step-3-header">Available Slots</h3>
                <div id="slots-container" class="grid grid-3 gap-4">
                    <!-- Slots will be injected here -->
                </div>
                <input type="hidden" name="time_slot" id="time_slot" required>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg" id="confirm-btn">Confirm Booking</button>
                </div>
            </div>

            <div class="flex justify-between mt-4">
                <button type="button" id="prev-btn" class="btn btn-secondary hidden" onclick="prevStep()">Back</button>
                <!-- Next button is triggered by selection usually, but we can have manual next -->
            </div>
        </form>
    </div>
</div>

<script>
    let currentStep = 1;

    function showStep(step) {
        document.getElementById('step-1').classList.add('hidden');
        document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-3').classList.add('hidden');

        document.getElementById('step-' + step).classList.remove('hidden');

        if (step > 1) {
            document.getElementById('prev-btn').classList.remove('hidden');
        } else {
            document.getElementById('prev-btn').classList.add('hidden');
        }
        currentStep = step;

        // Highlight logic for indicators could go here
    }

    function prevStep() {
        showStep(currentStep - 1);
    }

    function selectDoctor(id, name) {
        document.getElementById('doctor_id').value = id;
        document.getElementById('selected-doctor-name').textContent = "Dr. " + name;

        // Visual selection
        document.querySelectorAll('.doctor-select-card').forEach(c => c.style.borderColor = 'transparent');
        event.currentTarget.style.borderColor = 'var(--color-primary)';

        showStep(2);
    }

    function filterDoctors() {
        const cat = document.getElementById('specialty-filter').value;
        const cards = document.querySelectorAll('.doctor-select-card');
        cards.forEach(card => {
            if (cat === 'all' || card.dataset.specialty == cat) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    async function fetchSlots() {
        const doctorId = document.getElementById('doctor_id').value;
        const date = document.getElementById('date').value;

        if (!date) return;

        const container = document.getElementById('slots-container');
        container.innerHTML = '<div style="grid-column: 1/-1; text-align:center;"><p><i class="fas fa-spinner fa-spin"></i> Checking availability...</p></div>';

        try {
            const response = await fetch(`book/slots?doctor_id=${doctorId}&date=${date}`);
            const data = await response.json();

            container.innerHTML = '';

            if (data.error) {
                container.innerHTML = `
                    <div style="grid-column: 1/-1; text-align:center; padding: 2rem; border: 1px dashed #ef4444; border-radius: 8px; background: #FEF2F2;">
                        <i class="fas fa-calendar-times" style="font-size: 2rem; color: #ef4444; margin-bottom: 1rem;"></i>
                        <h4 style="color: #b91c1c;">${data.error}</h4>
                        <p class="text-muted">Currently, the doctor is not available on this selected date.</p>
                    </div>
                `;
                document.getElementById('step-3-header').style.display = 'none';
                document.getElementById('confirm-btn').style.display = 'none';
                showStep(3);
                return;
            } else {
                document.getElementById('step-3-header').style.display = 'block';
                document.getElementById('confirm-btn').style.display = 'inline-block';
            }

            if (data.slots.length === 0) {
                container.innerHTML = '<div style="grid-column: 1/-1; text-align:center;"><p>No slots defined for this day.</p></div>';
                return;
            }

            data.slots.forEach(slot => {
                const div = document.createElement('div');
                div.className = `card text-center slot-card ${slot.is_full ? 'disabled' : ''}`;
                div.style.padding = '0.75rem';
                div.style.transition = 'all 0.2s';
                div.style.cursor = slot.is_full ? 'not-allowed' : 'pointer';

                if (slot.is_full) {
                    div.style.backgroundColor = '#F1F5F9';
                    div.style.opacity = '0.7';
                }

                div.innerHTML = `
                    <div style="font-weight: 700; color: ${slot.is_full ? '#94a3b8' : 'var(--color-primary)'};">${slot.label}</div>
                    <div style="font-size: 0.8rem; margin: 0.25rem 0; color: ${slot.booked >= 4 ? '#ef4444' : '#6b7280'};">
                        Occupied: ${slot.booked}/5
                    </div>
                    <small style="color: ${slot.is_full ? '#ef4444' : '#10b981'}; font-weight: 500;">
                        ${slot.is_full ? 'SLOT FULL' : 'AVAILABLE'}
                    </small>
                `;

                if (!slot.is_full) {
                    div.onclick = () => {
                        document.querySelectorAll('.slot-card').forEach(c => {
                            c.style.border = '1px solid #eee';
                            c.style.backgroundColor = 'white';
                        });
                        div.style.border = '2px solid var(--color-primary)';
                        div.style.backgroundColor = '#eff6ff';
                        document.getElementById('time_slot').value = slot.time;
                    };
                }

                container.appendChild(div);
            });

            showStep(3);
        } catch (e) {
            console.error(e);
            container.innerHTML = '<div style="grid-column: 1/-1; text-align:center;"><p class="text-danger">Error loading slots. Please refresh.</p></div>';
        }
    }

    // Auto-select doctor if passed in URL
    window.addEventListener('DOMContentLoaded', () => {
        const preSelected = "<?= $selected_doctor ?>";
        if (preSelected) {
            const doctorCard = document.querySelector(`.doctor-select-card[onclick*="selectDoctor(${preSelected}"]`);
            if (doctorCard) {
                doctorCard.click();
            }
        }
    });
</script>

<style>
    .grid-2 {
        grid-template-columns: 1fr 1fr;
    }
</style>

<?php require_once '../views/layouts/footer.php'; ?>