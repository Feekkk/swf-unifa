<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Application Details - RCMP Unifa</title>
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

        .title, .subtitle{color:var(--text)}
        .card{background:#fff; border:1px solid rgba(0,0,0,.06); box-shadow:0 4px 12px rgba(0,0,0,.06); margin-bottom:1.5rem}
        .section h2.title{color:var(--primary-dark)}
        .button.is-primary{background-color:var(--primary); border-color:var(--primary); color:#fff}
        .button.is-primary:hover{filter:brightness(.95)}
        .button.is-accent{background-color:var(--accent); border-color:var(--accent); color:#000}
        .button.is-accent:hover{filter:brightness(.95)}
        .button.is-success{background-color:#10b981; border-color:#10b981; color:#fff}
        .button.is-success:hover{filter:brightness(.95)}

        .label{color:var(--primary-dark); font-weight:600}
        .input, .textarea{background:#ffffff; color:var(--text); border:1px solid #dfe3ea; border-radius:12px}
        .select select{background:#ffffff; color:var(--text); border:1px solid #dfe3ea; border-radius:12px; padding:.6rem .9rem}
        .input:focus, .textarea:focus, .select select:focus{border-color:var(--primary); box-shadow:0 0 0 0.125em rgba(65,105,225,.25)}
        ::placeholder{color:#94a3b8}

        .button.is-light{background:#ffffff; color:var(--text); border:1px solid #e5e7eb}
        .info-row{display:flex; padding:.75rem 0; border-bottom:1px solid #f3f4f6}
        .info-row:last-child{border-bottom:none}
        .info-label{font-weight:600; color:var(--primary-dark); min-width:180px; flex-shrink:0}
        .info-value{color:var(--text); flex:1}
        .status-chip{display:inline-flex; align-items:center; gap:.4rem; padding:.4rem .8rem; border-radius:999px; font-size:.875rem; font-weight:700}
        .status-pending{background:rgba(255,192,0,.15); color:#7c5a00; border:1px solid rgba(255,192,0,.4)}
        .status-approved{background:rgba(16,185,129,.12); color:#065f46; border:1px solid rgba(16,185,129,.35)}
        .status-rejected{background:rgba(244,63,94,.12); color:#7f1d1d; border:1px solid rgba(244,63,94,.35)}
        .status-under_review{background:rgba(59,130,246,.12); color:#1e40af; border:1px solid rgba(59,130,246,.35)}
        .status-disbursed{background:rgba(16,185,129,.25); color:#065f46; border:1px solid rgba(16,185,129,.5)}

        .footer { background-color: var(--primary-dark); color: #fff; }
        .footer a { color: rgba(255, 255, 255, 0.8); }
        .footer a:hover { color: var(--accent); }
        
        .section-title{color:var(--primary-dark); font-size:1.25rem; font-weight:700; margin-bottom:1rem; padding-bottom:.5rem; border-bottom:2px solid var(--primary)}
        .document-card{background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:1rem; transition:all 0.2s}
        .document-card:hover{box-shadow:0 2px 8px rgba(0,0,0,.1); transform:translateY(-2px)}
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
                    <a class="navbar-item" href="/admin/dashboard">Dashboard</a>
                    <a class="navbar-item is-active" href="/admin/applications">View Applications</a>
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
            <div class="level mb-5">
                <div class="level-left">
                    <div>
                        <h1 class="title is-3">Application Details</h1>
                        <p class="subtitle is-6">Application ID: APP-{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                <div class="level-right">
                    <a href="{{ route('admin.applications.index') }}" class="button is-light">
                        <span class="icon"><i class="fa-solid fa-arrow-left"></i></span>
                        <span>Back to Applications</span>
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="notification is-success is-light mb-4" style="border-left: 4px solid #10b981;">
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

            <!-- Student Information -->
            <div class="card">
                <div class="card-content">
                    <h2 class="section-title">
                        <span class="icon-text">
                            <span class="icon"><i class="fa-solid fa-user-graduate"></i></span>
                            <span>Student Information</span>
                        </span>
                    </h2>
                    <div class="content">
                        <div class="info-row">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">{{ $application->user->display_name ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Student ID</div>
                            <div class="info-value">{{ $application->user->student_id ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $application->user->email ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value">{{ $application->user->phone_number ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Course</div>
                            <div class="info-value">{{ $application->user->course ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Semester</div>
                            <div class="info-value">{{ $application->user->semester ?? '-' }}</div>
                        </div>
                        @if($application->user->street_address || $application->user->city || $application->user->state)
                        <div class="info-row">
                            <div class="info-label">Address</div>
                            <div class="info-value">
                                {{ $application->user->street_address ?? '' }}
                                {{ $application->user->city ? ', ' . $application->user->city : '' }}
                                {{ $application->user->state ? ', ' . $application->user->state : '' }}
                                {{ $application->user->postal_code ? ' ' . $application->user->postal_code : '' }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Application Details -->
            <div class="card">
                <div class="card-content">
                    <h2 class="section-title">
                        <span class="icon-text">
                            <span class="icon"><i class="fa-solid fa-file-lines"></i></span>
                            <span>Application Details</span>
                        </span>
                    </h2>
                    <div class="content">
                        <div class="info-row">
                            <div class="info-label">Application ID</div>
                            <div class="info-value">#APP-{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                @php($status = strtolower($application->status ?? 'pending'))
                                <span class="status-chip {{ $status==='approved'?'status-approved':($status==='rejected'?'status-rejected':($status==='under_review'?'status-under_review':($status==='disbursed'?'status-disbursed':'status-pending'))) }}">
                                    @if($status==='approved')<i class="fa-solid fa-circle-check"></i>@elseif($status==='rejected')<i class="fa-solid fa-circle-xmark"></i>@elseif($status==='under_review')<i class="fa-solid fa-clock"></i>@elseif($status==='disbursed')<i class="fa-solid fa-check-double"></i>@else<i class="fa-regular fa-clock"></i>@endif
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Category</div>
                            <div class="info-value"><span class="has-text-capitalized">{{ $application->category }}</span></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Subcategory</div>
                            <div class="info-value"><span class="has-text-capitalized">{{ $application->subcategory }}</span></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Amount Applied</div>
                            <div class="info-value">RM {{ number_format($application->total_amount, 2) }}</div>
                        </div>
                        @if($application->amount_approved)
                        <div class="info-row">
                            <div class="info-label">Amount Approved</div>
                            <div class="info-value"><strong style="color:#10b981">RM {{ number_format($application->amount_approved, 2) }}</strong></div>
                        </div>
                        @endif
                        <div class="info-row">
                            <div class="info-label">Submitted On</div>
                            <div class="info-value">{{ $application->created_at->format('d M Y, g:i A') }}</div>
                        </div>
                        @if($application->reviewed_at)
                        <div class="info-row">
                            <div class="info-label">Reviewed On</div>
                            <div class="info-value">{{ $application->reviewed_at->format('d M Y, g:i A') }}</div>
                        </div>
                        @endif
                        @if($application->reviewer)
                        <div class="info-row">
                            <div class="info-label">Reviewed By</div>
                            <div class="info-value">{{ $application->reviewer->display_name ?? $application->reviewer->username }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Application Data (Dynamic Fields) -->
            @if($application->application_data && count($application->application_data) > 0)
            <div class="card">
                <div class="card-content">
                    <h2 class="section-title">
                        <span class="icon-text">
                            <span class="icon"><i class="fa-solid fa-info-circle"></i></span>
                            <span>Application Information</span>
                        </span>
                    </h2>
                    <div class="content">
                        @foreach($application->application_data as $key => $value)
                            @if($value && !empty($value))
                            <div class="info-row">
                                <div class="info-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</div>
                                <div class="info-value">{{ is_array($value) ? implode(', ', $value) : $value }}</div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Bank Details -->
            <div class="card">
                <div class="card-content">
                    <h2 class="section-title">
                        <span class="icon-text">
                            <span class="icon"><i class="fa-solid fa-university"></i></span>
                            <span>Bank Details</span>
                        </span>
                    </h2>
                    <div class="content">
                        <div class="info-row">
                            <div class="info-label">Bank Name</div>
                            <div class="info-value">{{ $application->bank_name ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Account Number</div>
                            <div class="info-value">{{ $application->bank_account_number ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents -->
            @if($application->documents && $application->documents->count() > 0)
            <div class="card">
                <div class="card-content">
                    <h2 class="section-title">
                        <span class="icon-text">
                            <span class="icon"><i class="fa-solid fa-file-pdf"></i></span>
                            <span>Application Documents ({{ $application->documents->count() }})</span>
                        </span>
                    </h2>
                    <div class="columns is-multiline">
                        @foreach($application->documents as $document)
                        <div class="column is-6">
                            <div class="document-card">
                                <div class="level is-mobile">
                                    <div class="level-left">
                                        <div>
                                            <span class="icon-text">
                                                <span class="icon has-text-primary"><i class="fa-solid fa-file-pdf"></i></span>
                                                <span class="has-text-weight-semibold">{{ $document->filename }}</span>
                                            </span>
                                            <p class="help mt-1">{{ number_format($document->file_size / 1024, 2) }} KB</p>
                                        </div>
                                    </div>
                                    <div class="level-right">
                                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="button is-small is-primary">
                                            <span class="icon"><i class="fa-solid fa-eye"></i></span>
                                            <span>View Document</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Admin Notes -->
            @if($application->admin_notes)
            <div class="card">
                <div class="card-content">
                    <h2 class="section-title">
                        <span class="icon-text">
                            <span class="icon"><i class="fa-solid fa-sticky-note"></i></span>
                            <span>Admin Notes</span>
                        </span>
                    </h2>
                    <div class="content">
                        <div class="notification is-light" style="background-color:#f3f4f6; border-left: 4px solid var(--primary);">
                            <p style="white-space: pre-wrap;">{{ $application->admin_notes }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Verify Form (Only show if status is pending) -->
            @if(strtolower($application->status) === 'pending')
            <div class="card">
                <div class="card-content">
                    <h2 class="section-title">
                        <span class="icon-text">
                            <span class="icon"><i class="fa-solid fa-check-circle"></i></span>
                            <span>Verify Application</span>
                        </span>
                    </h2>
                    <form method="POST" action="{{ route('admin.applications.verify', $application->id) }}">
                        @csrf
                        <div class="field">
                            <label class="label">Approved Amount</label>
                            <div class="control">
                                <input class="input" type="number" name="amount_approved" step="0.01" min="0" 
                                       value="{{ $application->total_amount }}" placeholder="Enter approved amount">
                            </div>
                            <p class="help">Default: RM {{ number_format($application->total_amount, 2) }}</p>
                        </div>
                        <div class="field">
                            <label class="label">Admin Notes (Optional)</label>
                            <div class="control">
                                <textarea class="textarea" name="admin_notes" rows="4" placeholder="Add any notes or remarks about this application">{{ old('admin_notes', $application->admin_notes) }}</textarea>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <button type="submit" class="button is-success is-medium">
                                    <span class="icon"><i class="fa-solid fa-check"></i></span>
                                    <span>Verify & Approve Application</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif

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
                    <h4 class="title is-6 has-text-white mb-4">Admin Portal</h4>
                    <ul>
                        <li><a href="/admin/dashboard">Dashboard</a></li>
                        <li><a href="/admin/applications">View Applications</a></li>
                        <li><a href="/admin/profile">Profile</a></li>
                    </ul>
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

            const navbarItems = document.querySelectorAll('.navbar-menu .navbar-item');
            const brandItem = document.querySelector('.navbar-brand .navbar-item');
            const navbarEnd = document.querySelector('.navbar-end');
            navbarItems.forEach(item => {
                if (item.querySelector('.button') || item.querySelector('i.fa, i.fas, i.far')) { item.classList.add('has-icon'); }
            });
            if (brandItem) {
                brandItem.addEventListener('mouseenter', () => { navbarItems.forEach(item => item.classList.add('no-underline')); });
                brandItem.addEventListener('mouseleave', () => { navbarItems.forEach(item => item.classList.remove('no-underline')); });
            }
            if (burger) {
                burger.addEventListener('mouseenter', () => { navbarItems.forEach(item => item.classList.add('no-underline')); });
                burger.addEventListener('mouseleave', () => { navbarItems.forEach(item => item.classList.remove('no-underline')); });
            }
            if (navbarEnd) {
                navbarEnd.addEventListener('mouseenter', () => { document.querySelectorAll('.navbar-start .navbar-item').forEach(i=>i.classList.add('no-underline')); });
                navbarEnd.addEventListener('mouseleave', () => { document.querySelectorAll('.navbar-start .navbar-item').forEach(i=>i.classList.remove('no-underline')); });
            }
        });
    </script>
</body>
</html>

