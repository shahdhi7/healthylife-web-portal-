<?php require_once '../views/layouts/header.php'; ?>

<section class="hero-section">
    <div class="container flex items-center justify-between hero-container">
        <div class="hero-text">
            <h1>Expert Care for a <br><span class="highlight">Healthy Life</span></h1>
            <p>Experience world-class medical treatment with our state-of-the-art facilities and specialized doctors.
                Your health journey starts here.</p>
            <div class="hero-buttons">
                <a href="doctors" class="btn btn-primary">Find a Doctor</a>
                <a href="services" class="btn btn-secondary">Our Services</a>
            </div>
        </div>
        <div class="hero-image">
            <div class="image-placeholder"></div>
        </div>
    </div>
</section>

<!-- New Search Section (Directly below Hero) -->
<section class="search-section">
    <div class="container">
        <div class="search-container">
            <h2>Find Your Specialist</h2>
            <form action="search" method="GET" class="search-form">
                <input type="text" name="q" placeholder="Search for doctors, departments, or services..."
                    class="search-input">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
</section>

<!-- Architecture Section -->
<section class="architecture-section">
    <div class="container">
        <h2 class="section-title">Our Facility</h2>
        <div class="architecture-grid">
            <div class="arch-content">
                <h2>Designed for Healing</h2>
                <p>Our hospital architecture combines modern aesthetics with patient-centric design. Experience a
                    calming environment that promotes recovery and well-being from the moment you step in.</p>
                <div class="arch-image">
                    <img src="images/hospital_interior.png" alt="Hospital Interior Lobby">
                </div>
            </div>
            <div class="arch-content">
                <h2>World-Class Infrastructure</h2>
                <p>Equipped with the latest medical technology and housed in a sustainable, eco-friendly building, we
                    ensure the highest standards of safety and care.</p>
                <div class="arch-image">
                    <img src="images/hospital_exterior.png" alt="Hospital Exterior Building">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="services-preview section-padding">
    <div class="container">
        <h2 class="section-title text-center">Our Services</h2>
        <div class="grid grid-3">
            <div class="card service-card">
                <i class="fas fa-heartbeat icon-lg"></i>
                <h3>Cardiology</h3>
                <p>Comprehensive heart care services with advanced diagnostic and treatment options.</p>
            </div>
            <div class="card service-card">
                <i class="fas fa-brain icon-lg"></i>
                <h3>Neurology</h3>
                <p>Advanced neurological treatments for complex brain and nervous system conditions.</p>
            </div>
            <div class="card service-card">
                <i class="fas fa-child icon-lg"></i>
                <!-- Changed icon to fa-child which is often used for pediatrics/kids if baby isn't available, but keeping consistent -->
                <h3>Pediatrics</h3>
                <p>Specialized care for children, ensuring their health and development from infancy.</p>
            </div>
        </div>
        <div class="text-center mt-4" style="text-align:center; padding-top:2rem;">
            <a href="services" class="btn btn-secondary">View All Services</a>
        </div>
    </div>
</section>

<!-- Contact/Feedback Section -->
<section id="contact" class="contact-section">
    <div class="container">
        <h2 class="section-title">Get in Touch</h2>
        <div class="contact-form-container">
            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'success'): ?>
                <div
                    style="background: #DEF7EC; color: #03543F; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: center;">
                    <i class="fas fa-check-circle"></i> Thank you! Your message has been sent successfully.
                </div>
            <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'error'): ?>
                <div
                    style="background: #FDE8E8; color: #9B1C1C; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: center;">
                    <i class="fas fa-exclamation-circle"></i> Oops! Something went wrong. Please try again later.
                </div>
            <?php endif; ?>
            <form action="contact/send" method="POST">
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <select id="subject" name="subject" class="form-control">
                        <option value="inquiry">General Inquiry</option>
                        <option value="appointment">Appointment Help</option>
                        <option value="feedback">Feedback</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
            </form>
        </div>
    </div>
</section>

<?php require_once '../views/layouts/footer.php'; ?>