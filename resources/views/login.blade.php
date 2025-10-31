<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - RCMP Unifa</title>
    <!-- Bulma + Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root{--primary-dark:#191970;--primary:#4169E1;--accent:#FFC000;--background:#FAF9F6;--text:#0f172a;--text-muted:#475569}
        body{background:var(--background);color:var(--text)}
        .auth-hero{background:radial-gradient(1200px 600px at 60% 40%, rgba(25,25,112,.35), rgba(25,25,112,0) 60%),linear-gradient(135deg, rgba(25,25,112,1), rgba(65,105,225,.75));border-radius:16px;min-height:560px;position:relative;overflow:hidden;border:1px solid rgba(255,255,255,.15);box-shadow:0 18px 40px rgba(0,0,0,.2)}
        .auth-hero::after{content:'';position:absolute;inset:0;background:linear-gradient(180deg, rgba(0,0,0,.25) 0%, rgba(0,0,0,.55) 70%);z-index:1}
        .auth-hero .hero-content{position:absolute;left:0;right:0;bottom:0;padding:2rem;color:#e6fffb;z-index:2}
        .auth-hero .brand{display:inline-flex;align-items:center;gap:.5rem;padding:.4rem .7rem;border-radius:999px;background:rgba(255,255,255,.14);backdrop-filter:blur(6px);border:1px solid rgba(255,255,255,.25);color:#f8fafc;font-weight:700}
        .auth-video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:0;filter:saturate(1.1)}
        .brand{display:flex;align-items:center;gap:.5rem;color:#e2e8f0;font-weight:700}
        .card.auth-card{background:#ffffff;border:1px solid #eef0f4;border-top:4px solid var(--primary);border-radius:16px;box-shadow:0 12px 24px rgba(0,0,0,.06)}
        .card.auth-card .title{color:var(--primary-dark)}
        .card.auth-card .subtitle{color:var(--text-muted)}
        .divider{height:1px;background:#eceff3;margin:1.25rem 0}
        /* Form controls theme */
        .label{color:var(--primary-dark);font-weight:600}
        .input, .textarea{background:#ffffff;color:var(--text);border:1px solid #dfe3ea;border-radius:12px}
        .select select{background:#ffffff;color:var(--text);border:1px solid #dfe3ea;border-radius:12px;padding:.6rem .9rem}
        .input:focus, .textarea:focus, .select select:focus{border-color:var(--primary);box-shadow:0 0 0 0.125em rgba(65,105,225,.25)}
        ::placeholder{color:#94a3b8}
        .navbar{background-color:var(--primary-dark)!important}
        .navbar .navbar-item{color:#fff!important}
        .button.is-primary{background-color:var(--primary);border-color:var(--primary);color:#fff}
        .button.is-primary:hover{background-color:var(--primary-dark);border-color:var(--primary-dark);color:#fff}
        .password-toggle{cursor:pointer}
        /* Role selector */
        .role-options{display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem}
        .role-option{display:flex;align-items:center;gap:.6rem;border:1px solid #dfe3ea;border-radius:12px;padding:.6rem .8rem;background:#fff;cursor:pointer;transition:border-color .2s ease, box-shadow .2s ease}
        .role-option .icon{color:var(--primary)}
        .role-option.is-active{border-color:var(--primary);box-shadow:0 0 0 0.125em rgba(65,105,225,.15)}
        @media(max-width:480px){.role-options{grid-template-columns:1fr}}
    </style>
</head>
<body>
    <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="/">
                    <img src="/assets/images/logos/unikl-rcmp.png" alt="UniKL RCMP Logo" style="max-height: 3rem;">
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
                    <a class="navbar-item" href="{{ route('register') }}">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="section" style="padding-top:6rem">
    	<div class="container">
    		<div class="columns is-variable is-7 is-vcentered">
    			<div class="column is-6">
    				<div class="card auth-card">
    					<div class="card-content">
    						<h1 class="title is-3">Welcome Back!</h1>
    						<p class="subtitle is-6">Sign in to your SWF account</p>
                @if ($errors->any())
                	<div class="notification is-danger is-light">
                		<ul style="margin-left:1rem">
                			@foreach ($errors->all() as $error)
                				<li>{{ $error }}</li>
                			@endforeach
                		</ul>
                	</div>
                @endif

                @if (session('status'))
                	<div class="notification is-success is-light">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="field">
                        <label class="label">Sign in as</label>
                        <div class="role-options">
                            <div id="role-student" class="role-option is-active" role="button" tabindex="0">
                                <span class="icon"><i class="fa-solid fa-user-graduate"></i></span>
                                <span>Student</span>
                            </div>
                            <div id="role-staff" class="role-option" role="button" tabindex="0">
                                <span class="icon"><i class="fa-solid fa-user-tie"></i></span>
                                <span>Staff</span>
                            </div>
                        </div>
                        <input type="hidden" name="user_type" id="user_type" value="student">
                    </div>

                    <div class="field" id="staff-role-field" style="display:none">
                        <label class="label">Staff role</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select id="staff_role" name="staff_role">
                                    <option value="approval">Approval</option>
                                    <option value="admin">Admin</option>
                                    <option value="committee">Committee</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Email or ID number</label>
                        <div class="control">
                            <input id="login-identifier" class="input" type="text" name="login" value="{{ old('login') }}" placeholder="RCMP123456 or your@email.com" required autocomplete="username">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control has-icons-right">
                            <input id="login-password" class="input" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                            <span class="icon is-small is-right password-toggle" data-toggle-target="login-password"><i class="fa-regular fa-eye"></i></span>
                        </div>
                    </div>

                    <div class="level">
                    	<div class="level-left">
                    		<label class="checkbox"><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me</label>
                    	</div>
                    	<div class="level-right">
                    		<a href="{{ route('password.request') }}">Forgot password?</a>
                    	</div>
                    </div>

                    <div class="field">
                        <button class="button is-primary is-fullwidth">Sign In</button>
                    </div>
                </form>
                <div class="divider"></div>
                <p class="has-text-centered is-size-7">Don’t have an account? <a href="{{ route('register') }}"><strong>Create one</strong></a></p>
            		</div>
    				</div>
    			</div>
    			<div class="column is-6">
                <div class="auth-hero">
                    <video class="auth-video" autoplay muted loop playsinline>
                        <source src="/assets/images/logos/logo-animation.mp4" type="video/mp4">
                    </video>
    					<div class="hero-content">
    						<p class="brand"><span class="icon"><i class="fa-solid fa-graduation-cap"></i></span> UniKL RCMP • SWF</p>
    						<h2 class="title is-2 has-text-white">Revolutionize Student Support</h2>
    						<p class="is-size-5" style="color:#c7f7f3">“Our SWF portal streamlines requests so students get help faster.”</p>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </section>

    <footer class="footer" style="background-color: var(--primary-dark); color:#fff">
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
                        <li><a href="/ #about">About SWF</a></li>
                        <li><a href="/ #application">Apply for Fund</a></li>
                        <li><a href="/ #contact">Contact Us</a></li>
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
                        <a href="https://facebook.com/unikl.rcmp" class="button is-outlined is-light"><span class="icon"><i class="fab fa-facebook"></i></span></a>
                        <a href="https://instagram.com/unikl.rcmp" class="button is-outlined is-light"><span class="icon"><i class="fab fa-instagram"></i></span></a>
                        <a href="https://twitter.com/unikl_rcmp" class="button is-outlined is-light"><span class="icon"><i class="fab fa-twitter"></i></span></a>
                        <a href="https://linkedin.com/school/unikl-rcmp" class="button is-outlined is-light"><span class="icon"><i class="fab fa-linkedin"></i></span></a>
                    </div>
                </div>
            </div>
            <div class="content has-text-centered mt-5 pt-5" style="border-top: 1px solid rgba(255,255,255,0.2);">
                <p class="has-text-white-ter">&copy; {{ date('Y') }} Universiti Kuala Lumpur Royal College of Medicine Perak. All rights reserved.</p>
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

            // Role toggle: Student vs Staff (supports preselection via ?type=...)
            const roleStudentBtn = document.getElementById('role-student');
            const roleStaffBtn = document.getElementById('role-staff');
            const roleInput = document.getElementById('user_type');
            const loginIdentifier = document.getElementById('login-identifier');
            const loginLabel = document.querySelector('label.label');
            const staffRoleField = document.getElementById('staff-role-field');
            const setActive = (isStudent) => {
                if (!roleStudentBtn || !roleStaffBtn || !roleInput) return;
                roleStudentBtn.classList.toggle('is-active', isStudent);
                roleStaffBtn.classList.toggle('is-active', !isStudent);
                roleInput.value = isStudent ? 'student' : 'staff';
                if (loginIdentifier && loginLabel) {
                    if (isStudent) {
                        loginIdentifier.setAttribute('placeholder', 'RCMP123456 or your@email.com');
                        loginLabel.textContent = 'Student ID or Email';
                    } else {
                        loginIdentifier.setAttribute('placeholder', 'your@email.com');
                        loginLabel.textContent = 'Staff Email';
                    }
                }
                if (staffRoleField) { staffRoleField.style.display = isStudent ? 'none' : ''; }
            };
            if (roleStudentBtn && roleStaffBtn) {
                const activateStudent = () => setActive(true);
                const activateStaff = () => setActive(false);
                roleStudentBtn.addEventListener('click', activateStudent);
                roleStaffBtn.addEventListener('click', activateStaff);
                roleStudentBtn.addEventListener('keydown', (e)=>{ if(e.key==='Enter'||e.key===' '){e.preventDefault();activateStudent();}});
                roleStaffBtn.addEventListener('keydown', (e)=>{ if(e.key==='Enter'||e.key===' '){e.preventDefault();activateStaff();}});
                const url = new URL(window.location.href);
                const type = (url.searchParams.get('type') || 'student').toLowerCase();
                setActive(type !== 'staff');
            }
        });
    </script>
</body>
</html>