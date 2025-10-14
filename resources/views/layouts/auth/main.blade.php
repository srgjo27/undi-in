<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
@include('layouts.auth.head')

<body>
    <div class="auth-page-wrapper pt-5">
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>
            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>
        <div class="auth-page-content">

            @yield('content')

            <footer class="footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <p class="mb-0 text-muted">&copy; UndiIn | All Rights Reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <div class="customizer-setting d-none d-md-block">
        <div class="btn-info btn-rounded shadow-lg btn btn-icon btn-lg p-2" data-bs-toggle="modal"
            data-bs-target="#contactAdminModal" aria-controls="contactAdminModal" title="Contact Admin Support">
            <i class='ri-user-voice-line fs-16'></i>
        </div>
    </div>

    <!-- Contact Admin Modal -->
    <div class="modal fade" id="contactAdminModal" tabindex="-1" aria-labelledby="contactAdminModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactAdminModalLabel">
                        <i class="las la-headset me-2"></i>Contact Admin Support
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="contactAdminForm">
                        <div class="mb-3">
                            <label for="contactName" class="form-label">Full Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="contactName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="contactEmail" class="form-label">Email Address <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="contactEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="contactSubject" class="form-label">Subject <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="contactSubject" name="subject" required>
                                <option value="">Select a subject</option>
                                <option value="account_blocked">Account Blocked - Request Unblock</option>
                                <option value="login_issues">Login Issues</option>
                                <option value="account_recovery">Account Recovery</option>
                                <option value="general_support">General Support</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="contactMessage" class="form-label">Message <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="contactMessage" name="message" rows="4"
                                placeholder="Please describe your issue in detail..." required></textarea>
                        </div>
                        <div class="alert alert-info">
                            <i class="las la-info-circle me-2"></i>
                            <strong>Note:</strong> Admin will respond within 24-48 hours. Please check your email
                            regularly for updates.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="las la-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="sendContactForm">
                        <i class="las la-paper-plane me-1"></i>Send Message
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Methods -->
    <div class="modal fade" id="contactMethodsModal" tabindex="-1" aria-labelledby="contactMethodsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMethodsModalLabel">
                        <i class="las la-phone me-2"></i>Alternative Contact Methods
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="card border-primary contact-method-card">
                                <div class="card-body text-center">
                                    <i class="las la-envelope-open-text fs-24 text-primary mb-2"></i>
                                    <h6 class="card-title">Email Support</h6>
                                    <p class="card-text">operatorserbabisa123@gmail.com</p>
                                    <a href="mailto:operatorserbabisa123@gmail.com" class="btn btn-primary btn-sm">
                                        <i class="las la-envelope me-1"></i>Send Email
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="card border-success contact-method-card">
                                <div class="card-body text-center">
                                    <i class="lab la-whatsapp fs-24 text-success mb-2"></i>
                                    <h6 class="card-title">WhatsApp Support</h6>
                                    <p class="card-text">+62 812-3456-7890</p>
                                    <a href="https://wa.me/"
                                        target="_blank" class="btn btn-success btn-sm">
                                        <i class="lab la-whatsapp me-1"></i>Chat on WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card border-info contact-method-card">
                                <div class="card-body text-center">
                                    <i class="las la-phone fs-24 text-info mb-2"></i>
                                    <h6 class="card-title">Phone Support</h6>
                                    <p class="card-text">+62 21-1234-5678</p>
                                    <small class="text-muted">Available: Mon-Fri, 9:00 AM - 6:00 PM (WIB)</small>
                                    <br>
                                    <a href="tel:+622112345678" class="btn btn-info btn-sm mt-2">
                                        <i class="las la-phone me-1"></i>Call Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.auth.scripts')
</body>

</html>
