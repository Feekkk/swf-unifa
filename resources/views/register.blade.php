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
        :root{--primary-dark:#191970;--primary:#4169E1;--accent:#FFC000;--bg:#FAF9F6}
        body{background:var(--bg)}
        .auth-hero{min-height:100vh;display:flex;align-items:center}
        .box.auth-box{max-width:720px;margin:0 auto;border-top:4px solid var(--primary)}
        .button.is-primary{background:var(--primary);border-color:var(--primary)}
        .button.is-primary:hover{background:var(--primary-dark);border-color:var(--primary-dark)}
        a{color:var(--primary)} a:hover{color:var(--primary-dark)}
        .step{background:rgba(65,105,225,.12);color:var(--primary-dark)}
    </style>
</head>
<body>
    <section class="hero is-medium" style="background:linear-gradient(135deg, rgba(25,25,112,.9), rgba(65,105,225,.75)), url('/assets/images/hero/bgm3.png') center/cover no-repeat;">
        <div class="hero-body auth-hero">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-7-desktop is-8-tablet">
                        <div class="has-text-centered mb-5">
                            <h1 class="title is-3 has-text-white">Create your SWF account</h1>
                            <p class="subtitle is-6 has-text-light">A quick 4-step form to get you started</p>
                        </div>
                        <div class="box auth-box">
                            @if ($errors->any())
                                <article class="message is-danger"><div class="message-body"><ul style="margin-left:1rem;">
                                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul></div></article>
                            @endif

                            <form method="POST" action="{{ route('register') }}" id="register-form">
                                @csrf

                                <!-- Step indicator -->
                                <nav class="steps is-small mb-5">
                                    <ul>
                                        <li class="step-item is-active"><a class="step-link">Personal</a></li>
                                        <li class="step-item"><a class="step-link">Contact</a></li>
                                        <li class="step-item"><a class="step-link">Academic</a></li>
                                        <li class="step-item"><a class="step-link">Security</a></li>
                                    </ul>
                                </nav>

                                <!-- Step 1 Personal -->
                                <div class="columns is-multiline step-pane is-active">
                                    <div class="column is-12">
                                        <label class="label">Full Name</label>
                                        <input class="input" type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="As per IC">
                                    </div>
                                    <div class="column is-6">
                                        <label class="label">Username</label>
                                        <input class="input" type="text" name="username" value="{{ old('username') }}" required>
                                        <p class="help">3-20 characters, letters and numbers only</p>
                                    </div>
                                    <div class="column is-6">
                                        <label class="label">Email</label>
                                        <input class="input" type="email" name="email" value="{{ old('email') }}" required>
                                    </div>
                                    <div class="column is-6">
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
                                    <div class="column is-6">
                                        <label class="label">Bank Account Number</label>
                                        <input class="input" type="text" name="bank_account_number" required>
                                    </div>
                                </div>

                                <!-- Step 2 Contact -->
                                <div class="columns is-multiline step-pane">
                                    <div class="column is-6">
                                        <label class="label">Phone Number</label>
                                        <input class="input" type="tel" name="phone_number" placeholder="+60xxxxxxxxx" required>
                                        <p class="help">Include country code</p>
                                    </div>
                                    <div class="column is-12">
                                        <label class="label">Street Address</label>
                                        <input class="input" type="text" name="street_address" required>
                                    </div>
                                    <div class="column is-4">
                                        <label class="label">City</label>
                                        <input class="input" type="text" name="city" required>
                                    </div>
                                    <div class="column is-4">
                                        <label class="label">State</label>
                                        <div class="select is-fullwidth">
                                            <select name="state" required>
                                                <option value="">Select state</option>
                                                <option value="perak">Perak</option>
                                                <option value="selangor">Selangor</option>
                                                <option value="johor">Johor</option>
                                                <option value="sabah">Sabah</option>
                                                <option value="sarawak">Sarawak</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="column is-4">
                                        <label class="label">Postal Code</label>
                                        <input class="input" type="text" name="postal_code" pattern="\d{5}" required>
                                    </div>
                                </div>

                                <!-- Step 3 Academic -->
                                <div class="columns is-multiline step-pane">
                                    <div class="column is-6">
                                        <label class="label">Student ID</label>
                                        <input class="input" type="text" name="student_id" placeholder="RCMP123456" required>
                                    </div>
                                    <div class="column is-6">
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
                                    <div class="column is-6">
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
                                    <div class="column is-6">
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

                                <!-- Step 4 Security -->
                                <div class="columns is-multiline step-pane">
                                    <div class="column is-6">
                                        <label class="label">Password</label>
                                        <input class="input" type="password" name="password" required>
                                        <p class="help">Min 8 chars with upper, lower, number, symbol</p>
                                    </div>
                                    <div class="column is-6">
                                        <label class="label">Confirm Password</label>
                                        <input class="input" type="password" name="password_confirmation" required>
                                    </div>
                                    <div class="column is-12">
                                        <label class="checkbox">
                                            <input type="checkbox" name="terms_accepted" required> I agree to the Terms & Privacy Policy
                                        </label>
                                    </div>
                                </div>

                                <div class="is-flex is-justify-content-space-between mt-4">
                                    <button type="button" class="button is-light" id="prevBtn" disabled>Previous</button>
                                    <div>
                                        <button type="button" class="button" id="nextBtn">Next</button>
                                        <button type="submit" class="button is-primary" id="submitBtn" style="display:none;">Create Account</button>
                                    </div>
                                </div>
                            </form>
                            <p class="has-text-centered is-size-7 mt-3">Already have an account? <a href="{{ route('login') }}"><strong>Sign in</strong></a></p>
                        </div>
                        <p class="has-text-centered"><a class="button is-light is-small" href="/">Back to Home</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Simple stepper (UI only)
        const panes=[...document.querySelectorAll('.step-pane')];
        const items=[...document.querySelectorAll('.steps .step-item')];
        const prevBtn=document.getElementById('prevBtn');
        const nextBtn=document.getElementById('nextBtn');
        const submitBtn=document.getElementById('submitBtn');
        let idx=0;
        function render(){
            panes.forEach((p,i)=>p.classList.toggle('is-active',i===idx));
            items.forEach((it,i)=>{
                it.classList.toggle('is-active',i===idx);
                it.classList.toggle('is-completed',i<idx);
            });
            prevBtn.disabled=idx===0;
            nextBtn.style.display=idx===panes.length-1?'none':'';
            submitBtn.style.display=idx===panes.length-1?'inline-flex':'none';
        }
        prevBtn.onclick=()=>{if(idx>0){idx--;render();}}
        nextBtn.onclick=()=>{if(idx<panes.length-1){idx++;render();}}
        render();
    </script>
</body>
</html>