<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - UniKL RCMP SWF</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --primary-dark:#191970; --primary:#4169E1; --accent:#FFC000; --background:#FAF9F6; --text:#000 }
        body{background:var(--background); color:var(--text)}
        .navbar{background-color:var(--primary-dark)!important; min-height:4.5rem; box-shadow:0 2px 8px rgba(0,0,0,.1)}
        .navbar .navbar-item{color:#fff!important}
        .navbar-brand .brand-wrap{display:flex; align-items:center}
        .navbar-brand .brand-wrap img.logo{max-height:3.5rem}
        .navbar-brand .brand-name{display:flex; flex-direction:column; margin-left:.5rem}
        .navbar-brand .brand-name img.wordmark{height:1.6rem}
        .navbar-brand .brand-name .rcmp{margin-top:.15rem; font-weight:800; letter-spacing:.5px; color:#fff; font-size:.85rem}

        /* Dashboard theming */
        .title, .subtitle{color:var(--text)}
        .card{background:#fff; border:1px solid rgba(0,0,0,.06); box-shadow:0 4px 12px rgba(0,0,0,.06)}
        .card .title{color:var(--primary-dark)}
        .section h2.title{color:var(--primary-dark)}
        .button.is-primary{background-color:var(--primary); border-color:var(--primary); color:#fff}
        .button.is-primary:hover{filter:brightness(.95)}
        .button.is-accent{background-color:var(--accent); border-color:var(--accent); color:#000}
        .button.is-accent:hover{filter:brightness(.95)}

        /* Spacing tweaks */
        .dashboard-columns{gap:1.25rem}
        .footer {
            background-color: var(--primary-dark);
            color: #fff;
        }
    </style>
    @vite(['resources/js/app.js'])
    @stack('head')
    </head>
<body>
    <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item brand-wrap" href="/">
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
                    <a class="navbar-item" href="/">Home</a>
                </div>
                <div class="navbar-end">
                    <a class="navbar-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none">@csrf</form>
                </div>
            </div>
        </div>
    </nav>

    <section class="section" style="padding-top:6rem">
        <div class="container">
            <h1 class="title is-3">Student Dashboard</h1>
            <p class="subtitle is-6">Welcome, {{ auth()->user()->display_name ?? 'Student' }}.</p>
            <div class="columns is-variable is-5 dashboard-columns">
                <div class="column is-12-tablet is-8-desktop">
                    <h2 class="title is-5">Application Aids Available</h2>
                    <div class="columns is-multiline">
                        <div class="column is-12-mobile is-6-tablet is-4-desktop">
                            <div class="card">
                                <div class="card-content">
                                    <p class="title is-6 mb-2">Category 1</p>
                                    <p class="content is-size-7">Short description for this aid category.</p>
                                    <div class="tags mt-2">
                                        <span class="tag is-info is-light">Aid</span>
                                        <span class="tag is-warning is-light">Open</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12-mobile is-6-tablet is-4-desktop">
                            <div class="card">
                                <div class="card-content">
                                    <p class="title is-6 mb-2">Category 2</p>
                                    <p class="content is-size-7">Short description for this aid category.</p>
                                    <div class="tags mt-2">
                                        <span class="tag is-info is-light">Aid</span>
                                        <span class="tag is-warning is-light">Open</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12-mobile is-6-tablet is-4-desktop">
                            <div class="card">
                                <div class="card-content">
                                    <p class="title is-6 mb-2">Category 3</p>
                                    <p class="content is-size-7">Short description for this aid category.</p>
                                    <div class="tags mt-2">
                                        <span class="tag is-info is-light">Aid</span>
                                        <span class="tag is-warning is-light">Open</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="buttons mt-2">
                                <a href="#" class="button is-primary"><span class="icon"><i class="fas fa-plus"></i></span><span>Make Application</span></a>
                                <a href="#" class="button is-accent is-light"><span class="icon"><i class="fas fa-folder-open"></i></span><span>My Applications</span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-12-tablet is-4-desktop">
                    <h2 class="title is-5">Profile View</h2>
                    <div class="card">
                        <div class="card-content">
                            @php($u = auth()->user())
                            <div class="columns is-multiline is-mobile">
                                <div class="column is-12">
                                    <p class="is-size-7 has-text-grey">Name</p>
                                    <p class="is-size-6">{{ $u->display_name ?? '-' }}</p>
                                </div>
                                <div class="column is-12">
                                    <p class="is-size-7 has-text-grey">Student ID</p>
                                    <p class="is-size-6">{{ $u->student_id ?? '-' }}</p>
                                </div>
                                <div class="column is-12">
                                    <p class="is-size-7 has-text-grey">Email</p>
                                    <p class="is-size-6">{{ $u->email ?? '-' }}</p>
                                </div>
                                <div class="column is-12">
                                    <p class="is-size-7 has-text-grey">Course</p>
                                    <p class="is-size-6">{{ $u->course ?? '-' }}</p>
                                </div>
                                <div class="column is-12">
                                    <p class="is-size-7 has-text-grey">Semester</p>
                                    <p class="is-size-6">{{ $u->semester ?? '-' }}</p>
                                </div>
                                <div class="column is-12">
                                    <p class="is-size-7 has-text-grey">State</p>
                                    <p class="is-size-6">{{ $u->state ?? '-' }}</p>
                                </div>
                                <div class="column is-12">
                                    <p class="is-size-7 has-text-grey">Bank Name</p>
                                    <p class="is-size-6">{{ $u->bank_name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @yield('content')
        </div>
    </section>

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
                        <a href="https://facebook.com/unikl.rcmp" target="_blank" class="button is-outlined is-light">
                            <span class="icon"><i class="fab fa-facebook"></i></span>
                        </a>
                        <a href="https://instagram.com/unikl.rcmp" target="_blank" class="button is-outlined is-light">
                            <span class="icon"><i class="fab fa-instagram"></i></span>
                        </a>
                        <a href="https://twitter.com/unikl_rcmp" target="_blank" class="button is-outlined is-light">
                            <span class="icon"><i class="fab fa-twitter"></i></span>
                        </a>
                        <a href="https://linkedin.com/school/unikl-rcmp" target="_blank" class="button is-outlined is-light">
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const burger = document.querySelector('.navbar-burger');
            const menu = document.getElementById('navbarMenu');
            if(burger && menu){
                burger.addEventListener('click', ()=>{ burger.classList.toggle('is-active'); menu.classList.toggle('is-active'); });
            }
        });
    </script>
</body>
</html>