<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - UniKL RCMP SWF</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root{--primary-dark:#191970;--primary:#4169E1;--background:#FAF9F6;--text:#0f172a;--text-muted:#475569}
        body{background:var(--background)}
        .auth-hero{background:radial-gradient(1200px 600px at 60% 40%, rgba(25,25,112,.35), rgba(25,25,112,0) 60%),linear-gradient(135deg, rgba(25,25,112,1), rgba(65,105,225,.75));border-radius:16px;min-height:560px;position:relative;overflow:hidden}
        .auth-hero .hero-content{position:absolute;inset:auto 0 0 0;padding:2rem;color:#e6fffb}
        .card.auth-card{background:#ffffff;border:1px solid #eef0f4;border-top:4px solid var(--primary);border-radius:16px;box-shadow:0 12px 24px rgba(0,0,0,.06)}
        .card.auth-card .title{color:var(--primary-dark)}
        .card.auth-card .subtitle{color:var(--text-muted)}
        .grid-2{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem}
        @media(max-width:768px){.grid-2{grid-template-columns:1fr}}
        .divider{height:1px;background:#eceff3;margin:1.25rem 0}
        /* Form controls theme */
        .label{color:var(--primary-dark);font-weight:600}
        .input, .textarea{background:#ffffff;color:var(--text);border:1px solid #dfe3ea;border-radius:12px}
        .select select{background:#ffffff;color:var(--text);border:1px solid #dfe3ea;border-radius:12px;padding:.6rem .9rem}
        .input:focus, .textarea:focus, .select select:focus{border-color:var(--primary);box-shadow:0 0 0 0.125em rgba(65,105,225,.25)}
        ::placeholder{color:#94a3b8}
        .navbar{background-color:var(--primary-dark)!important}
        .navbar .navbar-item{color:#fff!important}
        .button.is-primary{background-color:var(--primary);border-color:var(--primary)}
        .button.is-primary:hover{background-color:var(--primary-dark);border-color:var(--primary-dark)}
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
                <div class="navbar-start"><a class="navbar-item" href="/">Home</a></div>
                <div class="navbar-end"><a class="navbar-item" href="{{ route('login') }}">Login</a></div>
            </div>
        </div>
    </nav>

    <section class="section" style="padding-top:6rem">
    	<div class="container">
    		<div class="columns is-variable is-7 is-vcentered">
    			<div class="column is-6">
    				<div class="card auth-card">
    					<div class="card-content">
    						<h1 class="title is-3">Create an account</h1>
    						<p class="subtitle is-6">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
                @if ($errors->any())
                	<div class="notification is-danger is-light">
                		<ul style="margin-left:1rem">
                			@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                		</ul>
                	</div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="register-form">
                    @csrf
                    <div class="field">
                        <label class="label">Full Name</label>
                        <input class="input" type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="As per IC">
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <label class="label">Username</label>
                            <input class="input" type="text" name="username" value="{{ old('username') }}" required placeholder="3-20 letters/numbers">
                        </div>
                        <div class="field">
                            <label class="label">Email</label>
                            <input class="input" type="email" name="email" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <label class="label">Bank Name</label>
                            <div class="select is-fullwidth">
                            <select name="bank_name" required>
                                <option value="">Select your bank</option>
                                <option value="maybank">Maybank</option>
                                <option value="cimb">CIMB Bank</option>
                                <option value="public_bank">Public Bank</option>
                                <option value="rhb">RHB Bank</option>
                                <option value="hong_leong">Hong Leong Bank</option>
                                <option value="ambank">AmBank</option>
                                <option value="bsn">Bank Simpanan Nasional</option>
                                <option value="other">Other</option>
                            </select>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Bank Account Number</label>
                            <input class="input" type="text" name="bank_account_number" required>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <label class="label">Phone Number</label>
                            <input class="input" type="tel" name="phone_number" placeholder="+60xxxxxxxxx" required>
                        </div>
                        <div class="field">
                            <label class="label">Street Address</label>
                            <input class="input" type="text" name="street_address" required>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <label class="label">City</label>
                            <input class="input" type="text" name="city" required>
                        </div>
                        <div class="field">
                            <label class="label">State</label>
                            <div class="select is-fullwidth">
                            <select name="state" required>
                                <option value="">Select state</option>
                                <option value="johor">Johor</option>
                                <option value="kedah">Kedah</option>
                                <option value="kelantan">Kelantan</option>
                                <option value="melaka">Melaka</option>
                                <option value="negeri_sembilan">Negeri Sembilan</option>
                                <option value="perlis">Perlis</option>
                                <option value="pulau_pinang">Pulau Pinang</option>
                                <option value="sabah">Sabah</option>
                                <option value="sarawak">Sarawak</option>
                                <option value="selangor">Selangor</option>
                                <option value="terengganu">Terengganu</option>
                                <option value="wp_kuala_lumpur">WP Kuala Lumpur</option>
                                <option value="wp_labuan">WP Labuan</option>
                                <option value="wp_putrajaya">WP Putrajaya</option>
                            </select>
                            </div>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Postal Code</label>
                        <input class="input" type="text" name="postal_code" pattern="\d{5}" required>
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <label class="label">Student ID</label>
                            <input class="input" type="text" name="student_id" placeholder="RCMP123456" required>
                        </div>
                        <div class="field">
                            <label class="label">Course/Program</label>
                            <div class="select is-fullwidth">
                            <select name="course" required>
                                <option value="">Select course</option>
                                <option>MBBS</option>
                                <option>Diploma in Nursing</option>
                                <option>Diploma in Medical Assistant</option>
                                <option>Other</option>
                            </select>
                            </div>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <label class="label">Current Semester</label>
                            <div class="select is-fullwidth">
                            <select name="semester" required>
                                <option value="">Select semester</option>
                                @for($i=1;$i<=10;$i++)
                                    <option value="{{ $i }}">Semester {{ $i }}</option>
                                @endfor
                            </select>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Year of Study</label>
                            <div class="select is-fullwidth">
                            <select name="year_of_study" required>
                                <option value="">Select year</option>
                                @for($i=1;$i<=5;$i++)
                                    <option value="{{ $i }}">Year {{ $i }}</option>
                                @endfor
                            </select>
                            </div>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="field">
                            <label class="label">Password</label>
                            <input class="input" type="password" name="password" required>
                        </div>
                        <div class="field">
                            <label class="label">Confirm Password</label>
                            <input class="input" type="password" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="checkbox"><input type="checkbox" name="terms_accepted" required> I agree to the Terms & Privacy Policy</label>
                    </div>

                    <div class="field">
                        <button type="submit" class="button is-primary is-fullwidth">Create Account</button>
                    </div>
                </form>
                <div class="divider"></div>
                <p class="has-text-centered is-size-7">Already have an account? <a href="{{ route('login') }}"><strong>Sign in</strong></a></p>
            		</div>
    				</div>
    			</div>
    			<div class="column is-6">
    				<div class="auth-hero">
    					<div class="hero-content">
    						<p class="has-text-weight-semibold">UniKL RCMP • SWF</p>
    						<h2 class="title is-2 has-text-white">Smarter Support for Students</h2>
    						<p class="is-size-5" style="color:#c7f7f3">Fast, reliable assistance to keep you focused on success.</p>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </section>

    <footer class="footer" style="background-color: var(--primary-dark); color:#fff">
        <div class="container">
            <div class="columns">
                <div class="column is-6">
                    <h3 class="title is-5 has-text-white">UniKL RCMP</h3>
                    <p class="has-text-white-ter">Student Welfare Fund portal</p>
                </div>
                <div class="column is-6 has-text-right">
                    <a class="button is-light is-outlined" href="/">Back to Home</a>
                </div>
            </div>
            <div class="content has-text-centered" style="border-top:1px solid rgba(255,255,255,0.2);padding-top:1rem">
                <p class="has-text-white-ter">&copy; {{ date('Y') }} UniKL RCMP. All rights reserved.</p>
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