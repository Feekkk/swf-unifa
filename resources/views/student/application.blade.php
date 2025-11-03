<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>New Application - RCMP Unifa</title>
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
        .input, .textarea{background:#fff!important; color:#000!important; border:1px solid #cbd5e1}
        .select select{background:#fff!important; color:#000!important; border:1px solid #cbd5e1}
        label, .label, .subtitle, .help, .file-name { color:#000 !important }
        /* Keep 'Choose files…' CTA not black for contrast */
        .file-cta span { color:#fff !important }
        /* Application page specific styles */
        .section .title.is-3 { color: #000 !important }
        .message { background-color: inherit !important }
        .message-body { background-color: inherit !important }
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

            @if ($errors->any())
                <div class="notification is-danger is-light mb-4" style="border-left: 4px solid #f14668;">
                    <button class="delete" onclick="this.parentElement.remove()"></button>
                    <span class="icon mr-2"><i class="fa-solid fa-circle-exclamation"></i></span>
                    <ul style="margin-left:1rem">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php($u = auth()->user())
            <div class="card">
                <div class="card-content">
                    <form method="POST" action="{{ route('student.application.store') }}" enctype="multipart/form-data">
                        @csrf
                        <h3 class="title is-5" style="color:#191970">Application Category</h3>
                        <p class="subtitle is-6" style="color:#000">Select the category and sub-category of your application</p>
                        
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <label class="label">Category</label>
                                <div class="select is-fullwidth">
                                    <select id="category-select" name="category" required>
                                        <option value="">Select category</option>
                                        <option value="bereavement">Bereavement (Khairat)</option>
                                        <option value="illness">Illness &amp; Injuries</option>
                                        <option value="emergency">Emergency</option>
                                    </select>
                                </div>
                            </div>
                            <div class="column is-6">
                                <label class="label">Sub-category</label>
                                <div class="select is-fullwidth">
                                    <select id="subcategory-select" name="subcategory" required>
                                        <option value="">Select sub-category</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Amount Information Display -->
                            <div class="column is-12">
                                <article class="message is-info">
                                    <div class="message-body" id="amount-note">Please select a category and sub-category to see required details.</div>
                                </article>
                            </div>

                            <!-- Dynamic Form Fields Container -->
                            <div class="column is-12">
                                <div id="dynamic-fields-container"></div>
                            </div>

                            <!-- Bank Details Section (for most categories) -->
                            <div class="column is-12" data-section="bank-details" hidden>
                                <hr style="margin: 1.5rem 0;">
                                <h3 class="title is-5" style="color:#191970">Bank Details</h3>
                                <p class="subtitle is-6" style="color:#000">Bank account information for fund disbursement</p>
                                <div class="columns is-multiline">
                                    <div class="column is-6">
                                        <label class="label">Bank Name</label>
                                        <div class="control">
                                            <input class="input" type="text" name="bank_name" id="bank-name-input" 
                                                   value="{{ $u->bank_name ?? '' }}" required placeholder="e.g., Maybank, CIMB Bank">
                                        </div>
                                    </div>
                                    <div class="column is-6">
                                        <label class="label">Bank Account Number</label>
                                        <div class="control">
                                            <input class="input" type="text" name="bank_account_number" id="bank-account-input" 
                                                   value="{{ $u->bank_account_number ?? '' }}" required placeholder="Enter your account number">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents Upload Section -->
                            <div class="column is-12" data-section="uploader" hidden>
                                <hr style="margin: 1.5rem 0;">
                                <h3 class="title is-5" style="color:#191970">Supporting Documents</h3>
                                <p class="subtitle is-6" style="color:#000">Please upload all required documents</p>
                                <div class="file has-name is-fullwidth">
                                    <label class="file-label">
                                        <input class="file-input" type="file" id="upload-input" name="documents[]" multiple>
                                        <span class="file-cta"><span class="icon"><i class="fa-solid fa-paperclip"></i></span><span>Choose files…</span></span>
                                        <span class="file-name" id="upload-help">Upload relevant documents</span>
                                    </label>
                                </div>
                                <p class="help">You can upload multiple files (PDF, JPG, PNG)</p>
                                <input type="hidden" name="amount" id="fixed-amount-hidden">
                            </div>

                            <!-- Requirements List -->
                            <div class="column is-12" data-section="requirements" hidden>
                                <div class="card" style="background-color: #F0F8FF;">
                                    <div class="card-content">
                                        <p class="title is-6" style="color:#191970"><i class="fa-solid fa-circle-info mr-2"></i>Required Documents</p>
                                        <ul id="requirements-list" class="content" style="margin-left:1rem; color:#000">
                                            <li>Select a category to view requirements</li>
                                        </ul>
                                    </div>
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

            // Dynamic form by category/subcategory
            const categorySelect = document.getElementById('category-select');
            const subcategorySelect = document.getElementById('subcategory-select');
            const amountNote = document.getElementById('amount-note');
            const fieldsContainer = document.getElementById('dynamic-fields-container');
            const reqList = document.getElementById('requirements-list');
            const bankDetails = document.querySelector('[data-section="bank-details"]');
            const uploader = document.querySelector('[data-section="uploader"]');
            const requirements = document.querySelector('[data-section="requirements"]');
            const fixedAmountHidden = document.getElementById('fixed-amount-hidden');

            function setRequirements(items){
                reqList.innerHTML = '';
                if(!items || items.length===0){
                    const li = document.createElement('li'); 
                    li.textContent = 'No specific documents required.'; 
                    reqList.appendChild(li); 
                    return;
                }
                items.forEach(text => { 
                    const li = document.createElement('li'); 
                    li.textContent = text; 
                    reqList.appendChild(li); 
                });
            }

            function hideAll() {
                fieldsContainer.innerHTML = '';
                if (bankDetails) bankDetails.hidden = true;
                if (uploader) uploader.hidden = true;
                if (requirements) requirements.hidden = true;
            }

            function createField(type, name, label, placeholder = '', required = false, attrs = {}) {
                const fieldDiv = document.createElement('div');
                fieldDiv.className = 'field column is-6';
                
                const labelEl = document.createElement('label');
                labelEl.className = 'label';
                labelEl.textContent = label;
                if (required) labelEl.innerHTML += ' <span style="color: red;">*</span>';
                
                const controlDiv = document.createElement('div');
                controlDiv.className = 'control';
                
                let input;
                if (type === 'textarea') {
                    input = document.createElement('textarea');
                    input.className = 'textarea';
                    input.rows = 3;
                } else {
                    input = document.createElement('input');
                    input.className = 'input';
                    input.type = type;
                }
                
                input.name = name;
                input.id = name;
                input.placeholder = placeholder;
                if (required) input.required = true;
                
                // Add additional attributes
                Object.keys(attrs).forEach(key => {
                    if (key === 'step' || key === 'min' || key === 'max') {
                        input.setAttribute(key, attrs[key]);
                    } else {
                        input[key] = attrs[key];
                    }
                });
                
                controlDiv.appendChild(input);
                fieldDiv.appendChild(labelEl);
                fieldDiv.appendChild(controlDiv);
                
                return fieldDiv;
            }

            function createFieldsContainer(fields) {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'columns is-multiline';
                fields.forEach(field => {
                    rowDiv.appendChild(field);
                });
                fieldsContainer.appendChild(rowDiv);
            }

            function setFixedAmount(rm) {
                fixedAmountHidden.value = rm;
            }

            function setEditableAmount(limitText) {
                fixedAmountHidden.value = '';
                amountNote.textContent = limitText || '';
            }

            function populateSubcategories() {
                subcategorySelect.innerHTML = '';
                const placeholder = document.createElement('option');
                placeholder.value = '';
                placeholder.textContent = 'Select sub-category';
                subcategorySelect.appendChild(placeholder);
                const cat = categorySelect.value;
                if (cat === 'bereavement') {
                    ['Student','Parent','Sibling'].forEach((label, i) => {
                        const val = ['student','parent','sibling'][i];
                        const opt = document.createElement('option');
                        opt.value = val; opt.textContent = label; subcategorySelect.appendChild(opt);
                    });
                } else if (cat === 'illness') {
                    [['outpatient','Out-patient treatment'],['inpatient','In-patient treatment'],['injuries','Injuries']].forEach(([v,l])=>{
                        const opt = document.createElement('option'); opt.value=v; opt.textContent=l; subcategorySelect.appendChild(opt);
                    });
                } else if (cat === 'emergency') {
                    [['critical','Critical Illness'],['disaster','Natural Disaster'],['others','Others']].forEach(([v,l])=>{
                        const opt = document.createElement('option'); opt.value=v; opt.textContent=l; subcategorySelect.appendChild(opt);
                    });
                }
                amountNote.textContent = 'Please choose a sub-category to proceed.';
                hideAll();
            }

            function onSubcategoryChange() {
                hideAll();
                const cat = categorySelect.value; 
                const sub = subcategorySelect.value;
                
                if (!cat || !sub) return;

                if (cat === 'bereavement') {
                    if (sub === 'student') { 
                        setFixedAmount(500); 
                        amountNote.textContent = 'Bereavement (Student): Fixed RM 500.'; 
                        setRequirements(['Death Certificate']);
                    }
                    else if (sub === 'parent') { 
                        setFixedAmount(200); 
                        amountNote.textContent = 'Bereavement (Parent): Fixed RM 200.'; 
                        setRequirements(['Death Certificate']);
                    }
                    else if (sub === 'sibling') { 
                        setFixedAmount(100); 
                        amountNote.textContent = 'Bereavement (Sibling): Fixed RM 100.'; 
                        setRequirements(['Death Certificate']);
                    }
                    // All bereavement need bank details
                    if (bankDetails) bankDetails.hidden = false;
                    if (uploader) uploader.hidden = false;
                    if (requirements) requirements.hidden = false;
                }
                else if (cat === 'illness') {
                    if (sub === 'outpatient') {
                        setEditableAmount('Out-patient: Limited to RM 30 per semester.');
                        setRequirements(['Receipt Clinic']);
                        createFieldsContainer([
                            createField('text', 'clinic_name', 'Clinic Name', 'Enter clinic/hospital name', true),
                            createField('textarea', 'reason_visit', 'Reason for Visit', 'Describe the reason for your visit', true),
                            createField('datetime-local', 'visit_date', 'Date & Time of Visit', '', true),
                            createField('number', 'amount_applied', 'Total Amount Applied (RM)', '0.00', true, {step: '0.01', min: '0'}),
                        ]);
                        if (bankDetails) bankDetails.hidden = false;
                        if (uploader) uploader.hidden = false;
                        if (requirements) requirements.hidden = false;
                    } else if (sub === 'inpatient') {
                        setEditableAmount('In-patient: Limit up to RM 10,000.');
                        setRequirements(['Report, Discharge Note, Hospital Bill']);
                        createFieldsContainer([
                            createField('textarea', 'reason_visit', 'Reason for Visit', 'Describe the reason for hospitalization', true),
                            createField('date', 'check_in_date', 'Check-in Date', '', true),
                            createField('date', 'check_out_date', 'Check-out Date', '', true),
                            createField('number', 'amount_applied', 'Total Amount Applied (RM)', '0.00', true, {step: '0.01', min: '0', max: '10000'}),
                        ]);
                        if (bankDetails) bankDetails.hidden = false;
                        if (uploader) uploader.hidden = false;
                        if (requirements) requirements.hidden = false;
                    } else if (sub === 'injuries') {
                        setEditableAmount('Injuries: Coverage up to RM 200 for support equipment.');
                        setRequirements(['Hospital Report, Receipt of purchased']);
                        createFieldsContainer([
                            createField('number', 'amount_applied', 'Total Amount Applied (RM)', '0.00', true, {step: '0.01', min: '0', max: '200'}),
                        ]);
                        if (bankDetails) bankDetails.hidden = false;
                        if (uploader) uploader.hidden = false;
                        if (requirements) requirements.hidden = false;
                    }
                }
                else if (cat === 'emergency') {
                    if (sub === 'critical') {
                        setEditableAmount('Critical Illness: Coverage up to RM 200.');
                        setRequirements(['Supporting Document']);
                        createFieldsContainer([
                            createField('number', 'amount_applied', 'Total Amount Applied (RM)', '0.00', true, {step: '0.01', min: '0', max: '200'}),
                        ]);
                        if (bankDetails) bankDetails.hidden = false;
                        if (uploader) uploader.hidden = false;
                        if (requirements) requirements.hidden = false;
                    } else if (sub === 'disaster') {
                        setEditableAmount('Natural Disaster: Coverage up to RM 200.');
                        setRequirements(['Supporting Document (Police Report, Photo of incident)']);
                        createFieldsContainer([
                            createField('textarea', 'case_description', 'Case Description', 'Describe what happened and how it affected you', true),
                            createField('number', 'amount_applied', 'Total Amount Applied (RM)', '0.00', true, {step: '0.01', min: '0', max: '200'}),
                        ]);
                        if (bankDetails) bankDetails.hidden = false;
                        if (uploader) uploader.hidden = false;
                        if (requirements) requirements.hidden = false;
                    } else if (sub === 'others') {
                        setEditableAmount('Other emergencies: Subject to committee approval (limit RM 5,000).');
                        setRequirements(['Supporting Document']);
                        createFieldsContainer([
                            createField('textarea', 'case_description', 'Case Description', 'Describe your emergency situation in detail', true),
                            createField('number', 'amount_applied', 'Total Amount Applied (RM)', '0.00', true, {step: '0.01', min: '0', max: '5000'}),
                        ]);
                        if (bankDetails) bankDetails.hidden = false;
                        if (uploader) uploader.hidden = false;
                        if (requirements) requirements.hidden = false;
                    }
                }
            }

            if (categorySelect && subcategorySelect) {
                categorySelect.addEventListener('change', populateSubcategories);
                subcategorySelect.addEventListener('change', onSubcategoryChange);
            }

            // Handle file upload display
            const fileInput = document.getElementById('upload-input');
            if (fileInput) {
                fileInput.addEventListener('change', (e) => {
                    const files = e.target.files;
                    const helpText = document.getElementById('upload-help');
                    if (files && files.length > 0) {
                        const names = Array.from(files).map(f => f.name).join(', ');
                        helpText.textContent = names;
                    } else {
                        helpText.textContent = 'Upload relevant documents';
                    }
                });
            }
        });
    </script>
</body>
</html>

