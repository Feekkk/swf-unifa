<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - RCMP Unifa</title>
    <link rel="icon" type="image/png" href="/assets/images/logos/rcmp.png">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --primary-dark:#191970; --primary:#4169E1; --accent:#FFC000; --background:#FAF9F6; --text:#000 }
        body{background:var(--background); color:var(--text)}
        .navbar{background-color:var(--primary-dark)!important; min-height:4.5rem; box-shadow:0 2px 8px rgba(0,0,0,.1); transition:background-color 0.3s ease}
        .navbar:hover{background-color:rgba(25,25,112,0.95)!important}
        .navbar .navbar-item{color:#fff!important; position:relative; overflow:hidden; transition:background-color 0.3s ease}
        .navbar-menu .navbar-item::after{content:''; position:absolute; bottom:0; left:50%; width:0; height:2px; background:var(--accent); transform:translateX(-50%); transition:width 0.3s ease}
        .navbar-menu .navbar-item:hover::after{width:80%}
        .navbar-menu .navbar-item:hover{background-color:rgba(255,255,255,0.1)!important}
        .navbar-brand .navbar-item::after,
        .navbar-item.has-icon::after,
        .navbar-item.no-underline::after{display:none!important}
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
        .footer a { color: rgba(255, 255, 255, 0.8); }
        .footer a:hover { color: var(--accent); }
        
        /* Admin specific styles */
        .admin-card {
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .admin-card-icon {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, rgba(65, 105, 225, 0.1), rgba(255, 192, 0, 0.1));
        }
        .admin-card-icon i {
            font-size: 2.5rem;
            color: var(--primary);
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
                    <a class="navbar-item" href="/admin/applications">View Applications</a>
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
            <h1 class="title is-3">Admin Dashboard</h1>
            <p class="subtitle is-6">Welcome, {{ Auth::guard('admin')->user()->name ?? 'Admin' }}.</p>
            
            @if (session('success') || session('status'))
                <div class="notification is-success is-light mb-4" style="border-left: 4px solid #48c774;">
                    <button class="delete" onclick="this.parentElement.remove()"></button>
                    <span class="icon mr-2"><i class="fa-solid fa-circle-check"></i></span>
                    {{ session('success') ?? session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="notification is-danger is-light mb-4" style="border-left: 4px solid #f14668;">
                    <button class="delete" onclick="this.parentElement.remove()"></button>
                    <span class="icon mr-2"><i class="fa-solid fa-circle-exclamation"></i></span>
                    {{ session('error') }}
                </div>
            @endif

            <div class="columns is-variable is-5 dashboard-columns">
                <div class="column is-12-tablet is-8-desktop">
                    <h2 class="title is-5">Quick Actions</h2>
                    
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <div class="card admin-card">
                                <div class="card-content has-text-centered">
                                    <div class="admin-card-icon">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <h3 class="title is-5">View Applications</h3>
                                    <p class="subtitle is-6" style="color:#000">Review and manage student applications for financial aid</p>
                                    <div class="buttons is-centered mt-4">
                                        <a href="/admin/applications" class="button is-primary">
                                            <span class="icon"><i class="fas fa-eye"></i></span>
                                            <span>View All</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="card admin-card">
                                <div class="card-content has-text-centered">
                                    <div class="admin-card-icon">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <h3 class="title is-5">Manage Users</h3>
                                    <p class="subtitle is-6" style="color:#000">View and manage registered user accounts and settings</p>
                                    <div class="buttons is-centered mt-4">
                                        <a href="/admin/users" class="button is-accent">
                                            <span class="icon"><i class="fas fa-users-cog"></i></span>
                                            <span>Manage Registered Users</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="column is-12-tablet is-4-desktop">
                    <h2 class="title is-5">Profile Information</h2>
                    <div class="card">
                        <div class="card-content">
                            @php($admin = Auth::guard('admin')->user())
                            <div class="columns is-multiline is-mobile">
                                <div class="column is-12">
                                    <p class="is-size-7" style="color:#000">Name</p>
                                    <p class="is-size-6">{{ $admin->name ?? '-' }}</p>
                                </div>
                                <div class="column is-12">
                                    <p class="is-size-7" style="color:#000">Email</p>
                                    <p class="is-size-6">{{ $admin->email ?? '-' }}</p>
                                </div>
                                <div class="column is-12">
                                    <p class="is-size-7" style="color:#000">Role</p>
                                    <p class="is-size-6">
                                        <span class="tag is-info">Administrator</span>
                                    </p>
                                </div>
                                <div class="column is-12">
                                    <a href="/admin/profile/edit" class="button is-primary is-light is-fullwidth">
                                        <span class="icon"><i class="fa-solid fa-user-pen"></i></span>
                                        <span>Edit Profile</span>
                                    </a>
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
                        <li><a href="/">Home</a></li>
                        <li><a href="/#about">About SWF</a></li>
                        <li><a href="/#application">Apply for Fund</a></li>
                        <li><a href="/#contact">Contact Us</a></li>
                    </ul>
                </div>
                <div class="column is-2">
                    <h4 class="title is-6 has-text-white mb-4">Admin Portal</h4>
                    <ul>
                        <li><a href="/admin/dashboard">Dashboard</a></li>
                        <li><a href="/admin/applications">View Applications</a></li>
                        <li><a href="/admin/profile">Profile</a></li>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const burger = document.querySelector('.navbar-burger');
            const menu = document.getElementById('navbarMenu');
            if(burger && menu){
                burger.addEventListener('click', ()=>{ burger.classList.toggle('is-active'); menu.classList.toggle('is-active'); });
            }

            // Handle navbar hover effects
            const navbarItems = document.querySelectorAll('.navbar-menu .navbar-item');
            const brandItem = document.querySelector('.navbar-brand .navbar-item');
            const navbarEnd = document.querySelector('.navbar-end');
            
            navbarItems.forEach(item => {
                if (item.querySelector('.button') || item.querySelector('i.fa, i.fas, i.far')) {
                    item.classList.add('has-icon');
                }
            });
            
            if (brandItem) {
                brandItem.addEventListener('mouseenter', () => {
                    navbarItems.forEach(item => item.classList.add('no-underline'));
                });
                brandItem.addEventListener('mouseleave', () => {
                    navbarItems.forEach(item => item.classList.remove('no-underline'));
                });
            }
            
            if (burger) {
                burger.addEventListener('mouseenter', () => {
                    navbarItems.forEach(item => item.classList.add('no-underline'));
                });
                burger.addEventListener('mouseleave', () => {
                    navbarItems.forEach(item => item.classList.remove('no-underline'));
                });
            }
            
            if (navbarEnd) {
                navbarEnd.addEventListener('mouseenter', () => {
                    const menuItems = document.querySelectorAll('.navbar-start .navbar-item');
                    menuItems.forEach(item => item.classList.add('no-underline'));
                });
                navbarEnd.addEventListener('mouseleave', () => {
                    const menuItems = document.querySelectorAll('.navbar-start .navbar-item');
                    menuItems.forEach(item => item.classList.remove('no-underline'));
                });
            }
        });
    </script>
</body>
</html>
