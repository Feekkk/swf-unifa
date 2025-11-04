<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Applications - RCMP Unifa</title>
    <link rel="icon" type="image/png" href="/assets/images/logos/rcmp.png">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --primary-dark:#191970; --primary:#4169E1; --accent:#FFC000; --background:#FAF9F6 }
        body{background:var(--background); color:#000}
        .navbar{background-color:var(--primary-dark)!important; min-height:4.5rem}
        .navbar .navbar-item{color:#fff!important}
        .card{background:#fff; border:1px solid #e5e7eb; box-shadow:0 4px 12px rgba(0,0,0,.06); color:#000}
        .card .title{color:#191970}
        .button.is-primary{background-color:#4169E1; border-color:#4169E1; color:#fff}
        .button.is-primary:hover{filter:brightness(.95)}
        .footer{background-color:#191970; color:#fff}
        .footer a { color: rgba(255, 255, 255, 0.8); }
        .footer a:hover { color: var(--accent); }
        .label, .subtitle, .help { color:#000 !important }
        
        /* Status badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-verify {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-under_review {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-approved {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .status-disbursed {
            background-color: #059669;
            color: #fff;
        }
        
        /* Application card styling */
        .application-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .application-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,.1);
        }
        
        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        .empty-state-icon {
            font-size: 4rem;
            color: #9ca3af;
            margin-bottom: 1rem;
        }
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
                    <a class="navbar-item" href="{{ route('dashboard') }}">Dashboard</a>
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
                        <h1 class="title is-3 has-text-black">My Applications</h1>
                        <p class="subtitle is-6">View all your Student Welfare Fund applications and their status.</p>
                    </div>
                </div>
                <div class="level-right">
                    <a href="{{ route('student.application') }}" class="button is-primary">
                        <span class="icon"><i class="fa-solid fa-plus"></i></span>
                        <span>New Application</span>
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

            @php
                $applications = auth()->user()->applications()
                    ->with(['documents', 'reviewer', 'verifier'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            @endphp

            @if($applications->isEmpty())
                <div class="card">
                    <div class="card-content">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fa-solid fa-folder-open"></i>
                            </div>
                            <h3 class="title is-4" style="color:#191970">No Applications Yet</h3>
                            <p class="subtitle is-6">You haven't submitted any applications. Start by creating a new application.</p>
                            <a href="{{ route('student.application') }}" class="button is-primary mt-4">
                                <span class="icon"><i class="fa-solid fa-plus"></i></span>
                                <span>Create Your First Application</span>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="columns is-multiline">
                    @foreach($applications as $application)
                        <div class="column is-12">
                            <div class="card application-card">
                                <div class="card-content">
                                    <div class="level is-mobile">
                                        <div class="level-left">
                                            <div>
                                                <h3 class="title is-5" style="color:#191970; margin-bottom:0.5rem">
                                                    {{ ucfirst($application->category) }} - {{ ucfirst(str_replace('_', ' ', $application->subcategory)) }}
                                                </h3>
                                                <p class="subtitle is-6" style="color:#6b7280">
                                                    <span class="icon-text">
                                                        <span class="icon"><i class="fa-solid fa-calendar"></i></span>
                                                        <span>Submitted: {{ $application->created_at->format('d M Y, g:i A') }}</span>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="level-right">
                                            @php
                                                $statusClass = 'status-' . str_replace('_', '-', $application->status);
                                                $statusLabels = [
                                                    'pending' => 'Pending',
                                                    'verify' => 'Verified',
                                                    'under_review' => 'Under Review',
                                                    'approved' => 'Approved',
                                                    'rejected' => 'Rejected',
                                                    'disbursed' => 'Disbursed'
                                                ];
                                                $statusIcons = [
                                                    'pending' => 'fa-clock',
                                                    'verify' => 'fa-check-circle',
                                                    'under_review' => 'fa-hourglass-half',
                                                    'approved' => 'fa-check-circle',
                                                    'rejected' => 'fa-times-circle',
                                                    'disbursed' => 'fa-money-bill-wave'
                                                ];
                                            @endphp
                                            <span class="status-badge {{ $statusClass }}">
                                                <span class="icon"><i class="fa-solid {{ $statusIcons[$application->status] ?? 'fa-clock' }}"></i></span>
                                                <span>{{ $statusLabels[$application->status] ?? ucfirst($application->status) }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <hr style="margin: 1rem 0;">

                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <p class="heading">Application ID</p>
                                            <p class="title is-6">#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                        <div class="column is-4">
                                            <p class="heading">Amount Applied</p>
                                            <p class="title is-6">
                                                @if($application->amount_applied)
                                                    RM {{ number_format($application->amount_applied, 2) }}
                                                @else
                                                    @php
                                                        $fixedAmounts = [
                                                            'student' => 500.00,
                                                            'parent' => 200.00,
                                                            'sibling' => 100.00,
                                                        ];
                                                        $amount = $fixedAmounts[$application->subcategory] ?? 0.00;
                                                    @endphp
                                                    RM {{ number_format($amount, 2) }}
                                                @endif
                                            </p>
                                        </div>
                                        <div class="column is-4">
                                            <p class="heading">Amount Approved</p>
                                            <p class="title is-6">
                                                @if($application->amount_approved)
                                                    <span style="color:#059669; font-weight:600">
                                                        RM {{ number_format($application->amount_approved, 2) }}
                                                    </span>
                                                @else
                                                    <span style="color:#9ca3af">Not yet determined</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    @if($application->verifier)
                                        <div class="columns is-multiline mt-2">
                                            <div class="column is-6">
                                                <p class="heading">Verified By</p>
                                                <p class="subtitle is-6">{{ $application->verifier->name ?? 'Admin' }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if($application->reviewed_at)
                                        <div class="columns is-multiline mt-2">
                                            <div class="column is-6">
                                                <p class="heading">Reviewed On</p>
                                                <p class="subtitle is-6">{{ $application->reviewed_at->format('d M Y, g:i A') }}</p>
                                            </div>
                                            @if($application->reviewer)
                                                <div class="column is-6">
                                                    <p class="heading">Reviewed By</p>
                                                    <p class="subtitle is-6">{{ $application->reviewer->display_name ?? $application->reviewer->username }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @if($application->admin_notes)
                                        <div class="notification is-light mt-3" style="background-color:#f3f4f6; border-left: 4px solid #4169E1;">
                                            <p class="heading">Admin Notes</p>
                                            <p>{{ $application->admin_notes }}</p>
                                        </div>
                                    @endif

                                    <div class="buttons mt-4">
                                        @if($application->documents->count() > 0)
                                            <button class="button is-light" onclick="toggleDocuments({{ $application->id }})">
                                                <span class="icon"><i class="fa-solid fa-file"></i></span>
                                                <span>View Documents ({{ $application->documents->count() }})</span>
                                            </button>
                                        @endif
                                    </div>

                                    <div id="documents-{{ $application->id }}" style="display:none; margin-top:1rem; padding-top:1rem; border-top:1px solid #e5e7eb;">
                                        <div class="level is-mobile mb-3">
                                            <div class="level-left">
                                                <p class="heading mb-0">Uploaded Documents</p>
                                            </div>
                                            <div class="level-right">
                                                @if($application->status === 'pending')
                                                    <button class="button is-small is-primary" onclick="toggleAddDocument({{ $application->id }})">
                                                        <span class="icon"><i class="fa-solid fa-plus"></i></span>
                                                        <span>Add Document</span>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Add Document Form (only shown for pending applications) -->
                                        @if($application->status === 'pending')
                                        <div id="add-document-{{ $application->id }}" style="display:none; margin-bottom:1rem; padding:1rem; background-color:#f9fafb; border-radius:8px; border:1px dashed #d1d5db;">
                                            <form method="POST" action="{{ route('student.applications.documents.add', $application->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="field">
                                                    <div class="file has-name">
                                                        <label class="file-label">
                                                            <input class="file-input" type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" required onchange="updateFileName(this, 'file-name-{{ $application->id }}')">
                                                            <span class="file-cta">
                                                                <span class="file-icon"><i class="fa-solid fa-upload"></i></span>
                                                                <span class="file-label">Choose file…</span>
                                                            </span>
                                                            <span class="file-name" id="file-name-{{ $application->id }}">No file selected</span>
                                                        </label>
                                                    </div>
                                                    <p class="help">Accepted formats: PDF, JPG, PNG (Max 10MB)</p>
                                                </div>
                                                <div class="field is-grouped">
                                                    <div class="control">
                                                        <button type="submit" class="button is-primary is-small">
                                                            <span class="icon"><i class="fa-solid fa-upload"></i></span>
                                                            <span>Upload</span>
                                                        </button>
                                                    </div>
                                                    <div class="control">
                                                        <button type="button" class="button is-light is-small" onclick="toggleAddDocument({{ $application->id }})">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        @endif

                                        <div class="columns is-multiline">
                                            @foreach($application->documents as $document)
                                                <div class="column is-6">
                                                    <div class="box" style="background-color:#f9fafb; padding:0.75rem;">
                                                        <div class="level is-mobile">
                                                            <div class="level-left">
                                                                <div>
                                                                    <span class="icon-text">
                                                                        <span class="icon has-text-primary"><i class="fa-solid fa-file-pdf"></i></span>
                                                                        <span>{{ $document->filename }}</span>
                                                                    </span>
                                                                    <p class="help">{{ number_format($document->file_size / 1024, 2) }} KB</p>
                                                                </div>
                                                            </div>
                                                            <div class="level-right">
                                                                <div class="buttons">
                                                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="button is-small is-light">
                                                                        <span class="icon"><i class="fa-solid fa-eye"></i></span>
                                                                        <span>View</span>
                                                                    </a>
                                                                    @if($application->status === 'pending')
                                                                        <form method="POST" action="{{ route('student.applications.documents.delete', [$application->id, $document->id]) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="button is-small is-danger is-light">
                                                                                <span class="icon"><i class="fa-solid fa-trash"></i></span>
                                                                                <span>Delete</span>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($applications->count() > 5)
                    <div class="card mt-4">
                        <div class="card-content">
                            <p class="has-text-centered subtitle is-6">
                                Showing all {{ $applications->count() }} application(s)
                            </p>
                        </div>
                    </div>
                @endif
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
                burger.addEventListener('click', ()=>{ 
                    burger.classList.toggle('is-active'); 
                    menu.classList.toggle('is-active'); 
                });
            }
        });

        function toggleDocuments(applicationId) {
            const documentsDiv = document.getElementById('documents-' + applicationId);
            if (documentsDiv) {
                documentsDiv.style.display = documentsDiv.style.display === 'none' ? 'block' : 'none';
            }
        }

        function toggleAddDocument(applicationId) {
            const addDocumentDiv = document.getElementById('add-document-' + applicationId);
            if (addDocumentDiv) {
                addDocumentDiv.style.display = addDocumentDiv.style.display === 'none' ? 'block' : 'none';
            }
        }

        function updateFileName(input, fileNameId) {
            const fileNameSpan = document.getElementById(fileNameId);
            if (fileNameSpan && input.files && input.files[0]) {
                fileNameSpan.textContent = input.files[0].name;
            } else {
                fileNameSpan.textContent = 'No file selected';
            }
        }
    </script>
</body>
</html>

