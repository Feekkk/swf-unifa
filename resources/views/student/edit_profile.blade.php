<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profile - RCMP Unifa</title>
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
        .grid-2{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem}
        @media(max-width:768px){.grid-2{grid-template-columns:1fr}}

        /* Form controls: force white background and black text */
        .input, .textarea{background:#ffffff!important; color:#000!important; border:1px solid #cbd5e1;}
        .select select{background:#ffffff!important; color:#000!important; border:1px solid #cbd5e1;}
        .select:not(.is-multiple):not(.is-loading)::after{border-color:#000}
        ::placeholder{color:#111827}
        option{color:#000}

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
                <div class="navbar-start"><a class="navbar-item" href="/">Home</a></div>
                <div class="navbar-end"><a class="navbar-item" href="{{ route('dashboard') }}">Dashboard</a></div>
            </div>
        </div>
    </nav>

    <section class="section" style="padding-top:6rem">
        <div class="container">
            <h1 class="title is-3">Edit Profile</h1>
            <p class="subtitle is-6">Update your personal, contact, and academic information.</p>

            @if (session('status'))
                <div class="notification is-success is-light">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="notification is-danger is-light">
                    <ul style="margin-left:1rem">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            @php($u = auth()->user())
            <div class="card">
                <div class="card-content">
                    <form id="edit-profile-form" method="POST" action="{{ route('student.profile.update') }}">
                        @csrf

                        <div class="field">
                            <label class="label">Full Name</label>
                            <input class="input" type="text" name="full_name" value="{{ old('full_name', $u->full_name) }}" required>
                        </div>

                        <div class="grid-2">
                            <div class="field">
                                <label class="label">Username</label>
                                <input class="input" type="text" name="username" value="{{ old('username', $u->username) }}" required>
                            </div>
                            <div class="field">
                                <label class="label">Email</label>
                                <input class="input" type="email" name="email" value="{{ old('email', $u->email) }}" required>
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="field">
                                <label class="label">Bank Name</label>
                                <div class="select is-fullwidth">
                                <select name="bank_name" required>
                                    @php($banks = ['maybank'=>'Maybank','cimb'=>'CIMB Bank','public_bank'=>'Public Bank','rhb'=>'RHB Bank','hong_leong'=>'Hong Leong Bank','ambank'=>'AmBank','bsn'=>'Bank Simpanan Nasional','other'=>'Other'])
                                    @foreach($banks as $val=>$label)
                                        <option value="{{ $val }}" @selected(old('bank_name', $u->bank_name)===$val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="field">
                                <label class="label">Bank Account Number</label>
                                <input class="input" type="text" name="bank_account_number" value="{{ old('bank_account_number', $u->bank_account_number) }}" required>
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="field">
                                <label class="label">Phone Number</label>
                                <input class="input" type="tel" name="phone_number" value="{{ old('phone_number', $u->phone_number) }}" required>
                            </div>
                            <div class="field">
                                <label class="label">Street Address</label>
                                <input class="input" type="text" name="street_address" value="{{ old('street_address', $u->street_address) }}" required>
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="field">
                                <label class="label">City</label>
                                <input class="input" type="text" name="city" value="{{ old('city', $u->city) }}" required>
                            </div>
                            <div class="field">
                                <label class="label">State</label>
                                <div class="select is-fullwidth">
                                <select name="state" required>
                                    @php($states = ['johor','kedah','kelantan','melaka','negeri_sembilan','perak','perlis','pulau_pinang','sabah','sarawak','selangor','terengganu','wp_kuala_lumpur','wp_labuan','wp_putrajaya'])
                                    @foreach($states as $s)
                                        <option value="{{ $s }}" @selected(old('state', $u->state)===$s)>{{ ucwords(str_replace('_',' ',$s)) }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Postal Code</label>
                            <input class="input" type="text" name="postal_code" value="{{ old('postal_code', $u->postal_code) }}" required>
                        </div>

                        <div class="grid-2">
                            <div class="field">
                                <label class="label">Student ID</label>
                                <input class="input" type="text" name="student_id" value="{{ old('student_id', $u->student_id) }}" required>
                            </div>
                            <div class="field">
                                <label class="label">Course/Program</label>
                                <div class="select is-fullwidth">
                                <select name="course" id="course-select" required>
                                    @php($courses = [
                                        'ASASI DALAM SAINS PERUBATAN','DIPLOMA FARMASI','DIPLOMA KEJURURAWATAN','DIPLOMA DALAM FISIOTERAPI','DIPLOMA PENGIMEJAN PERUBATAN',
                                        'SARJANA MUDA FARMASI (KEPUJIAN)','SARJANA MUDA FISIOTERAPI (KEPUJIAN)','SARJANA MUDA SAINS PSIKOLOGI (KEPUJIAN)',
                                        'SARJANA MUDA SAINS KEJURURAWATAN (KEPUJIAN)','SARJANA MUDA SAINS (KEPUJIAN) DALAM TEKNOLOGI FARMASEUTIKAL',
                                        'SARJANA MUDA PERUBATAN DAN SARJANA MUDA PEMBEDAHAN (MBBS)','SARJANA SAINS (FARMASI)','SARJANA SAINS PERUBATAN',
                                        'SARJANA KESIHATAN AWAM','SARJANA SAINS DALAM KESIHATAN AWAM','DOKTOR FALSAFAH (FARMASI)','DOKTOR FALSAFAH (SAINS PERUBATAN)'
                                    ])
                                    @foreach($courses as $c)
                                        <option value="{{ $c }}" @selected(old('course', $u->course)===$c)>{{ $c }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label" id="semester-label">Current Semester</label>
                            <div class="select is-fullwidth">
                            <select id="semester-select" name="semester" required></select>
                            </div>
                        </div>

                        <div class="field is-grouped">
                            <div class="control">
                                <button type="submit" class="button is-primary" id="save-btn">
                                    <span class="icon"><i class="fa-solid fa-floppy-disk"></i></span>
                                    <span>Save Changes</span>
                                </button>
                            </div>
                            <div class="control">
                                <a href="{{ route('dashboard') }}" class="button is-light">Cancel</a>
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

            // Semester/Year behavior for MBBS
            const courseSelect = document.getElementById('course-select');
            const semesterSelect = document.getElementById('semester-select');
            const semesterLabel = document.getElementById('semester-label');
            const currentSemester = @json(old('semester', $u->semester));

            function populateOptions(){
                const selectedCourse = (courseSelect?.value || '').toUpperCase();
                const isMbbs = selectedCourse.includes('MBBS');
                semesterSelect.innerHTML = '';
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = isMbbs ? 'Select year' : 'Select semester';
                semesterSelect.appendChild(placeholder);
                const max = isMbbs ? 5 : 10;
                for(let i=1;i<=max;i++){
                    const opt = document.createElement('option');
                    opt.value = String(i);
                    opt.textContent = isMbbs ? `Year ${i}` : `Semester ${i}`;
                    if(String(currentSemester) === String(i)) opt.selected = true;
                    semesterSelect.appendChild(opt);
                }
                semesterLabel.textContent = isMbbs ? 'Current Year' : 'Current Semester';
            }
            if(courseSelect && semesterSelect){
                populateOptions();
                courseSelect.addEventListener('change', populateOptions);
            }

            // Confirmation modal before submit
            const form = document.getElementById('edit-profile-form');
            const saveBtn = document.getElementById('save-btn');
            const modal = document.getElementById('confirm-modal');
            const modalBg = document.getElementById('confirm-modal-bg');
            const cancelBtn = document.getElementById('confirm-cancel');
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
            [cancelBtn, modalBg].forEach(el=>{ if(el){ el.addEventListener('click', closeModal); } });
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
                    <img src="/assets/images/logos/unikl-rcmp.png" alt="UniKL RCMP" style="height:50px; width:auto"><br>
                    <span>Please review your profile</span>
                </p>
                <button class="delete" aria-label="close" id="confirm-cancel"></button>
            </header>
            <section class="modal-card-body has-text-black">
                <p class="has-text-black">
                    Before submitting, carefully review all details in your profile. Inaccurate or incomplete information may cause delays or affect your application eligibility.
                </p>
                <p class="mt-3 has-text-black"><strong class="has-text-danger">Note:</strong> It is the student's responsibility to ensure the information provided is accurate and up to date.</p>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-primary" id="confirm-submit">Submit</button>
            </footer>
        </div>
    </div>
</body>
</html>

