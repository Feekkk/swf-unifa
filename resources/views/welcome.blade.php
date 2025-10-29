<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="UniKL RCMP Student Welfare Fund - Financial assistance and support for students at Universiti Kuala Lumpur Royal College of Medicine Perak">
    <meta name="keywords" content="UniKL RCMP, Student Welfare Fund, Financial Aid, University, Medical College, Perak">
    <meta name="author" content="UniKL RCMP">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UniKL RCMP') }} - Student Welfare Fund</title>

    <!-- DNS Prefetch -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    
    <!-- Preconnect to font providers -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Preload critical resources -->
    <link rel="preload" href="/assets/images/logos/unikl-rcmp.png" as="image" type="image/png">
    
    <!-- Load fonts with display=swap for better performance -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/logos/favicon.ico">
    <link rel="apple-touch-icon" href="/assets/images/logos/apple-touch-icon.png">

    <!-- Critical CSS (inlined for performance) -->
    <style>
        /* Critical above-the-fold styles */
        body { font-family: 'Inter', sans-serif; margin: 0; background: #FAF9EE; }
        .header { background: #16476A; position: sticky; top: 0; z-index: 100; }
        .skip-link { position: absolute; top: -40px; left: 6px; background: #16476A; color: white; padding: 8px 16px; text-decoration: none; border-radius: 8px; z-index: 1000; }
        .skip-link:focus { top: 6px; }
        .hero-slideshow { width: 100vw; height: 70vh; min-height: 500px; background: #343a40; margin-left: calc(-50vw + 50%); }
    </style>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/polyfills.js', 'resources/js/app.js', 'resources/js/accessibility.js', 'resources/js/lazy-loading.js'])
</head>
<body>
    <!-- Skip Link for Accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- Header -->
    <header class="header" id="header">
        <div class="header-content">
            <!-- Logo -->
            <div class="logo-container">
                <img src="/assets/images/logos/unikl-rcmp-logo-white.png" alt="Universiti Kuala Lumpur Royal College of Medicine Perak - Student Welfare Fund" class="logo" loading="eager" width="200" height="60">
            </div>

            <!-- Navigation -->
            <nav class="nav" role="navigation" aria-label="Main navigation">
                <!-- Mobile Menu Toggle -->
                <button class="nav-toggle" aria-expanded="false" aria-controls="nav-menu" aria-label="Toggle navigation menu">
                    ☰
                </button>

                <!-- Navigation Menu -->
                <ul class="nav-menu" id="nav-menu">
                    <li class="nav-item">
                        <a href="#home" class="nav-link active">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#about" class="nav-link">About SWF</a>
                    </li>
                    <li class="nav-item">
                        <a href="#application" class="nav-link">Apply for Fund</a>
                    </li>
                    <li class="nav-item">
                        <a href="#contact" class="nav-link">Contact</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">Login</a>
                        </li>
                    @endauth
                </ul>

                <!-- CTA Button -->
                <a href="#application" class="btn btn-accent">Apply Now</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main id="main-content">
        <!-- Hero Section -->
        <section class="hero hero-slideshow" id="home" aria-label="Hero slideshow">
            <!-- Slideshow will be initialized by JavaScript -->
        </section>

        <!-- Introduction Section -->
        <section class="content-section" id="about" aria-labelledby="about-heading">
            <div class="container">
                <div class="two-column two-column-60-40">
                    <div>
                        <h2 id="about-heading">Supporting Your Academic Journey</h2>
                        <p>The Student Welfare Fund (SWF) at UniKL RCMP is dedicated to providing essential financial assistance to our students. Established to ensure that financial constraints do not hinder your educational aspirations, our fund offers comprehensive support for various needs including medical emergencies, bereavement, and critical situations.</p>
                        
                        <p>Since its establishment on September 30, 2005, and rebranding in 2017, the SWF has been managed by the Campus Lifestyle Division to ensure efficient and compassionate support for our student community.</p>
                        
                        <div class="mb-lg">
                            <h3>Our Mission</h3>
                            <p>To provide essential welfare support to UniKL students, including assistance in cases of emergencies, medical conditions or injuries, and bereavement.</p>
                        </div>

                        <a href="#application" class="btn btn-primary">Learn About Eligibility</a>
                    </div>
                    <div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Student Contributions</h3>
                            </div>
                            <div class="card-content">
                                <p><strong>Local Students:</strong> RM30.00 per semester</p>
                                <p><strong>International Students:</strong> RM50.00 per semester</p>
                                <p class="mb-0">Your contributions help build a supportive community fund that assists fellow students in times of need.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SWF Structure Section -->
        <section class="content-section" aria-labelledby="swf-structure-heading">
            <div class="container">
                <h2 id="swf-structure-heading" class="text-center mb-xl">Student Welfare Fund Structure</h2>
                
                <div class="grid grid-cols-4 gap-lg">
                    <!-- Fund Types Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Fund Categories</h3>
                        </div>
                        <div class="card-content">
                            <ul>
                                <li><strong>Bereavement:</strong> Student (RM500), Parent (RM200), Siblings (RM100)</li>
                                <li><strong>Medical:</strong> Out-patient (RM30/semester), In-patient (up to RM1,000)</li>
                                <li><strong>Emergency:</strong> Critical illness, natural disasters, and other emergencies</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Eligibility Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Eligibility Criteria</h3>
                        </div>
                        <div class="card-content">
                            <ul>
                                <li>Currently enrolled UniKL RCMP student</li>
                                <li>Up-to-date SWF contribution payments</li>
                                <li>Valid supporting documentation</li>
                                <li>Compliance with fund guidelines</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Application Process Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Application Process</h3>
                        </div>
                        <div class="card-content">
                            <ol>
                                <li>Submit online application</li>
                                <li>Provide required documentation</li>
                                <li>SWF Committee review</li>
                                <li>Approval notification</li>
                                <li>Fund disbursement</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Committee Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">SWF Committee</h3>
                        </div>
                        <div class="card-content">
                            <ul>
                                <li>Head of Campus / Dean</li>
                                <li>Deputy Dean</li>
                                <li>Campus Lifestyle Head</li>
                                <li>Finance & Admin Representative</li>
                                <li>Student Representative</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Application Process Timeline -->
        <section class="content-section" id="application" aria-labelledby="application-heading">
            <div class="container">
                <h2 id="application-heading" class="text-center mb-xl">How to Apply for Financial Aid</h2>
                
                <div class="two-column">
                    <div>
                        <h3>Step-by-Step Process</h3>
                        <ol class="timeline" role="list" aria-label="Application process steps">
                            <li class="timeline-item">
                                <div class="timeline-marker" aria-hidden="true">1</div>
                                <div class="timeline-content">
                                    <h4>Create Account</h4>
                                    <p>Register for an account using your student credentials and personal information.</p>
                                </div>
                            </li>
                            
                            <li class="timeline-item">
                                <div class="timeline-marker" aria-hidden="true">2</div>
                                <div class="timeline-content">
                                    <h4>Complete Application</h4>
                                    <p>Fill out the comprehensive application form with accurate details about your situation.</p>
                                </div>
                            </li>
                            
                            <li class="timeline-item">
                                <div class="timeline-marker" aria-hidden="true">3</div>
                                <div class="timeline-content">
                                    <h4>Submit Documentation</h4>
                                    <p>Upload required supporting documents such as medical reports, receipts, or certificates.</p>
                                </div>
                            </li>
                            
                            <li class="timeline-item">
                                <div class="timeline-marker" aria-hidden="true">4</div>
                                <div class="timeline-content">
                                    <h4>Committee Review</h4>
                                    <p>Your application will be reviewed by the SWF Campus Committee for approval.</p>
                                </div>
                            </li>
                            
                            <li class="timeline-item">
                                <div class="timeline-marker" aria-hidden="true">5</div>
                                <div class="timeline-content">
                                    <h4>Receive Funds</h4>
                                    <p>Upon approval, funds will be disbursed according to the established procedures.</p>
                                </div>
                            </li>
                        </ol>
                    </div>
                    
                    <div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Required Documents</h3>
                            </div>
                            <div class="card-content">
                                <ul class="checklist">
                                    <li>✓ Completed application form</li>
                                    <li>✓ Student ID verification</li>
                                    <li>✓ Supporting documentation (medical reports, death certificates, etc.)</li>
                                    <li>✓ Bank account details</li>
                                    <li>✓ Academic transcript (if required)</li>
                                    <li>✓ Proof of SWF contribution payments</li>
                                </ul>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('register') }}" class="btn btn-primary btn-large">Start Your Application</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="content-section" id="contact" aria-labelledby="contact-heading">
            <div class="container">
                <h2 id="contact-heading" class="text-center mb-xl">Contact Us</h2>
                
                <div class="two-column">
                    <!-- Contact Form -->
                    <div>
                        <h3>Send us a Message</h3>
                        <form class="contact-form" data-validate method="POST" action="{{ route('contact.submit') }}" novalidate>
                            @csrf
                            
                            <!-- Success/Error Messages -->
                            @if(session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            @if(session('error'))
                                <div class="alert alert-error" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            
                            <div class="form-group">
                                <label for="contact_name" class="form-label required">Full Name</label>
                                <input 
                                    type="text" 
                                    id="contact_name" 
                                    name="name" 
                                    class="form-input @error('name') error @enderror" 
                                    value="{{ old('name') }}"
                                    required 
                                    minlength="2"
                                    maxlength="100"
                                    autocomplete="name"
                                    aria-describedby="contact_name-help"
                                >
                                <span id="contact_name-help" class="form-help">Enter your full name as it appears on your student records</span>
                                @error('name')
                                    <span class="form-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_email" class="form-label required">Email Address</label>
                                <input 
                                    type="email" 
                                    id="contact_email" 
                                    name="email" 
                                    class="form-input @error('email') error @enderror" 
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    aria-describedby="contact_email-help"
                                >
                                <span id="contact_email-help" class="form-help">We'll use this email to respond to your inquiry</span>
                                @error('email')
                                    <span class="form-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_student_id" class="form-label">Student ID</label>
                                <input 
                                    type="text" 
                                    id="contact_student_id" 
                                    name="student_id" 
                                    class="form-input @error('student_id') error @enderror" 
                                    value="{{ old('student_id') }}"
                                    data-validation="student-id"
                                    pattern="[0-9]{12}"
                                    maxlength="12"
                                    placeholder="e.g., 202012345678"
                                    aria-describedby="contact_student_id-help"
                                >
                                <span id="contact_student_id-help" class="form-help">Optional: 12-digit student ID for faster assistance</span>
                                @error('student_id')
                                    <span class="form-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_message" class="form-label required">Message</label>
                                <textarea 
                                    id="contact_message" 
                                    name="message" 
                                    class="form-textarea @error('message') error @enderror" 
                                    rows="5" 
                                    required
                                    minlength="10"
                                    maxlength="1000"
                                    placeholder="Please describe your inquiry or question about the Student Welfare Fund..."
                                    aria-describedby="contact_message-help"
                                >{{ old('message') }}</textarea>
                                <span id="contact_message-help" class="form-help">Minimum 10 characters, maximum 1000 characters</span>
                                @error('message')
                                    <span class="form-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" aria-describedby="submit-help">
                                    <span class="btn-text">Send Message</span>
                                </button>
                                <span id="submit-help" class="form-help">We typically respond within 1-2 business days</span>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Contact Information -->
                    <div>
                        <h3>Get in Touch</h3>
                        <div class="contact-info">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/>
                                    </svg>
                                </div>
                                <div class="contact-details">
                                    <h4>Office Location</h4>
                                    <address>
                                        Campus Lifestyle Section<br>
                                        Universiti Kuala Lumpur<br>
                                        Royal College of Medicine Perak<br>
                                        No. 3, Jalan Greentown<br>
                                        30450 Ipoh, Perak, Malaysia
                                    </address>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" fill="currentColor"/>
                                    </svg>
                                </div>
                                <div class="contact-details">
                                    <h4>Email</h4>
                                    <p>
                                        <a href="mailto:sw.rcmp@unikl.edu.my" class="contact-link">
                                            sw.rcmp@unikl.edu.my
                                        </a>
                                    </p>
                                    <p class="contact-note">Primary contact for SWF inquiries</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" fill="currentColor"/>
                                    </svg>
                                </div>
                                <div class="contact-details">
                                    <h4>Phone</h4>
                                    <p>
                                        <a href="tel:+6052536200" class="contact-link">
                                            +60 5-253 6200
                                        </a>
                                    </p>
                                    <p class="contact-note">Main campus line</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" fill="currentColor"/>
                                        <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z" fill="currentColor"/>
                                    </svg>
                                </div>
                                <div class="contact-details">
                                    <h4>Office Hours</h4>
                                    <div class="office-hours">
                                        <div class="hours-row">
                                            <span class="day">Monday - Friday:</span>
                                            <span class="time">8:00 AM - 5:00 PM</span>
                                        </div>
                                        <div class="hours-row">
                                            <span class="day">Saturday:</span>
                                            <span class="time">8:00 AM - 12:00 PM</span>
                                        </div>
                                        <div class="hours-row">
                                            <span class="day">Sunday:</span>
                                            <span class="time">Closed</span>
                                        </div>
                                    </div>
                                    <p class="contact-note">Best time to visit: Tuesday - Thursday, 9:00 AM - 4:00 PM</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Map Container -->
                        <div class="map-container">
                            <h4>Find Us</h4>
                            <div class="map-wrapper">
                                <div class="map-placeholder" role="img" aria-label="Map showing UniKL RCMP location at No. 3, Jalan Greentown, 30450 Ipoh, Perak">
                                    <div class="map-content">
                                        <div class="map-icon">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="var(--primary-color)"/>
                                            </svg>
                                        </div>
                                        <p class="map-title">UniKL RCMP Campus</p>
                                        <p class="map-address">No. 3, Jalan Greentown<br>30450 Ipoh, Perak</p>
                                        <div class="map-actions">
                                            <a href="https://maps.google.com/?q=UniKL+RCMP+Ipoh+Perak" 
                                               target="_blank" 
                                               rel="noopener noreferrer" 
                                               class="btn btn-secondary btn-small">
                                                View on Google Maps
                                            </a>
                                            <a href="https://waze.com/ul?q=UniKL%20RCMP%20Ipoh" 
                                               target="_blank" 
                                               rel="noopener noreferrer" 
                                               class="btn btn-secondary btn-small">
                                                Open in Waze
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="map-info">
                                <p><strong>Parking:</strong> Visitor parking available on campus</p>
                                <p><strong>Public Transport:</strong> Accessible via local bus routes</p>
                                <p><strong>Landmarks:</strong> Near Greentown Business Centre and Ipoh Parade</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer" role="contentinfo">
        <div class="footer-content">
            <!-- University Information -->
            <div class="footer-section footer-branding">
                <div class="footer-logo">
                    <img src="/assets/images/logos/unikl-rcmp.png" alt="Universiti Kuala Lumpur Royal College of Medicine Perak logo" class="footer-logo-img lazy-image" loading="lazy" width="150" height="45">
                </div>
                <h3>UniKL RCMP</h3>
                <p class="footer-description">Universiti Kuala Lumpur Royal College of Medicine Perak is committed to providing quality medical education and supporting our students through comprehensive welfare programs.</p>
                <p class="footer-mission">The Student Welfare Fund ensures that financial challenges do not impede academic success, fostering an environment where every student can thrive academically.</p>
                <div class="footer-accreditation">
                    <p class="footer-accreditation-text">Accredited by the Malaysian Medical Council</p>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="footer-section footer-navigation">
                <h3>Quick Navigation</h3>
                <div class="footer-nav-columns">
                    <div class="footer-nav-column">
                        <h4>Main Pages</h4>
                        <ul class="footer-links">
                            <li><a href="#home">Home</a></li>
                            <li><a href="#about">About SWF</a></li>
                            <li><a href="#application">Apply for Fund</a></li>
                            <li><a href="#contact">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="footer-nav-column">
                        <h4>Student Portal</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('login') }}">Student Login</a></li>
                            <li><a href="{{ route('register') }}">Register Account</a></li>
                            <li><a href="/application-status">Check Application Status</a></li>
                            <li><a href="/faq">Frequently Asked Questions</a></li>
                        </ul>
                    </div>
                    <div class="footer-nav-column">
                        <h4>Resources</h4>
                        <ul class="footer-links">
                            <li><a href="/guidelines">SWF Guidelines</a></li>
                            <li><a href="/forms">Download Forms</a></li>
                            <li><a href="/announcements">Announcements</a></li>
                            <li><a href="/help">Help & Support</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Contact & Social -->
            <div class="footer-section footer-contact">
                <h3>Contact Information</h3>
                <div class="footer-contact-info">
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="footer-contact-details">
                            <span class="footer-contact-label">Email:</span>
                            <a href="mailto:sw.rcmp@unikl.edu.my" class="footer-contact-link">sw.rcmp@unikl.edu.my</a>
                        </div>
                    </div>
                    
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="footer-contact-details">
                            <span class="footer-contact-label">Phone:</span>
                            <a href="tel:+6052536200" class="footer-contact-link">+60 5-253 6200</a>
                        </div>
                    </div>
                    
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="footer-contact-details">
                            <span class="footer-contact-label">Address:</span>
                            <address class="footer-address">
                                No. 3, Jalan Greentown<br>
                                30450 Ipoh, Perak, Malaysia
                            </address>
                        </div>
                    </div>
                    
                    <div class="footer-contact-item">
                        <div class="footer-contact-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" fill="currentColor"/>
                                <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="footer-contact-details">
                            <span class="footer-contact-label">Office Hours:</span>
                            <div class="footer-hours">
                                <div>Mon-Fri: 8:00 AM - 5:00 PM</div>
                                <div>Sat: 8:00 AM - 12:00 PM</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="social-links">
                    <h4>Follow Us</h4>
                    <div class="social-icons">
                        <a href="https://facebook.com/unikl.rcmp" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="Follow us on Facebook">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="currentColor"/>
                            </svg>
                        </a>
                        
                        <a href="https://instagram.com/unikl.rcmp" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="Follow us on Instagram">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" fill="currentColor"/>
                            </svg>
                        </a>
                        
                        <a href="https://twitter.com/unikl_rcmp" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="Follow us on Twitter">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" fill="currentColor"/>
                            </svg>
                        </a>
                        
                        <a href="https://linkedin.com/school/unikl-rcmp" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="Follow us on LinkedIn">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" fill="currentColor"/>
                            </svg>
                        </a>
                        
                        <a href="https://youtube.com/c/uniklrcmp" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="Subscribe to our YouTube channel">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" fill="currentColor"/>
                            </svg>
                        </a>
                        
                        <a href="https://t.me/uniklrcmp" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="Join our Telegram channel">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.820 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" fill="currentColor"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <div class="footer-copyright">
                    <p>&copy; {{ date('Y') }} Universiti Kuala Lumpur Royal College of Medicine Perak. All rights reserved.</p>
                </div>
                <div class="footer-legal">
                    <a href="/privacy-policy">Privacy Policy</a>
                    <span class="footer-separator">|</span>
                    <a href="/terms-of-service">Terms of Service</a>
                    <span class="footer-separator">|</span>
                    <a href="/accessibility">Accessibility</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Additional CSS for timeline and contact styles -->
    <style>
        .timeline {
            position: relative;
            padding-left: var(--spacing-xl);
            list-style: none;
            counter-reset: timeline-counter;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--primary-color);
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: var(--spacing-lg);
            counter-increment: timeline-counter;
        }
        
        .timeline-marker {
            position: absolute;
            left: -23px;
            top: 0;
            width: 30px;
            height: 30px;
            background: var(--primary-color);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: var(--font-weight-bold);
            font-size: var(--font-size-small);
        }
        
        .timeline-content h4 {
            margin-bottom: var(--spacing-sm);
            color: var(--primary-color);
        }
        
        .checklist {
            list-style: none;
            padding: 0;
        }
        
        .checklist li {
            margin-bottom: var(--spacing-sm);
            color: var(--success);
        }
        
        .contact-info {
            margin-bottom: var(--spacing-xl);
        }
        
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-xl);
            padding: var(--spacing-lg);
            background: var(--white);
            border-radius: var(--border-radius-md);
            box-shadow: var(--shadow-sm);
            transition: box-shadow var(--transition-fast);
        }
        
        .contact-item:hover {
            box-shadow: var(--shadow-md);
        }
        
        .contact-icon {
            flex-shrink: 0;
            width: 48px;
            height: 48px;
            background: var(--secondary-color);
            border-radius: var(--border-radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
        }
        
        .contact-details {
            flex: 1;
        }
        
        .contact-item h4 {
            color: var(--primary-color);
            margin-bottom: var(--spacing-sm);
            font-size: var(--font-size-h5);
            font-weight: var(--font-weight-semibold);
        }
        
        .contact-item address {
            font-style: normal;
            line-height: 1.6;
            color: var(--gray-700);
        }
        
        .contact-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: var(--font-weight-medium);
            transition: color var(--transition-fast);
        }
        
        .contact-link:hover,
        .contact-link:focus {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .contact-note {
            font-size: var(--font-size-small);
            color: var(--gray-600);
            margin-top: var(--spacing-xs);
            margin-bottom: 0;
        }
        
        .office-hours {
            margin-bottom: var(--spacing-sm);
        }
        
        .hours-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--spacing-xs);
            padding: var(--spacing-xs) 0;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .hours-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .day {
            font-weight: var(--font-weight-medium);
            color: var(--gray-800);
        }
        
        .time {
            color: var(--gray-700);
        }
        
        .map-container {
            margin-top: var(--spacing-2xl);
        }
        
        .map-container h4 {
            color: var(--primary-color);
            margin-bottom: var(--spacing-md);
            font-size: var(--font-size-h5);
        }
        
        .map-wrapper {
            margin-bottom: var(--spacing-lg);
        }
        
        .map-placeholder {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #f0f0f0 100%);
            padding: var(--spacing-2xl);
            text-align: center;
            border-radius: var(--border-radius-lg);
            border: 2px solid var(--gray-300);
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .map-content {
            max-width: 300px;
        }
        
        .map-icon {
            margin-bottom: var(--spacing-md);
        }
        
        .map-title {
            font-size: var(--font-size-h5);
            font-weight: var(--font-weight-semibold);
            color: var(--primary-color);
            margin-bottom: var(--spacing-sm);
        }
        
        .map-address {
            color: var(--gray-700);
            margin-bottom: var(--spacing-lg);
            line-height: 1.5;
        }
        
        .map-actions {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-sm);
        }
        
        .map-info {
            background: var(--gray-50);
            padding: var(--spacing-md);
            border-radius: var(--border-radius-md);
            border-left: 4px solid var(--accent-color);
        }
        
        .map-info p {
            margin-bottom: var(--spacing-sm);
            font-size: var(--font-size-small);
            color: var(--gray-700);
        }
        
        .map-info p:last-child {
            margin-bottom: 0;
        }
        
        .social-links {
            margin-top: var(--spacing-md);
        }
        
        /* Responsive Design for Contact Section */
        @media (max-width: 767px) {
            .contact-item {
                flex-direction: column;
                text-align: center;
                gap: var(--spacing-sm);
            }
            
            .contact-icon {
                align-self: center;
            }
            
            .hours-row {
                flex-direction: column;
                text-align: left;
                gap: var(--spacing-xs);
            }
            
            .map-placeholder {
                padding: var(--spacing-lg);
                min-height: 250px;
            }
            
            .map-actions {
                gap: var(--spacing-xs);
            }
            
            .map-actions .btn {
                font-size: var(--font-size-small);
                padding: var(--spacing-xs) var(--spacing-sm);
            }
        }
        
        @media (min-width: 768px) and (max-width: 1023px) {
            .map-actions {
                flex-direction: row;
                justify-content: center;
                gap: var(--spacing-sm);
            }
        }
    </style>
</body>
</html>