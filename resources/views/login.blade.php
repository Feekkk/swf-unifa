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
        :root{--primary-dark:#191970;--primary:#4169E1;--accent:#FFC000;--bg:#FAF9F6}
        body{background:var(--bg)}
        .auth-hero{min-height:100vh;display:flex;align-items:center}
        .brand-title{color:#fff;text-shadow:0 2px 8px rgba(0,0,0,.25)}
        .brand-sub{color:#eef2ff}
        .box.auth-box{max-width:480px;margin:0 auto;border-top:4px solid var(--primary)}
        .button.is-primary{background:var(--primary);border-color:var(--primary)}
        .button.is-primary:hover{background:var(--primary-dark);border-color:var(--primary-dark)}
        a{color:var(--primary)} a:hover{color:var(--primary-dark)}
    </style>
</head>
<body>
    <section class="hero is-medium" style="background:linear-gradient(135deg, rgba(25,25,112,.9), rgba(65,105,225,.75)), url('/assets/images/hero/bgm2.png') center/cover no-repeat;">
        <div class="hero-body auth-hero">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-5-tablet is-5-desktop">
                        <div class="has-text-centered mb-5">
                            <h1 class="title is-3 brand-title">UniKL RCMP Student Welfare Fund</h1>
                            <p class="subtitle is-6 brand-sub">Sign in to apply and manage your assistance</p>
                        </div>
                        <div class="box auth-box">
                            @if ($errors->any())
                                <article class="message is-danger">
                                    <div class="message-body">
                                        <ul style="margin-left:1rem;">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </article>
                            @endif

                            @if (session('status'))
                                <article class="message is-success">
                                    <div class="message-body">{{ session('status') }}</div>
                                </article>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="field">
                                    <label class="label">Student ID or Email</label>
                                    <div class="control has-icons-left">
                                        <input class="input" type="text" name="login" value="{{ old('login') }}" placeholder="RCMP123456 or your@email.com" required autocomplete="username">
                                        <span class="icon is-small is-left"><i class="fas fa-user"></i></span>
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">Password</label>
                                    <div class="control has-icons-left">
                                        <input class="input" type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                                        <span class="icon is-small is-left"><i class="fas fa-lock"></i></span>
                                    </div>
                                </div>

                                <div class="field is-flex is-justify-content-space-between is-align-items-center">
                                    <label class="checkbox">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
                                    </label>
                                    <a href="{{ route('password.request') }}">Forgot password?</a>
                                </div>

                                <div class="field">
                                    <button class="button is-primary is-fullwidth">Sign In</button>
                                </div>
                            </form>
                            <p class="has-text-centered is-size-7 mt-3">Don’t have an account? <a href="{{ route('register') }}"><strong>Create one</strong></a></p>
                        </div>
                        <p class="has-text-centered"><a class="button is-light is-small" href="/">Back to Home</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>