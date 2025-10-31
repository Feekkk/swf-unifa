<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>New Application - RCMP Unifa</title>
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
        .input, .textarea{background:#fff!important; color:#000!important; border:1px solid #cbd5e1}
        .select select{background:#fff!important; color:#000!important; border:1px solid #cbd5e1}
        label, .label, .subtitle, .help, .file-name { color:#000 !important }
        /* Keep 'Choose files…' CTA not black for contrast */
        .file-cta span { color:#fff !important }
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
            <h1 class="title is-3">New Application</h1>
            <p class="subtitle is-6">Fill in the details below to start your Student Welfare Fund application.</p>

            <div class="card">
                <div class="card-content">
                    <form method="POST" action="#">
                        @csrf
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <label class="label">Application Type</label>
                                <div class="select is-fullwidth">
                                    <select name="application_type" required>
                                        <option value="">Select type</option>
                                        <option value="bereavement">Bereavement (Khairat)</option>
                                        <option value="illness">Illness &amp; Injuries</option>
                                        <option value="emergency">Emergency</option>
                                    </select>
                                </div>
                            </div>

                            <div class="column is-6">
                                <label class="label">Requested Amount (RM)</label>
                                <input class="input" type="number" name="amount" min="0" step="1" placeholder="e.g., 200" required>
                            </div>
                            <div class="column is-6">
                                <label class="label">Bank Account Number</label>
                                <input class="input" type="text" name="bank_account_number" placeholder="e.g., 1234567890" required>
                            </div>

                            <div class="column is-12">
                                <label class="label">Reason / Description</label>
                                <textarea class="textarea" name="reason" rows="4" placeholder="Provide a brief description and justification" required></textarea>
                            </div>

                            <div class="column is-12">
                                <label class="label">Supporting Documents</label>
                                <div class="file has-name is-fullwidth">
                                    <label class="file-label">
                                        <input class="file-input" type="file" name="documents" multiple>
                                        <span class="file-cta">
                                            <span class="icon"><i class="fa-solid fa-paperclip"></i></span>
                                            <span>Choose files…</span>
                                        </span>
                                        <span class="file-name">Optional: Upload receipts, medical letters, etc.</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="field is-grouped">
                            <div class="control">
                                <button type="submit" class="button is-primary">
                                    <span class="icon"><i class="fa-solid fa-paper-plane"></i></span>
                                    <span>Submit Application</span>
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
        });
    </script>
</body>
</html>

