<script src="{{ asset('template/be/dist/default/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/js/plugins.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/particles.js/particles.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/js/pages/particles.app.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/js/pages/form-validation.init.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contactAdminForm');
        const sendButton = document.getElementById('sendContactForm');
        const contactModal = document.getElementById('contactAdminModal');

        // When blocked user clicks contact admin, pre-fill the form
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('blocked') === '1') {
            // Pre-select account blocked subject
            document.getElementById('contactSubject').value = 'account_blocked';
            // Pre-fill message for blocked account
            document.getElementById('contactMessage').value =
                'My account has been blocked and I cannot log in. Please review my account and restore access if appropriate. Thank you.';
        }

        // Handle contact form submission
        if (sendButton) {
            sendButton.addEventListener('click', function() {
                if (!contactForm.checkValidity()) {
                    contactForm.classList.add('was-validated');
                    return;
                }

                const formData = new FormData(contactForm);

                // Disable button and show loading
                sendButton.disabled = true;
                sendButton.innerHTML = '<i class="las la-spinner la-spin me-1"></i>Sending...';

                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');

                fetch('/contact-admin', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : '',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);

                            const modal = bootstrap.Modal.getInstance(contactModal);
                            modal.hide();

                            contactForm.reset();
                            contactForm.classList.remove('was-validated');
                        } else {
                            showAlert('danger', data.message);
                        }
                    })
                    .catch(error => {
                        showAlert('danger', 'An error occurred. Please try again.');
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        sendButton.disabled = false;
                        sendButton.innerHTML =
                            '<i class="las la-paper-plane me-1"></i>Send Message';
                    });
            });
        }

        // Show alert function
        function showAlert(type, message) {
            const existingAlerts = document.querySelectorAll('.modal-body .alert');
            existingAlerts.forEach(alert => alert.remove());

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
            <i class="las la-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

            // Insert at top of modal body
            const modalBody = contactModal.querySelector('.modal-body');
            modalBody.insertBefore(alertDiv, modalBody.firstChild);
        }

        // Add "View Alternative Contact Methods" button after form
        const modalFooter = contactModal.querySelector('.modal-footer');
        const altContactBtn = document.createElement('button');
        altContactBtn.type = 'button';
        altContactBtn.className = 'btn btn-outline-info me-auto';
        altContactBtn.innerHTML = '<i class="las la-phone me-1"></i>Alternative Methods';
        altContactBtn.setAttribute('data-bs-toggle', 'modal');

        // Insert before the cancel button
        modalFooter.insertBefore(altContactBtn, modalFooter.firstChild);

        // Handle alternative contact methods modal
        altContactBtn.addEventListener('click', function() {
            // Close the first modal
            const modal = bootstrap.Modal.getInstance(contactModal);
            modal.hide();

            // Open alternative methods modal
            setTimeout(() => {
                const altModal = new bootstrap.Modal(document.getElementById(
                    'contactMethodsModal'));
                altModal.show();
            }, 150);
        });
    });
</script>
