<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>View Applications - RCMP Unifa</title>
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
        .card{background:#fff; border:1px solid rgba(0,0,0,.06); box-shadow:0 4px 12px rgba(0,0,0,.06)}
        .section h2.title{color:var(--primary-dark)}
        .button.is-primary{background-color:var(--primary); border-color:var(--primary); color:#fff}
        .button.is-primary:hover{filter:brightness(.95)}
        .button.is-accent{background-color:var(--accent); border-color:var(--accent); color:#000}
        .button.is-accent:hover{filter:brightness(.95)}

        .table thead th{background:#ffffff !important; color:var(--primary-dark); font-weight:700; border-bottom:2px solid var(--primary)}
        .table tbody tr{background:#ffffff !important}
        .table td, .table th{background:#ffffff !important; color:var(--text) !important}
        /* Controls use system theme */
        .label{color:var(--primary-dark); font-weight:600}
        .input, .textarea{background:#ffffff; color:var(--text); border:1px solid #dfe3ea; border-radius:12px}
        .select select{background:#ffffff; color:var(--text); border:1px solid #dfe3ea; border-radius:12px; padding:.6rem .9rem}
        .input:focus, .textarea:focus, .select select:focus{border-color:var(--primary); box-shadow:0 0 0 0.125em rgba(65,105,225,.25)}
        ::placeholder{color:#94a3b8}

        /* Buttons themed */
        .button.is-primary{background-color:var(--primary); border-color:var(--primary); color:#fff}
        .button.is-primary.is-outlined{background:transparent; color:var(--primary); border-color:var(--primary)}
        .button.is-primary.is-light{background-color:var(--primary); color:#fff; border-color:var(--primary)} /* solid blue for lower icon button */
        .button.is-primary.is-light:hover{filter:brightness(.95)}
        .button.is-light{background:#ffffff; color:var(--text); border:1px solid #e5e7eb}

        /* Table hover */
        .table.is-hoverable tbody tr:hover{background:rgba(65,105,225,.06)}

        /* Force student icon to black */
        .table .fa-user-graduate{color:#000 !important}

        /* Pagination */
        .pagination .pagination-link.is-current{background-color:var(--primary); border-color:var(--primary); color:#fff}
        .pagination .pagination-link:focus, .pagination .pagination-link:hover{border-color:var(--primary)}
        .status-chip{display:inline-flex; align-items:center; gap:.4rem; padding:.2rem .55rem; border-radius:999px; font-size:.8rem; font-weight:700}
        .status-pending{background:rgba(255,192,0,.15); color:#7c5a00; border:1px solid rgba(255,192,0,.4)}
        .status-approved{background:rgba(16,185,129,.12); color:#065f46; border:1px solid rgba(16,185,129,.35)}
        .status-rejected{background:rgba(244,63,94,.12); color:#7f1d1d; border:1px solid rgba(244,63,94,.35)}
        .filters{display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem}
        @media(max-width:1024px){.filters{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:560px){.filters{grid-template-columns:1fr}}

        .footer { background-color: var(--primary-dark); color: #fff; }
        .footer a { color: rgba(255, 255, 255, 0.8); }
        .footer a:hover { color: var(--accent); }
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
            <h1 class="title is-3">All Applications</h1>
            <p class="subtitle is-6">Overview of applications submitted by users</p>

            <form method="GET" action="{{ route('admin.applications.index') }}" class="card mb-4">
                <div class="card-content">
                    <div class="filters">
                        <div class="field">
                            <label class="label">Search</label>
                            <div class="control has-icons-left">
                                <input class="input" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search by name, student ID, or application ID">
                                <span class="icon is-small is-left"><i class="fa-solid fa-magnifying-glass"></i></span>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Status</label>
                            <div class="select is-fullwidth">
                                <select name="status">
                                    <option value="all" {{ ($filters['status'] ?? 'all')==='all'?'selected':'' }}>All</option>
                                    <option value="pending" {{ ($filters['status'] ?? '')==='pending'?'selected':'' }}>Pending</option>
                                    <option value="approved" {{ ($filters['status'] ?? '')==='approved'?'selected':'' }}>Approved</option>
                                    <option value="rejected" {{ ($filters['status'] ?? '')==='rejected'?'selected':'' }}>Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Category</label>
                            <div class="select is-fullwidth">
                                <select name="category">
                                    <option value="all" {{ ($filters['category'] ?? 'all')==='all'?'selected':'' }}>All</option>
                                    <option value="bereavement" {{ ($filters['category'] ?? '')==='bereavement'?'selected':'' }}>Bereavement</option>
                                    <option value="medical" {{ ($filters['category'] ?? '')==='medical'?'selected':'' }}>Medical</option>
                                    <option value="emergency" {{ ($filters['category'] ?? '')==='emergency'?'selected':'' }}>Emergency</option>
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Sort by</label>
                            <div class="select is-fullwidth">
                                <select name="sort">
                                    <option value="newest" {{ ($filters['sort'] ?? 'newest')==='newest'?'selected':'' }}>Newest</option>
                                    <option value="oldest" {{ ($filters['sort'] ?? '')==='oldest'?'selected':'' }}>Oldest</option>
                                    <option value="amount_desc" {{ ($filters['sort'] ?? '')==='amount_desc'?'selected':'' }}>Amount (High → Low)</option>
                                    <option value="amount_asc" {{ ($filters['sort'] ?? '')==='amount_asc'?'selected':'' }}>Amount (Low → High)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3" style="display:flex; gap:.5rem; justify-content:flex-end">
                        <button type="submit" class="button is-primary"><span class="icon"><i class="fa-solid fa-filter"></i></span><span>Apply</span></button>
                        <a href="{{ route('admin.applications.index') }}" class="button is-light">Reset</a>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-content">
                    <div class="table-container">
                        <table class="table is-fullwidth is-hoverable">
                            <thead>
                                <tr>
                                    <th>Application ID</th>
                                    <th>Student</th>
                                    <th>Category</th>
                                    <th>Submitted</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th style="width: 120px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications as $app)
                                <tr>
                                    <td>{{ 'APP-' . str_pad($app->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        <div class="is-flex is-align-items-center" style="gap:.5rem">
                                            <span class="icon"><i class="fa-solid fa-user-graduate"></i></span>
                                            <div>
                                                <div class="has-text-weight-semibold">{{ $app->user->display_name ?? '-' }}</div>
                                                <div class="is-size-7 has-text-grey">{{ $app->user->student_id ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="has-text-capitalized">{{ $app->category }}</td>
                                    <td>{{ optional($app->created_at)->toDateString() }}</td>
                                    <td>RM {{ number_format($app->total_amount, 2) }}</td>
                                    <td>
                                        @php($status = strtolower($app->status ?? 'pending'))
                                        <span class="status-chip {{ $status==='approved'?'status-approved':($status==='rejected'?'status-rejected':'status-pending') }}">
                                            @if($status==='approved')<i class="fa-solid fa-circle-check"></i>@elseif($status==='rejected')<i class="fa-solid fa-circle-xmark"></i>@else<i class="fa-regular fa-clock"></i>@endif
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="buttons are-small">
                                            <a href="{{ route('admin.applications.show', $app->id) }}" class="button is-light"><span class="icon"><i class="fa-regular fa-eye"></i></span><span>View</span></a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="has-text-centered has-text-grey">No applications found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4" style="display:flex; justify-content:flex-end">
                        {{ $applications->onEachSide(1)->links() }}
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


