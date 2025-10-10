<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/js/plugins.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/jsvectormap/maps/world-merc.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/libs/fg-emoji-picker/fgEmojiPicker.js') }}"></script>
@if(!request()->routeIs('messages.*'))
<script src="{{ asset('template/be/dist/default/assets/js/pages/chat.init.js') }}"></script>
@endif
<script src="{{ asset('template/be/dist/default/assets/js/pages/profile.init.js') }}"></script>
<script src="{{ asset('template/be/dist/default/assets/js/pages/profile-setting.init.js') }}"></script>

<script src="{{ asset('template/be/src/assets/js/app.js') }}"></script>

<!-- Message notification updater -->
<script>
$(document).ready(function() {
    // Only run notification updater if we have the required elements
    if ($('#page-header-notifications-dropdown').length === 0) {
        return;
    }
    
    // Update message notification count every 30 seconds
    function updateMessageNotifications() {
        $.get('{{ route("messages.unread-count") }}', function(data) {
            const count = data.unread_count;
            
            // Update bell notification badge
            const bellBadge = $('.topbar-badge');
            const messageBadge = $('.btn-topbar .topbar-badge');
            
            if (count > 0) {
                // Update bell notification
                if (bellBadge.length) {
                    bellBadge.text(count > 99 ? '99+' : count).show();
                } else {
                    $('#page-header-notifications-dropdown i').after(
                        `<span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">
                            ${count > 99 ? '99+' : count}
                            <span class="visually-hidden">unread messages</span>
                        </span>`
                    );
                }
                
                // Update message icon badge
                const messageIcon = $('.btn-topbar .las.la-envelope').parent();
                const existingBadge = messageIcon.find('.topbar-badge');
                if (existingBadge.length) {
                    existingBadge.text(count > 9 ? '9+' : count);
                } else {
                    messageIcon.append(
                        `<span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-success">
                            ${count > 9 ? '9+' : count}
                            <span class="visually-hidden">unread messages</span>
                        </span>`
                    );
                }
                
                // Update notification header
                $('.dropdown-tabs .badge').text(count + ' Baru');
                
            } else {
                // Hide badges when no unread messages
                bellBadge.hide();
                $('.btn-topbar .topbar-badge').remove();
                $('.dropdown-tabs .badge').text('');
            }
        }).catch(function(error) {
            console.log('Error updating notifications:', error);
        });
    }
    
    // Update immediately and then every 30 seconds
    updateMessageNotifications();
    setInterval(updateMessageNotifications, 30000);
    
    // Also update when message page is focused (user comes back to tab)
    $(window).on('focus', function() {
        updateMessageNotifications();
    });
});
</script>

@stack('scripts')
