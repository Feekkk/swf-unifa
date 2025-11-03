<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="UniKL RCMP Student Welfare Fund - Financial assistance and support for students at Universiti Kuala Lumpur Royal College of Medicine Perak">
    <meta name="keywords" content="UniKL RCMP, Student Welfare Fund, Financial Aid, University, Medical College, Perak">
    <meta name="author" content="UniKL RCMP">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>RCMP Unifa - Financial Aids System</title>

    <!-- Bulma CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/images/logos/rcmp.png">   

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-dark: #191970;
            --primary: #4169E1;
            --accent: #FFC000;
            --black: #000000;
            --background: #FAF9F6;
            --text: #000000;
            --text-muted: #4a4a4a;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text);
            font-size: 15px;
            line-height: 1.65;
        }
        
        /* Increased section spacing */
        .section {
            padding: 5rem 1.5rem !important;
        }
        
        .section + .section {
            margin-top: 0;
        }
        
        .navbar {
            background-color: var(--primary-dark) !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            min-height: 4.5rem;
        }
        
        .navbar-brand .navbar-item,
        .navbar-menu .navbar-item {
            font-size: 0.95rem;
        }
        
        .navbar-brand .navbar-item,
        .navbar-menu .navbar-item {
            color: #fff !important;
        }

        .navbar-brand .brand-wrap { display: flex; align-items: center; }
        .navbar-brand .brand-wrap img.logo { max-height: 3.5rem; }
        .navbar-brand .brand-name { display: flex; flex-direction: column; margin-left: .5rem; }
        .navbar-brand .brand-name img.wordmark { height: 1.6rem; }
        .navbar-brand .brand-name .rcmp { margin-top: .15rem; font-weight: 800; letter-spacing: .5px; color: #fff; font-size: .85rem; }
        
        .navbar-menu .navbar-item:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* Theme overrides for Bulma */
        a { color: var(--primary); }
        a:hover { color: var(--primary-dark); }

        .button.is-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        .button.is-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .button.is-primary.is-light,
        .button.is-primary.is-outlined {
            background-color: transparent;
            color: var(--primary);
            border-color: var(--primary);
        }
        .button.is-warning {
            background-color: var(--accent);
            border-color: var(--accent);
            color: var(--black);
            font-weight: 600;
        }
        .button.is-warning:hover {
            background-color: #e6ad00;
            border-color: #e6ad00;
        }
        .button.is-warning.is-outlined,
        .button.is-warning.is-light {
            background-color: transparent;
            border-color: var(--accent);
            color: var(--black);
        }
        .tag.is-primary.is-light {
            background-color: rgba(65, 105, 225, 0.1);
            color: var(--primary-dark);
            border: 1px solid rgba(65, 105, 225, 0.25);
        }
        .title { 
            color: var(--primary-dark);
            font-family: 'Montserrat', sans-serif;
        }
        .title.is-1 { font-size: 2rem !important; }
        .title.is-2 { font-size: 1.75rem !important; }
        .title.is-3 { font-size: 1.375rem !important; }
        .title.is-4 { font-size: 1.125rem !important; }
        .title.is-5 { font-size: 1rem !important; }
        .title.is-6 { font-size: 0.9375rem !important; }
        .card-header-title { color: var(--primary-dark); }
        .subtitle { 
            color: var(--text-muted);
            font-size: 1rem;
        }
        .card { background-color: #fff; }
        
        .hero-slideshow {
            position: relative;
            min-height: 70vh;
        }

        /* Hero overlay for better contrast */
        .hero.theme-hero { 
            position: relative;
            min-height: 55vh;
        }
        .hero.theme-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,.55), rgba(0,0,0,.35));
            z-index: 1;
        }
        .hero.theme-hero .hero-body { position: relative; z-index: 1; }
        /* Full-page sections */
        .fullpage { min-height: 100vh; display: flex; align-items: center; }
        
        .section-title {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 3rem;
            position: relative;
            padding-bottom: 1rem;
            font-size: 1.75rem !important;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            border-radius: 2px;
        }

        /* Role cards */
        .login-role-card { position: relative; border-radius: 16px; padding: 1.75rem; border: 1px solid rgba(65,105,225,.25); background:#fff; transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; height: 100%; }
        .login-role-card:hover { transform: translateY(-4px); box-shadow: 0 14px 28px rgba(0,0,0,.08); border-color: var(--primary); }
        .login-role-card .icon-wrap { width:64px; height:64px; border-radius:12px; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, rgba(65,105,225,.12), rgba(255,192,0,.12)); color: var(--primary); margin-bottom: .75rem; }
        .login-role-card .title { margin-bottom: .25rem; }
        .login-role-card .subtitle { margin-bottom: 0; }
        
        .custom-card {
            border-top: 4px solid var(--primary);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .custom-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            background: linear-gradient(135deg, rgba(65, 105, 225, 0.1), rgba(255, 192, 0, 0.1));
        }
        
        .icon-wrapper i {
            font-size: 2rem;
            color: var(--primary);
        }
        
        .price-amount {
            font-size: 2.75rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }
        
        .price-currency {
            font-size: 1.25rem;
            color: var(--primary);
            margin-right: 0.25rem;
        }
        
        .timeline-item {
            position: relative;
            padding-left: 3rem;
            margin-bottom: 2rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0.75rem;
            top: 0;
            bottom: -2rem;
            width: 2px;
            background: var(--primary);
        }
        
        .timeline-item:last-child::before {
            display: none;
        }
        
        .timeline-badge {
            position: absolute;
            left: 0;
            top: 0;
            width: 1.5rem;
            height: 1.5rem;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .committee-tag {
            margin: 0.5rem;
        }

        /* Introduction (non-card) */
        .intro-lead { display: flex; align-items: center; gap: 12px; margin-bottom: 1.5rem; }
        .intro-icon { display: inline-flex; align-items: center; justify-content: center; width: 46px; height: 46px; border-radius: 10px; background: linear-gradient(135deg, rgba(65, 105, 225, 0.12), rgba(255, 192, 0, 0.12)); color: var(--primary); }
        .intro-icon i { font-size: 20px; }
        .intro-timeline { list-style: none; margin: 0; padding-left: 26px; position: relative; }
        .intro-timeline::before { content: ''; position: absolute; left: 10px; top: 0; bottom: 0; width: 2px; background: var(--primary); }
        .intro-timeline-item { display: grid; grid-template-columns: 36px 1fr; gap: 12px; margin-bottom: 18px; align-items: start; }
        .intro-timeline-item .dot { width: 32px; height: 32px; border-radius: 50%; background: var(--primary); color: #fff; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(65, 105, 225, 0.25); }
        .intro-timeline-item .dot i { font-size: 14px; }

        /* Committee cloud (non-card) */
        .committee-heading-icon { display: inline-flex; width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg, rgba(65, 105, 225, 0.12), rgba(255, 192, 0, 0.12)); color: var(--primary); align-items: center; justify-content: center; font-size: 24px; margin-bottom: 2rem !important; }
        .committee-cloud { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; max-width: 900px; margin: 0 auto; }
        .committee-chip { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 999px; border: 1px solid rgba(65, 105, 225, 0.25); background: rgba(65, 105, 225, 0.05); color: var(--primary-dark); font-weight: 500; font-size: 0.9rem; transition: transform .2s ease, box-shadow .2s ease, background .2s ease; }
        .committee-chip i { color: var(--primary); font-size: 14px; }
        .committee-chip:hover { transform: translateY(-2px); background: rgba(65, 105, 225, 0.1); box-shadow: 0 4px 12px rgba(0,0,0,.1); border-color: var(--primary); }
        
        .footer {
            background-color: var(--primary-dark);
            color: #fff;
        }
        
        .footer a {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .footer a:hover {
            color: var(--accent);
        }
        
        /* Objectives card centering and contrast */
        .objectives-card {
            max-width: 960px;
            margin: 0 auto;
        }
        .objectives-card .content,
        .objectives-card p { color: #1f2937; }
        .objectives-card h3 { color: var(--primary-dark); }

        /* Contact section improvements */
        .contact-container { max-width: 1100px; }
        .contact-card h3 { color: var(--primary-dark); }
        .contact-card p, .contact-card .content { color: #0f172a; }
        .contact-columns { align-items: flex-start; }
        /* Stronger icon color for visibility */
        .contact-columns .icon { color: var(--primary) !important; }

        /* Map card */
        .map-card{position:relative; overflow:hidden; border-radius:16px; border:1px solid #e6eaf1}
        .map-card img{display:block; width:100%; height:320px; object-fit:cover}
        .map-card::after{content:''; position:absolute; inset:0; background:linear-gradient(180deg, rgba(2,6,23,.2) 0%, rgba(2,6,23,.55) 75%)}
        .map-card .map-content{position:absolute; left:0; right:0; bottom:0; padding:1rem 1.25rem; z-index:1; color:#f8fafc}
        .map-card .map-content .title{color:#fff}
        .map-card .buttons .button{backdrop-filter:blur(6px)}

        /* Content text sizes */
        p, li, .content {
            font-size: 0.9375rem;
            line-height: 1.7;
        }
        
        .is-size-5 { font-size: 0.875rem !important; }
        .is-size-6 { font-size: 0.8125rem !important; }
        
        @media screen and (max-width: 768px) {
            .section {
                padding: 3rem 1rem !important;
            }
            .price-amount {
                font-size: 2rem;
            }
            .hero.theme-hero {
                min-height: 50vh;
            }
        }
    </style>

    @vite(['resources/js/polyfills.js', 'resources/js/app.js', 'resources/js/accessibility.js', 'resources/js/lazy-loading.js'])
</head>
<body>
    <!-- Skip Link -->
    <a href="#main-content" class="skip-link is-sr-only">Skip to main content</a>

    <!-- Navigation -->
    <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item brand-wrap" href="#home">
                    <img class="logo" src="/assets/images/logos/unikl-rcmp.png" alt="UniKL RCMP Logo">
                    <span class="brand-name">
                        <img class="wordmark" src="/assets/images/logos/unikl-word.png" alt="UniKL wordmark">
                        <span class="rcmp">RCMP</span>
                    </span>
                </a>
                
                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarMenu">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarMenu" class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item" href="#home">Home</a>
                    <a class="navbar-item" href="#about">About SWF</a>
                    <a class="navbar-item" href="#application">Apply for Fund</a>
                    <a class="navbar-item" href="#contact">Contact</a>
                </div>
                
                <div class="navbar-end">
                    @auth
                        <a class="navbar-item" href="{{ url('/dashboard') }}">Dashboard</a>
                    @else
                        <a class="navbar-item" href="{{ route('login') }}">Login</a>
                        <div class="navbar-item">
                            <a class="button is-warning is-rounded" href="#application">
                                <strong>Apply Now</strong>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
            </nav>

    <!-- Hero (Bulma) -->
    <section class="hero is-large theme-hero fullpage" id="home" style="background: url('/assets/images/hero/bgm.png') center/cover no-repeat;">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title is-1 has-text-white" style="text-shadow: 0 2px 8px rgba(0,0,0,.5)">UniKL RCMP Student Welfare Fund</h1>
                <p class="subtitle is-5 has-text-white" style="text-shadow: 0 1px 6px rgba(0,0,0,.5)">Financial assistance and student support designed to keep your studies on track.</p>
                <div class="buttons is-centered">
                    <a href="#application" class="button is-warning is-medium is-rounded"><strong>Apply for Financial Aid</strong></a>
                    <a href="#about" class="button is-light is-medium is-rounded">About the Fund</a>
        </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main id="main-content">
        <!-- Introduction Section -->
        <section class="section" id="about">
            <div class="container">
                <h2 class="title is-2 has-text-centered section-title">Introduction</h2>
                
                <div class="columns is-vcentered intro-grid">
                    <div class="column is-7">
                        <div class="intro-lead">
                            <span class="intro-icon"><i class="fas fa-university"></i></span>
                            <h3 class="title is-3">Tabung Kebajikan Siswa (TKS) / Student Welfare Fund (SWF)</h3>
                        </div>
                        <p class="is-size-5 has-text-justified">
                            The Student Welfare Fund (SWF) ensures financial challenges do not hinder UniKL students from succeeding in their studies. It supports emergencies, medical conditions or injuries, and bereavement with compassionate, efficient processes.
                        </p>
                    </div>
                    <div class="column is-5">
                        <ul class="intro-timeline">
                            <li class="intro-timeline-item">
                                <span class="dot"><i class="fas fa-calendar-alt"></i></span>
                    <div>
                                    <p class="has-text-weight-semibold mb-1">Established: September 30, 2005</p>
                                    <p class="is-size-6 has-text-grey-dark">Endorsed and approved by the Management of UniKL</p>
                            </div>
                            </li>
                            <li class="intro-timeline-item">
                                <span class="dot"><i class="fas fa-retweet"></i></span>
                                <div>
                                    <p class="has-text-weight-semibold mb-1">Rebranded: December 12, 2017</p>
                                    <p class="is-size-6 has-text-grey-dark">Approved on TMM 30th Jan 2018 (TMM NO.125 (2/2018))</p>
                            </div>
                            </li>
                            <li class="intro-timeline-item">
                                <span class="dot"><i class="fas fa-users-cog"></i></span>
                                <div>
                                    <p class="has-text-weight-semibold mb-1">Management</p>
                                    <p class="is-size-6 has-text-grey-dark">Campus Lifestyle Division and Campus Lifestyle Section manage SWF operations.</p>
                        </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Objectives Section -->
        <section class="section has-background-light">
            <div class="container">
                <h2 class="title is-2 has-text-centered section-title">SWF UniKL RCMP Objectives</h2>
                
                <div class="card custom-card">
                        <div class="card-content">
                        <div class="content has-text-centered">
                            <div class="icon-wrapper">
                                <i class="fas fa-bullseye"></i>
                        </div>
                            <h3 class="title is-3">Our Primary Objective</h3>
                            <p class="is-size-5 has-text-justified">
                            The Student Welfare Fund (SWF) at UniKL RCMP exists to ensure that no student’s academic journey is disrupted by unforeseen financial hardship. 
                            Established as Tabung Kebajikan Siswa (TKS) in 2005 and rebranded as SWF in 2017, the fund provides timely, compassionate assistance for 
                            emergencies, medical needs or injuries, bereavement, and other critical circumstances that may affect students’ well‑being and continuity of study.
                        </p>
<p class="is-size-6 has-text-justified">
  SWF is managed by the Campus Lifestyle Division and Campus Lifestyle Section with transparent processes and clear eligibility guidelines. 
  Funds are sourced from student contributions and are channeled directly to support essential needs such as immediate living expenses, treatment and recovery support, 
  transportation for urgent situations, and other welfare-related costs. Our objective is simple: to give students a reliable safety net so they can stay focused 
  on their learning, personal growth, and contribution to the campus community.
</p>
    <p class="is-size-5 has-text-justified">
  If you or a fellow student is facing an unexpected financial challenge, please review the application steps below and prepare the required documents. 
  Our team is ready to guide you through the process with confidentiality and care.
</p>
                    </div>
                        </div>
                        </div>
                    </div>
        </section>

        <!-- Student Contribution Section -->
        <section class="section" id="contribution">
            <div class="container">
                <h2 class="title is-2 has-text-centered section-title">Student Contribution SWF</h2>
                
                <div class="columns is-multiline">
                    <div class="column is-half">
                        <div class="card custom-card">
                            <div class="card-content has-text-centered">
                                <div class="icon-wrapper">
                                    <i class="fas fa-user-graduate"></i>
                        </div>
                                <h3 class="title is-4">Local Students</h3>
                                <div class="has-text-primary">
                                    <span class="price-currency">RM</span>
                                    <span class="price-amount">30.00</span>
                                </div>
                                <p class="has-text-weight-semibold mb-3">per semester</p>
                                <p class="is-size-6">The fund collection is based on SWF fees collected from registered students.</p>
                            </div>
                        </div>
                    </div>

                    <div class="column is-half">
                        <div class="card custom-card">
                            <div class="card-content has-text-centered">
                                <div class="icon-wrapper">
                                    <i class="fas fa-globe-americas"></i>
                        </div>
                                <h3 class="title is-4">International Students</h3>
                                <div class="has-text-primary">
                                    <span class="price-currency">RM</span>
                                    <span class="price-amount">50.00</span>
                                </div>
                                <p class="has-text-weight-semibold mb-3">per semester</p>
                                <p class="is-size-6">The fund collection is based on SWF fees collected from registered students.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Committee Section -->
        <section class="section has-background-light" id="committee">
            <div class="container">
                <h2 class="title is-2 has-text-centered section-title">SWF Campus Committee Members</h2>
                
                <div class="has-text-centered mb-5">
                    <span class="committee-heading-icon"><i class="fas fa-users"></i></span>
                </div>
                
                <div class="committee-cloud">
                    <span class="committee-chip"><i class="fas fa-user-tie"></i> Head of Campus / Dean</span>
                    <span class="committee-chip"><i class="fas fa-user-tie"></i> Deputy Dean, SDCL</span>
                    <span class="committee-chip"><i class="fas fa-user-tie"></i> Campus Lifestyle Head</span>
                    <span class="committee-chip"><i class="fas fa-user-tie"></i> Representative of Finance and Administration Department</span>
                    <span class="committee-chip"><i class="fas fa-user-tie"></i> Executive, Campus Lifestyle Section or any designated staff</span>
                    <span class="committee-chip"><i class="fas fa-user-tie"></i> President of Student Representative Committee or representative (by invitation)</span>
                </div>
            </div>
        </section>

        <!-- Application Process Section -->
        <section class="section" id="application">
            <div class="container">
                <h2 class="title is-2 has-text-centered section-title">How to Apply for Financial Aid</h2>
                
                <div class="columns">
                    <div class="column is-two-thirds">
                        <div class="content">
                            <h3 class="title is-4 mb-5">Step-by-Step Process</h3>
                            
                            <div class="timeline-item">
                                <span class="timeline-badge">1</span>
                                <div class="content">
                                    <h4 class="title is-5">Create Account</h4>
                                    <p>Register for an account using your student credentials and personal information.</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <span class="timeline-badge">2</span>
                                <div class="content">
                                    <h4 class="title is-5">Complete Application</h4>
                                    <p>Fill out the comprehensive application form with accurate details about your situation.</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <span inv="timeline-badge">3</span>
                                <div class="content">
                                    <h4 class="title is-5">Submit Documentation</h4>
                                    <p>Upload required supporting documents such as medical reports, receipts, or certificates.</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <span class="timeline-badge">4</span>
                                <div class="content">
                                    <h4 class="title is-5">Committee Review</h4>
                                    <p>Your application will be reviewed by the SWF Campus Committee for approval.</p>
                                </div>
                            </div>
                            
                            <div class="timeline-item">
                                <span class="timeline-badge">5</span>
                                <div class="content">
                                    <h4 class="title is-5">Receive Funds</h4>
                                    <p>Upon approval, funds will be disbursed according to the established procedures.</p>
                                </div>
                    </div>
                            </div>
                    </div>
                    
                    <div class="column">
                        <div class="card custom-card">
                            <header class="card-header">
                                <p class="card-header-title">
                                    Required Documents
                                </p>
                            </header>
                            <div class="card-content">
                                <div class="content">
                                    <ul>
                                        <li><i class="fas fa-check-circle has-text-success mr-2"></i>Completed application form</li>
                                        <li><i class="fas fa-check-circle has-text-success mr-2"></i>Student ID verification</li>
                                        <li><i class="fas fa-check-circle has-text-success mr-2"></i>Supporting documentation (medical reports, death certificates, etc.)</li>
                                        <li><i class="fas fa-check-circle has-text-success mr-2"></i>Bank account details</li>
                                        <li><i class="fas fa-check-circle has-text-success mr-2"></i>Academic transcript (if required)</li>
                                        <li><i class="fas fa-check-circle has-text-success mr-2"></i>Proof of SWF contribution payments</li>
                                </ul>
                            </div>
                            </div>
                            <footer class="card-footer">
                                <a href="{{ route('register') }}" class="card-footer-item button is-primary is-fullwidth">
                                    <strong>Start Your Application</strong>
                                </a>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="section has-background-light" id="contact">
            <div class="container contact-container">
                <h2 class="title is-2 has-text-centered section-title">Contact</h2>
                
                <div class="columns mt-6 contact-columns is-variable is-6">
                    <div class="column">
                        <h3 class="title is-4 mb-4">Get in Touch</h3>
                        
                        <div class="content">
                            <div class="media">
                                <figure class="media-left">
                                    <span class="icon is-large has-text-primary">
                                        <i class="fas fa-map-marker-alt fa-2x"></i>
                                    </span>
                                </figure>
                                <div class="media-content">
                                    <p class="title is-6">Office Location</p>
                                    <address>
                                        Universiti Kuala Lumpur<br>
                                        Royal College of Medicine Perak<br>
                                        No. 3, Jalan Greentown<br>
                                        30450 Ipoh, Perak, Malaysia
                                    </address>
                                </div>
                            </div>
                            
                            <div class="media">
                                <figure class="media-left">
                                    <span class="icon is-large has-text-primary">
                                        <i class="fas fa-envelope fa-2x"></i>
                                    </span>
                                </figure>
                                <div class="media-content">
                                    <p class="title is-6">Email</p>
                                    <p>
                                        <a href="mailto:sw.rcmp@unikl.edu.my">sw.rcmp@unikl.edu.my</a><br>
                                        <small class="has-text-grey">Primary contact for SWF inquiries</small>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="media">
                                <figure class="media-left">
                                    <span class="icon is-large has-text-primary">
                                        <i class="fas fa-phone fa-2x"></i>
                                    </span>
                                </figure>
                                <div class="media-content">
                                    <p class="title is-6">Phone</p>
                                    <p>
                                        <a href="tel:+6052536200">+60 5-253 6200</a><br>
                                        <small class="has-text-grey">Main campus line</small>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="media">
                                <figure class="media-left">
                                    <span class="icon is-large has-text-primary">
                                        <i class="fas fa-clock fa-2x"></i>
                                    </span>
                                </figure>
                                <div class="media-content">
                                    <p class="title is-6">Office Hours</p>
                                    <p>
                                        <strong>Monday - Friday:</strong> 8:00 AM - 5:00 PM<br>
                                        <strong>Saturday:</strong> 8:00 AM - 12:00 PM<br>
                                        <strong>Sunday:</strong> Closed<br>
                                        <small class="has-text-grey">Best time to visit: Tuesday - Thursday, 9:00 AM - 4:00 PM</small>
                                    </p>
                                </div>
                                        </div>
                            </div>
                        </div>
                        
                    <div class="column">
                        <h3 class="title is-4 mb-4">Find Us</h3>
                        <div class="map-card">
                            <img src="/assets/images/rcmp-map.png" alt="UniKL RCMP Campus Map">
                            <div class="map-content">
                                <p class="title is-5">UniKL RCMP Campus</p>
                                <p class="subtitle is-6" style="color:#e2e8f0">No. 3, Jalan Greentown • 30450 Ipoh, Perak</p>
                                <div class="buttons is-left mt-2">
                                    <a href="https://maps.google.com/?q=UniKL+RCMP+Ipoh+Perak" class="button is-light is-small">
                                        <span class="icon"><i class="fab fa-google"></i></span>
                                        <span>Google Maps</span>
                                    </a>
                                    <a href="https://waze.com/ul?q=UniKL%20RCMP%20Ipoh" class="button is-light is-small">
                                        <span class="icon"><i class="fab fa-waze"></i></span>
                                        <span>Waze</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="columns">
                <div class="column is-4">
                    <div class="mb-3">
                        <img src="/assets/images/logos/rcmp-white.png" alt="UniKL RCMP White Logo" style="height:90px">
                    </div>
                    <p class="has-text-white-ter">Universiti Kuala Lumpur Royal College of Medicine Perak is committed to providing quality medical education and supporting our students through comprehensive welfare programs.</p>
                    <p class="tag is-warning mt-4">Accredited by the Malaysian Medical Council</p>
            </div>
            
                <div class="column is-2">
                    <h4 class="title is-6 has-text-white mb-4">Main Pages</h4>
                    <ul>
                            <li><a href="#home">Home</a></li>
                            <li><a href="#about">About SWF</a></li>
                            <li><a href="#application">Apply for Fund</a></li>
                            <li><a href="#contact">Contact Us</a></li>
                        </ul>
                    </div>
                
                <div class="column is-2">
                    <h4 class="title is-6 has-text-white mb-4">Student Portal</h4>
                    <ul>
                            <li><a href="{{ route('login') }}">Student Login</a></li>
                            <li><a href="{{ route('register') }}">Register Account</a></li>
                        <li><a href="/application-status">Check Status</a></li>
                        <li><a href="/faq">FAQ</a></li>
                        </ul>
            </div>
            
                <div class="column is-4">
                    <h4 class="title is-6 has-text-white mb-4">Contact Information</h4>
                    <p><i class="fas fa-envelope mr-2"></i><a href="mailto:sw.rcmp@unikl.edu.my">sw.rcmp@unikl.edu.my</a></p>
                    <p><i class="fas fa-phone mr-2"></i><a href="tel:+6052536200">+60 5-253 6200</a></p>
                    <p><i class="fas fa-map-marker-alt mr-2"></i>No. 3, Jalan Greentown, 30450 Ipoh, Perak</p>
                    <p><i class="fas fa-clock mr-2"></i>Mon-Fri: 8:00 AM - 5:00 PM</p>
                    
                    <div class="buttons mt-4">
                        <a href="https://facebook.com/unikl.rcmp" class="button is-outlined is-light">
                            <span class="icon"><i class="fab fa-facebook"></i></span>
                        </a>
                        <a href="https://instagram.com/unikl.rcmp" class="button is-outlined is-light">
                            <span class="icon"><i class="fab fa-instagram"></i></span>
                        </a>
                        <a href="https://twitter.com/unikl_rcmp" class="button is-outlined is-light">
                            <span class="icon"><i class="fab fa-twitter"></i></span>
                        </a>
                        <a href="https://linkedin.com/school/unikl-rcmp" class="button is-outlined is-light">
                            <span class="icon"><i class="fab fa-linkedin"></i></span>
                        </a>
                </div>
            </div>
        </div>
        
            <div class="content has-text-centered mt-5 pt-5" style="border-top: 1px solid rgba(255,255,255,0.2);">
                <p class="has-text-white-ter">
                    &copy; {{ date('Y') }} Universiti Kuala Lumpur Royal College of Medicine Perak. All rights reserved.
                </p>
                <p class="mt-2">
                    <a href="/privacy-policy">Privacy Policy</a> |
                    <a href="/terms-of-service">Terms of Service</a> |
                    <a href="/accessibility">Accessibility</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Bulma JavaScript for mobile menu -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
            
            if (navbarBurgers.length > 0) {
                navbarBurgers.forEach(el => {
                    el.addEventListener('click', () => {
                        const target = el.dataset.target;
                        const $target = document.getElementById(target);
                        
                        el.classList.toggle('is-active');
                        $target.classList.toggle('is-active');
                    });
                });
            }

            // No slideshow (single static background)
        });
    </script>
</body>
</html>
