<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit User - Admin - RCMP Unifa</title>
    <link rel="icon" type="image/png" href="/assets/images/logos/rcmp.png">  
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
        .input, .select select { background-color: #fff !important; color: #000 !important; }
        
        /* Spacing tweaks */
        .dashboard-columns{gap:1.25rem}
        .footer { background-color: var(--primary-dark); color: #fff; }
        .footer a { color: rgba(255, 255, 255, 0.8); }
        .footer a:hover { color: var(--accent); }
        .password-toggle { cursor: pointer; }
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
                    <a class="navbar-item" href="/admin/dashboard">Dashboard</a>
                    <a class="navbar-item" href="/admin/users">Manage Users</a>
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
            <div class="level">
                <div class="level-left">
                    <div>
                        <h1 class="title is-3">Edit User Password</h1>
                        <p class="subtitle is-6">Change password for {{ $userName }} ({{ ucfirst($role) }})</p>
                    </div>
                </div>
                <div class="level-right">
                    <a href="{{ route('admin.users.index') }}" class="button is-light">
                        <span class="icon"><i class="fas fa-arrow-left"></i></span>
                        <span>Back to Users</span>
                    </a>
                </div>
            </div>

            @if ($errors->any())
                <div class="notification is-danger is-light mb-4" style="border-left: 4px solid #f14668;">
                    <button class="delete" onclick="this.parentElement.remove()"></button>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="notification is-success is-light mb-4" style="border-left: 4px solid #48c774;">
                    <button class="delete" onclick="this.parentElement.remove()"></button>
                    <span class="icon mr-2"><i class="fa-solid fa-circle-check"></i></span>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="notification is-danger is-light mb-4" style="border-left: 4px solid #f14668;">
                    <button class="delete" onclick="this.parentElement.remove()"></button>
                    <span class="icon mr-2"><i class="fa-solid fa-circle-exclamation"></i></span>
                    {{ session('error') }}
                </div>
            @endif

            <div class="columns">
                <div class="column is-8 is-offset-2">
                    <div class="card">
                        <div class="card-content">
                            <h2 class="title is-5 mb-4">User Information</h2>
                            <div class="field">
                                <label class="label has-text-black">Name</label>
                                <p class="is-size-6 has-text-black">{{ $userName }}</p>
                            </div>
                            <div class="field">
                                <label class="label has-text-black">Email</label>
                                <p class="is-size-6 has-text-black">{{ $user->email }}</p>
                            </div>
                            <div class="field">
                                <label class="label has-text-black">Role</label>
                                <p class="is-size-6">
                                    <span class="tag {{ $role === 'admin' ? 'is-warning' : 'is-info' }}">
                                        {{ ucfirst($role) }}
                                    </span>
                                </p>
                            </div>

                            <hr class="mt-5 mb-5">

                            <h2 class="title is-5 mb-4">Change Password</h2>
                            <p class="help mb-4" style="color:#000">Enter the new password for this user.</p>

                            <form method="POST" action="{{ route('admin.users.updatePassword', ['role' => $role, 'id' => $user->id]) }}">
                                @csrf

                                <div class="field">
                                    <label class="label has-text-black">New Password</label>
                                    <div class="control has-icons-right">
                                        <input class="input @error('password') is-danger @enderror" type="password" name="password" id="new-password" placeholder="Enter new password" required>
                                        <span class="icon is-small is-right password-toggle" data-toggle-target="new-password">
                                            <i class="fa-regular fa-eye"></i>
                                        </span>
                                    </div>
                                    @error('password')
                                        <p class="help is-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="field">
                                    <label class="label has-text-black">Confirm New Password</label>
                                    <div class="control has-icons-right">
                                        <input class="input @error('password') is-danger @enderror" type="password" name="password_confirmation" id="confirm-password" placeholder="Confirm new password" required>
                                        <span class="icon is-small is-right password-toggle" data-toggle-target="confirm-password">
                                            <i class="fa-regular fa-eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="field is-grouped">
                                    <div class="control">
                                        <button type="submit" class="button is-primary">
                                            <span class="icon"><i class="fa-solid fa-floppy-disk"></i></span>
                                            <span>Update Password</span>
                                        </button>
                                    </div>
                                    <div class="control">
                                        <a href="{{ route('admin.users.index') }}" class="button is-light">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
                        <li><a href="/admin/users">Manage Users</a></li>
                        <li><a href="/admin/profile/edit">Profile</a></li>
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
                burger.addEventListener('click', ()=>{ 
                    burger.classList.toggle('is-active'); 
                    menu.classList.toggle('is-active'); 
                });
            }

            // Password toggle functionality
            document.querySelectorAll('.password-toggle').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-toggle-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        });
    </script>
</body>
</html>

