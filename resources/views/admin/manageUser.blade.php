<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manage Users - Admin - RCMP Unifa</title>
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
        .card{background:#fff; border:1px solid rgba(0,0,0,.06); box-shadow:0 4px 12px rgba(0,0,0,.06); color:#000}
        .card .title{color:var(--primary-dark)}
        .subtitle, .label, .help, .notification{color:#000}
        .button.is-primary{background-color:var(--primary); border-color:var(--primary); color:#fff}
        .button.is-primary:hover{filter:brightness(.95)}
        .button.is-accent{background-color:var(--accent); border-color:var(--accent); color:#000}
        .button.is-accent:hover{filter:brightness(.95)}
        .footer { background-color: var(--primary-dark); color: #fff; }
        .footer a { color: rgba(255, 255, 255, 0.8); }
        .footer a:hover { color: var(--accent); }
        
        /* Table styling */
        .table { background:#fff }
        .table thead th { color:var(--primary-dark); font-weight:700; border-bottom:2px solid var(--primary) }
        .table tbody td { color:#000; border-bottom:1px solid #e5e7eb }
        .table tbody tr:hover { background-color:#fff }
        
        /* Search and filter */
        .search-filter-container { background:#fff; padding:1.5rem; border-radius:8px; box-shadow:0 2px 8px rgba(0, 0, 0, 0.1); margin-bottom:1.5rem }
        .input, .select select { background-color: #fff !important; color: #000 !important; }
        .spacing-tweaks { gap:1.25rem }
        
        /* Modal styling */
        .modal-card{background:#ffffff!important; color:#000!important}
        .modal-card-head{background:#ffffff!important; color:#000!important; border-bottom:1px solid #e5e7eb}
        .modal-card-body{background:#ffffff!important; color:#000!important}
        .modal-card-foot{background:#ffffff!important; color:#000!important; border-top:1px solid #e5e7eb}
        .modal-card-title{color:#000!important}
    </style>
    @vite(['resources/js/app.js'])
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
                    <a class="navbar-item" href="/admin/applications">View Applications</a>
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
                        <h1 class="title is-3" style="color: #000;">Manage Users</h1>
                        <p class="subtitle is-6">View and manage all registered users in the system</p>
                    </div>
                </div>
                <div class="level-right">
                    <a href="/admin/dashboard" class="button is-light">
                        <span class="icon"><i class="fas fa-arrow-left"></i></span>
                        <span>Back to Dashboard</span>
                    </a>
                </div>
            </div>

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

            @if (isset($noResults) && $noResults)
                <div class="notification is-warning is-light mb-4" style="border-left: 4px solid #ffb347;">
                    <button class="delete" onclick="this.parentElement.remove()"></button>
                    <span class="icon mr-2"><i class="fa-solid fa-search"></i></span>
                    <strong>No users found</strong> for "<strong>{{ $search }}</strong>". Please try a different search term.
                </div>
            @endif

            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="search-filter-container">
                    <div class="columns">
                        <div class="column is-6">
                            <div class="field has-addons">
                                <div class="control is-expanded">
                                    <input class="input" type="text" name="search" placeholder="Search by name, email, student ID..." value="{{ $search ?? '' }}" id="search-input">
                                </div>
                                <div class="control">
                                    <button type="submit" class="button is-primary">
                                        <span class="icon"><i class="fas fa-search"></i></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3">
                            <div class="field">
                                <div class="select is-fullwidth">
                                    <select name="role_filter" id="role-filter">
                                        <option value="">All Roles</option>
                                        <option value="student" {{ ($roleFilter ?? '') === 'student' ? 'selected' : '' }}>Student</option>
                                        <option value="admin" {{ ($roleFilter ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="committee" {{ ($roleFilter ?? '') === 'committee' ? 'selected' : '' }}>Committee</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="column is-3 has-text-right">
                            <div class="buttons">
                                @if($search ?? '')
                                    <a href="{{ route('admin.users.index', ['role_filter' => $roleFilter ?? '']) }}" class="button is-light">
                                        <span class="icon"><i class="fas fa-times"></i></span>
                                        <span>Clear Search</span>
                                    </a>
                                @endif
                                <a href="{{ route('admin.users.index') }}" class="button is-primary">
                                    <span class="icon"><i class="fas fa-sync"></i></span>
                                    <span>Refresh</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-content">
                    <div class="content">
                        <div class="table-container">
                            <table class="table is-fullwidth is-hoverable">
                                <thead>
                                    <tr>
                                        <th style="color: #000;">No</th>
                                        <th style="color: #000;">Name</th>
                                        <th style="color: #000;">Email</th>
                                        <th style="color: #000;">Role</th>
                                        <th style="color: #000;">Last Updated</th>
                                        <th style="color: #000;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users ?? [] as $index => $user)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $user['name'] }}</td>
                                            <td>{{ $user['email'] }}</td>
                                            <td>
                                                <span class="tag {{ $user['role'] === 'admin' ? 'is-warning' : ($user['role'] === 'committee' ? 'is-success' : 'is-info') }}">
                                                    {{ ucfirst($user['role']) }}
                                                </span>
                                            </td>
                                            <td>{{ $user['updated_at']->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.edit', ['role' => $user['role'], 'id' => $user['id']]) }}" class="button is-small is-info is-light">
                                                    <span class="icon"><i class="fas fa-key"></i></span>
                                                    <span>Edit Password</span>
                                                </a>
                                                <button class="button is-small is-danger is-light delete-user-btn" data-role="{{ $user['role'] }}" data-id="{{ $user['id'] }}" data-name="{{ $user['name'] }}">
                                                    <span class="icon"><i class="fas fa-trash"></i></span>
                                                    <span>Delete</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="has-text-centered" style="padding: 3rem 1rem;">
                                                <div class="has-text-grey">
                                                    <i class="fas fa-users fa-3x mb-4"></i>
                                                    @if(isset($search) && $search)
                                                        <p class="is-size-5">No users found matching your search</p>
                                                        <p class="is-size-6">Try searching with a different term or <a href="{{ route('admin.users.index') }}">view all users</a></p>
                                                    @else
                                                        <p class="is-size-5">No users found</p>
                                                        <p class="is-size-6">Users will appear here once registered</p>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="delete-modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title has-text-black" style="display:flex; align-items:center; gap:.5rem">
                    <img src="/assets/images/logos/unikl-rcmp.png" alt="UniKL RCMP" style="height:50px; width:auto">
                    <span>Confirm Deletion</span>
                </p>
                <button class="delete" aria-label="close"></button>
            </header>
            <section class="modal-card-body has-text-black">
                <p class="has-text-black">Are you sure you want to delete user <strong id="delete-user-name"></strong>?</p>
                <p class="mt-3 has-text-black"><strong class="has-text-danger" style="color: #dc3545;">Warning:</strong> This action cannot be undone!</p>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-danger" id="confirm-delete-btn" style="background-color: #dc3545; color: #fff;">Delete User</button>
                <button class="button is-light" id="cancel-delete-btn">Cancel</button>
            </footer>
        </div>
    </div>

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

            // Delete user functionality with modal
            const deleteModal = document.getElementById('delete-modal');
            const deleteUserName = document.getElementById('delete-user-name');
            const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
            const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
            const closeModalBtn = deleteModal.querySelector('.delete');
            
            let currentDeleteRole = '';
            let currentDeleteId = '';
            let currentDeleteName = '';

            document.querySelectorAll('.delete-user-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    currentDeleteName = this.getAttribute('data-name');
                    currentDeleteRole = this.getAttribute('data-role');
                    currentDeleteId = this.getAttribute('data-id');
                    
                    deleteUserName.textContent = currentDeleteName;
                    deleteModal.classList.add('is-active');
                });
            });

            // Close modal handlers
            function closeModal() {
                deleteModal.classList.remove('is-active');
            }

            closeModalBtn.addEventListener('click', closeModal);
            cancelDeleteBtn.addEventListener('click', closeModal);
            deleteModal.querySelector('.modal-background').addEventListener('click', closeModal);

            // Confirm delete
            confirmDeleteBtn.addEventListener('click', function() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${currentDeleteRole}/${currentDeleteId}`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            });

            // Auto-submit form on role filter change
            const roleFilter = document.getElementById('role-filter');
            if (roleFilter) {
                roleFilter.addEventListener('change', function() {
                    this.form.submit();
                });
            }

            // Submit search form on Enter key press
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.form.submit();
                    }
                });
            }

            // Clear search functionality
            const clearSearch = () => {
                const searchInput = document.getElementById('search-input');
                if (searchInput && searchInput.value) {
                    searchInput.value = '';
                    searchInput.form.submit();
                }
            };
        });
    </script>
</body>
</html>
