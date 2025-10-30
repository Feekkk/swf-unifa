<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - UniKL RCMP SWF</title>
    <!-- Bulma + Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root{--primary-dark:#191970;--primary:#4169E1;--accent:#FFC000;--background:#FAF9F6;--text:#0f172a;--text-muted:#475569}
        body{background:var(--background);color:var(--text)}
        .auth-hero{background:radial-gradient(1200px 600px at 60% 40%, rgba(25,25,112,.35), rgba(25,25,112,0) 60%),linear-gradient(135deg, rgba(25,25,112,1), rgba(65,105,225,.75));border-radius:16px;min-height:560px;position:relative;overflow:hidden}
        .auth-hero .hero-content{position:absolute;inset:auto 0 0 0;padding:2rem;color:#e6fffb}
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
                        <label class="label">Student ID or Email</label>
                        <div class="control">
                            <input class="input" type="text" name="login" value="{{ old('login') }}" placeholder="RCMP123456 or your@email.com" required autocomplete="username">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control">
                            <input class="input" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
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