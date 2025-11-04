<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profile - Committee - RCMP Unifa</title>
    <link rel="icon" type="image/png" href="/assets/images/logos/rcmp.png">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --primary-dark:#191970; --primary:#4169E1; --accent:#FFC000; --background:#FAF9F6; --text:#000 }
        body{background:var(--background); color:#000}
        .navbar{background-color:var(--primary-dark)!important; min-height:4.5rem; box-shadow:0 2px 8px rgba(0,0,0,.1)}
        .navbar .navbar-item{color:#fff!important}
        .card{background:#fff; border:1px solid #e5e7eb; box-shadow:0 4px 12px rgba(0,0,0,.06); color:#000}
        .card .title{color:var(--primary-dark)}
        .subtitle, .label, .help, .notification{color:#000}
        .button.is-primary{background-color:var(--primary); border-color:var(--primary); color:#fff}
        .button.is-primary:hover{filter:brightness(.95)}
        .footer { background-color: var(--primary-dark); color: #fff; }
        .footer a { color: rgba(255, 255, 255, 0.8); }
        .footer a:hover { color: var(--accent); }
        .grid-2{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem}
        @media(max-width:768px){.grid-2{grid-template-columns:1fr}}

        /* Form controls: force white background and black text */
        .input, .textarea{background:#ffffff!important; color:#000!important; border:1px solid #cbd5e1;}
        .select select{background:#ffffff!important; color:#000!important; border:1px solid #cbd5e1;}
        .select:not(.is-multiple):not(.is-loading)::after{border-color:#000}
        ::placeholder{color:#111827}
        option{color:#000}
        .password-toggle{cursor:pointer}

        /* Modal: ensure system (light) card styling */
        .modal-card{background:#ffffff!important; color:#000!important}
        .modal-card-head{background:#ffffff!important; color:#000!important; border-bottom:1px solid #e5e7eb}
        .modal-card-body{background:#ffffff!important; color:#000!important}
        .modal-card-foot{background:#ffffff!important; color:#000!important; border-top:1px solid #e5e7eb}
    </style>
    @vite(['resources/js/app.js'])
</head>
<body>
    <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="/">
                    <img src="/assets/images/logos/unikl-rcmp.png" alt="UniKL RCMP Logo" style="max-height: 3.5rem;">
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
                    <a class="navbar-item" href="/committee/dashboard">Dashboard</a>
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
            <h1 class="title is-3" style="color: #000;">Edit Profile</h1>
            <p class="subtitle is-6">Update your committee account information and password.</p>

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

            @if ($errors->any())
                <div class="notification is-danger is-light mb-4" style="border-left: 4px solid #f14668;">
                    <button class="delete" onclick="this.parentElement.remove()"></button>
                    <span class="icon mr-2"><i class="fa-solid fa-circle-exclamation"></i></span>
                    <ul style="margin-left:1rem">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php($committee = Auth::guard('committee')->user())
            <div class="card">
                <div class="card-content">
                    <form id="edit-profile-form" method="POST" action="{{ route('committee.profile.update') }}">
                        @csrf

                        <h2 class="title is-5 mb-4">Personal Information</h2>

                        <div class="field">
                            <label class="label">Name</label>
                            <input class="input" type="text" name="name" value="{{ old('name', $committee->name) }}" required>
                        </div>

                        <div class="field">
                            <label class="label">Email</label>
                            <input class="input" type="email" name="email" value="{{ old('email', $committee->email) }}" required>
                        </div>

                        <hr class="mt-5 mb-5">

                        <h2 class="title is-5 mb-4">Change Password</h2>
                        <p class="help mb-4" style="color:#000">Leave password fields blank if you don't want to change your password.</p>

                        <div class="field">
                            <label class="label">Current Password</label>
                            <div class="control has-icons-right">
                                <input class="input" type="password" name="current_password" id="current-password" placeholder="Enter current password">
                                <span class="icon is-small is-right password-toggle" data-toggle-target="current-password">
                                    <i class="fa-regular fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">New Password</label>
                            <div class="control has-icons-right">
                                <input class="input" type="password" name="password" id="new-password" placeholder="Enter new password">
                                <span class="icon is-small is-right password-toggle" data-toggle-target="new-password">
                                    <i class="fa-regular fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Confirm New Password</label>
                            <div class="control has-icons-right">
                                <input class="input" type="password" name="password_confirmation" id="confirm-password" placeholder="Confirm new password">
                                <span class="icon is-small is-right password-toggle" data-toggle-target="confirm-password">
                                    <i class="fa-regular fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <div class="field is-grouped mt-5">
                            <div class="control">
                                <button type="submit" class="button is-primary" id="save-btn">
                                    <span class="icon"><i class="fa-solid fa-floppy-disk"></i></span>
                                    <span>Save Changes</span>
                                </button>
                            </div>
                            <div class="control">
                                <a href="{{ route('committee.dashboard') }}" class="button is-light">
                                    <span class="icon"><i class="fa-solid fa-times"></i></span>
                                    <span>Cancel</span>
                                </a>
                            </div>
                        </div>
                    </form>
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
                    <h4 class="title is-6 has-text-white mb-4">Committee Portal</h4>
                    <ul>
                        <li><a href="/committee/dashboard">Dashboard</a></li>
                        <li><a href="/committee/profile/edit">Profile</a></li>
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

            // Password visibility toggles
            document.querySelectorAll('.password-toggle').forEach(t => {
                t.addEventListener('click', () => {
                    const targetId = t.getAttribute('data-toggle-target');
                    const input = document.getElementById(targetId);
                    if (!input) return;
                    const isPwd = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPwd ? 'text' : 'password');
                    const icon = t.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-eye');
                        icon.classList.toggle('fa-eye-slash');
                    }
                });
            });

            // Confirmation modal before submit
            const form = document.getElementById('edit-profile-form');
            const saveBtn = document.getElementById('save-btn');
            const modal = document.getElementById('confirm-modal');
            const modalBg = document.getElementById('confirm-modal-bg');
            const cancelBtn = document.getElementById('confirm-cancel');
            const cancelFooterBtn = document.getElementById('confirm-cancel-footer');
            const confirmBtn = document.getElementById('confirm-submit');

            function openModal(){ modal.classList.add('is-active'); }
            function closeModal(){ modal.classList.remove('is-active'); }

            if(form && saveBtn && modal){
                form.addEventListener('submit', (e)=>{
                    if(!modal.classList.contains('is-active')){
                        e.preventDefault();
                        openModal();
                    }
                });
            }
            [cancelBtn, cancelFooterBtn, modalBg].forEach(el=>{ if(el){ el.addEventListener('click', closeModal); } });
            if(confirmBtn){
                confirmBtn.addEventListener('click', ()=>{
                    closeModal();
                    form.submit();
                });
            }
        });
    </script>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirm-modal">
        <div class="modal-background" id="confirm-modal-bg"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title has-text-black" style="display:flex; align-items:center; gap:.5rem">
                    <img src="/assets/images/logos/unikl-rcmp.png" alt="UniKL RCMP" style="height:50px; width:auto">
                    <span>Confirm Profile Update</span>
                </p>
                <button class="delete" aria-label="close" id="confirm-cancel"></button>
            </header>
            <section class="modal-card-body has-text-black">
                <p class="has-text-black">
                    Are you sure you want to update your profile information? Please review all changes before submitting.
                </p>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-primary" id="confirm-submit">Save Changes</button>
                <button class="button is-light" id="confirm-cancel-footer">Cancel</button>
            </footer>
        </div>
    </div>
</body>
</html>

