@extends('layouts.be')

@section('title', 'Messages')

@section('css')
    <style>
        .chat-empty-state {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .conversation-item.active {
            background-color: #f8f9fa;
        }

        .conversation-item.active a {
            color: #495057;
        }

        .message-box-drop {
            visibility: hidden;
        }

        .chat-list:hover .message-box-drop {
            visibility: visible;
        }

        #chatContent {
            height: calc(100vh - 140px);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
            <div class="chat-leftsidebar">
                <div class="px-4 pt-4 mb-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="mb-4">Chats</h5>
                        </div>
                        <div class="flex-shrink-0">
                            <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom"
                                title="Add Contact">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-soft-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#newChatModal">
                                    <i class="ri-add-line align-bottom"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="search-box">
                        <input type="text" class="form-control bg-light border-light"
                            placeholder="Search conversations..." id="searchConversations">
                        <i class="ri-search-2-line search-icon"></i>
                    </div>
                </div>

                <div class="chat-room-list" data-simplebar>
                    <div class="d-flex align-items-center px-4 mb-2">
                        <div class="flex-grow-1">
                            <h4 class="mb-0 fs-11 text-muted text-uppercase">Direct Messages</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="bottom"
                                title="New Message">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-soft-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#newChatModal">
                                    <i class="ri-add-line align-bottom"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="chat-message-list">
                        <ul class="list-unstyled chat-list chat-user-list" id="conversationsList">
                            <!-- Conversations will be loaded here dynamically -->
                            @foreach ($conversations as $conversation)
                                @php
                                    $otherUser = $conversation->activeParticipants->first();
                                @endphp
                                @if ($otherUser)
                                    <li class="conversation-item" data-conversation-id="{{ $conversation->id }}">
                                        <a href="javascript:void(0);" onclick="loadConversation({{ $conversation->id }})">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 chat-user-img online align-self-center me-2 ms-0">
                                                    <div class="avatar-xxs">
                                                        <div class="avatar-title bg-primary rounded-circle">
                                                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                                        </div>
                                                    </div>
                                                    <span class="user-status"></span>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-truncate mb-0">{{ $otherUser->name }}</p>
                                                    @if ($conversation->latestMessage)
                                                        <small
                                                            class="text-muted">{{ Str::limit($conversation->latestMessage->content, 30) }}</small>
                                                    @endif
                                                </div>
                                                @if ($conversation->unread_count > 0)
                                                    <div class="flex-shrink-0">
                                                        <span
                                                            class="badge badge-soft-danger rounded p-1">{{ $conversation->unread_count }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <!-- End chat-message-list -->
                </div>
            </div>
            <!-- end chat leftsidebar -->

            <!-- Start User chat -->
            <div class="user-chat w-100 overflow-hidden" id="chatArea">
                <!-- Empty state -->
                <div class="chat-empty-state text-center py-5" id="emptyChatState">
                    <div class="py-5">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                <i class="ri-message-3-line fs-1"></i>
                            </div>
                        </div>
                        <h5>Selamat Datang di Pesan!</h5>
                        <p class="text-muted">Pilih percakapan dari sidebar atau mulai percakapan baru.</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newChatModal">
                            <i class="ri-add-line me-1"></i> Mulai Percakapan Baru
                        </button>
                    </div>
                </div>

                <!-- Chat content -->
                <div class="chat-content d-lg-flex" id="chatContent" style="display: none !important;">
                    <!-- start chat conversation section -->
                    <div class="w-100 overflow-hidden position-relative">
                        <!-- conversation user -->
                        <div class="position-relative">
                            <div class="p-3 user-chat-topbar">
                                <div class="row align-items-center">
                                    <div class="col-sm-4 col-8">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 d-block d-lg-none me-3">
                                                <a href="javascript: void(0);" class="user-chat-remove fs-18 p-1"><i
                                                        class="ri-arrow-left-s-line align-bottom"></i></a>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="flex-shrink-0 chat-user-img online user-own-img align-self-center me-3 ms-0">
                                                        <div class="avatar-xs rounded-circle" id="currentChatAvatar">
                                                            <div class="avatar-title bg-primary rounded-circle"
                                                                id="currentChatAvatarText">
                                                                U
                                                            </div>
                                                        </div>
                                                        <span class="user-status"></span>
                                                    </div>
                                                    <div class="flex-grow-1 overflow-hidden">
                                                        <h5 class="text-truncate mb-0 fs-16">
                                                            <span class="text-reset username"
                                                                id="currentChatUsername">Select a conversation</span>
                                                        </h5>
                                                        <p class="text-truncate text-muted fs-14 mb-0 userStatus">
                                                            <small id="currentChatStatus">Online</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8 col-4">
                                        <ul class="list-inline user-chat-nav text-end mb-0">
                                            <li class="list-inline-item m-0">
                                                <div class="dropdown">
                                                    <button class="btn btn-ghost-secondary btn-icon" type="button"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i data-feather="search" class="icon-sm"></i>
                                                    </button>
                                                    <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg">
                                                        <div class="p-2">
                                                            <div class="search-box">
                                                                <input type="text"
                                                                    class="form-control bg-light border-light"
                                                                    placeholder="Search here..."
                                                                    onkeyup="searchMessages()" id="searchMessage">
                                                                <i class="ri-search-2-line search-icon"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                            <li class="list-inline-item d-none d-lg-inline-block m-0">
                                                <button type="button" class="btn btn-ghost-secondary btn-icon"
                                                    data-bs-toggle="offcanvas" data-bs-target="#userProfileCanvasExample"
                                                    aria-controls="userProfileCanvasExample">
                                                    <i data-feather="info" class="icon-sm"></i>
                                                </button>
                                            </li>

                                            <li class="list-inline-item m-0">
                                                <div class="dropdown">
                                                    <button class="btn btn-ghost-secondary btn-icon" type="button"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i data-feather="more-vertical" class="icon-sm"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item d-block d-lg-none user-profile-show"
                                                            href="#"><i
                                                                class="ri-user-2-fill align-bottom text-muted me-2"></i>
                                                            View Profile</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ri-inbox-archive-line align-bottom text-muted me-2"></i>
                                                            Archive</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ri-mic-off-line align-bottom text-muted me-2"></i>
                                                            Muted</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="ri-delete-bin-5-line align-bottom text-muted me-2"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- end chat user head -->

                            <div class="position-relative" id="users-chat">
                                <div class="chat-conversation p-3 p-lg-4" id="chat-conversation" data-simplebar>
                                    <ul class="list-unstyled chat-conversation-list" id="users-conversation">
                                        <!-- Messages will be loaded here dynamically -->
                                    </ul>
                                    <!-- end chat-conversation-list -->
                                </div>
                                <div class="alert alert-warning alert-dismissible copyclipboard-alert px-4 fade show "
                                    id="copyClipBoard" role="alert">
                                    Message copied
                                </div>
                            </div>
                            <!-- end chat-conversation -->

                            <div class="chat-input-section p-3 p-lg-4">
                                <form id="chatinput-form" enctype="multipart/form-data">
                                    <div class="row g-0 align-items-center">
                                        <div class="col-auto">
                                            <div class="chat-input-links me-2">
                                                <div class="links-list-item">
                                                    <button type="button"
                                                        class="btn btn-link text-decoration-none emoji-btn"
                                                        id="emoji-btn">
                                                        <i class="bx bx-smile align-middle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="chat-input-feedback">
                                                Please Enter a Message
                                            </div>
                                            <input type="text" class="form-control chat-input bg-light border-light"
                                                id="chat-input" placeholder="Ketik pesan..." autocomplete="off">
                                        </div>
                                        <div class="col-auto">
                                            <div class="chat-input-links ms-2">
                                                <div class="links-list-item">
                                                    <button type="submit"
                                                        class="btn btn-success chat-send waves-effect waves-light"
                                                        id="send-message-btn">
                                                        <i class="ri-send-plane-2-fill align-bottom"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="replyCard">
                                <div class="card mb-0">
                                    <div class="card-body py-3">
                                        <div class="replymessage-block mb-0 d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h5 class="conversation-name"></h5>
                                                <p class="mb-0"></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <button type="button" id="close_toggle"
                                                    class="btn btn-sm btn-link mt-n2 me-n3 fs-18">
                                                    <i class="bx bx-x align-middle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end chat-wrapper -->
    </div>

    <!-- New Chat Modal -->
    <div class="modal fade" id="newChatModal" tabindex="-1" aria-labelledby="newChatModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newChatModalLabel">Mulai Percakapan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="search-box mb-3">
                        <input type="text" class="form-control bg-light border-light" placeholder="Cari pengguna..."
                            id="searchUsers">
                        <i class="ri-search-2-line search-icon"></i>
                    </div>
                    <div id="usersList">
                        <!-- Users will be loaded here -->
                        @foreach ($availableUsers as $user)
                            <div class="d-flex align-items-center p-2 border rounded mb-2 user-item"
                                data-user-id="{{ $user->id }}" style="cursor: pointer;">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <div class="avatar-title bg-primary rounded-circle">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <small class="text-muted">{{ ucfirst($user->role) }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentConversationId = null;
        let currentUserId = {{ auth()->id() }};
        let conversations = {!! json_encode($conversations) !!};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            if (window.location.pathname.includes('/messages')) {
                console.log('Initializing custom messaging functionality');
                setTimeout(initializeMessaging, 100);
            }
        });

        function initializeMessaging() {
            console.log('Initializing messaging with', conversations.length, 'conversations');

            if (conversations.length === 0) {
                $('#emptyChatState').show();
                $('#chatContent').hide();
            } else {
                $('#emptyChatState').show();
                $('#chatContent').hide();
            }

            $(document).on('click', '.user-item', function() {
                const userId = $(this).data('user-id');
                startConversation(userId);
            });

            $('#searchConversations').on('input', function() {
                const query = $(this).val().toLowerCase();
                $('.conversation-item').each(function() {
                    const name = $(this).find('p').first().text().toLowerCase();
                    if (name.includes(query)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('#searchUsers').on('input', function() {
                const query = $(this).val().toLowerCase();
                $('.user-item').each(function() {
                    const name = $(this).find('h6').text().toLowerCase();
                    if (name.includes(query)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('#chatinput-form').off('submit').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (currentConversationId) {
                    sendMessage();
                } else {
                    showToast('Pilih percakapan terlebih dahulu', 'error');
                }
                return false;
            });

            if (typeof FgEmojiPicker !== 'undefined') {
                try {
                    const emojiPicker = new FgEmojiPicker({
                        trigger: [".emoji-btn"],
                        removeOnSelection: false,
                        closeButton: true,
                        position: ["top", "right"],
                        preFetch: true,
                        insertInto: document.querySelector(".chat-input"),
                    });
                } catch (e) {
                    console.log('Emoji picker already initialized or error:', e);
                }
            }

            $(document).on('click', '.delete-message', function() {
                const messageId = $(this).data('message-id');
                deleteMessage(messageId);
            });

            $(document).on('click', '.edit-message', function() {
                const messageId = $(this).data('message-id');
                const currentContent = $(this).closest('.chat-list').find('.ctext-content').text();
                editMessage(messageId, currentContent);
            });

            $(document).on('click', '.copy-message', function() {
                const content = $(this).closest('.chat-list').find('.ctext-content').text();
                navigator.clipboard.writeText(content);
                showToast('Pesan berhasil disalin!');
            });
        }

        function startConversation(userId) {
            $.ajax({
                url: '{{ route('messages.start-conversation') }}',
                method: 'POST',
                data: {
                    user_id: userId
                },
                success: function(response) {
                    $('#newChatModal').modal('hide');
                    currentConversationId = response.conversation.id;

                    updateConversationsList(response.conversation, response.unread_count);

                    loadConversation(response.conversation.id);
                },
                error: function(xhr) {
                    showToast('Gagal memulai percakapan: ' + xhr.responseJSON.error, 'error');
                }
            });
        }

        function loadConversation(conversationId) {
            console.log('Loading conversation:', conversationId);
            currentConversationId = conversationId;

            $('.conversation-item').removeClass('active');
            $(`.conversation-item[data-conversation-id="${conversationId}"]`).addClass('active');

            $('#emptyChatState').hide();
            $('#chatContent').show();
            $('#users-conversation').html(
                '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                );

            $.ajax({
                url: `{{ url('/messages/conversations') }}/${conversationId}/messages`,
                method: 'GET',
                success: function(response) {
                    console.log('Messages loaded successfully:', response);
                    displayMessages(response.messages);
                    updateChatHeader(response.conversation);

                    $(`.conversation-item[data-conversation-id="${conversationId}"] .badge`).remove();
                },
                error: function(xhr) {
                    console.error('Error loading messages:', xhr);
                    $('#users-conversation').html(`
                <div class="text-center py-5">
                    <div class="avatar-md mx-auto mb-4">
                        <div class="avatar-title bg-soft-danger text-danger rounded-circle">
                            <i class="ri-error-warning-line fs-2"></i>
                        </div>
                    </div>
                    <h6 class="mb-2 text-danger">Gagal memuat pesan</h6>
                    <p class="text-muted">Terjadi kesalahan saat memuat percakapan.</p>
                    <button class="btn btn-sm btn-outline-primary" onclick="loadConversation(${conversationId})">
                        <i class="ri-refresh-line me-1"></i> Coba Lagi
                    </button>
                </div>
            `);
                    showToast('Gagal memuat pesan', 'error');
                }
            });
        }

        function displayMessages(messages) {
            const conversationList = $('#users-conversation');
            conversationList.empty();

            if (messages.length === 0) {
                conversationList.html(`
            <div class="text-center py-5">
                <div class="avatar-md mx-auto mb-4">
                    <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                        <i class="ri-chat-3-line fs-2"></i>
                    </div>
                </div>
                <h6 class="mb-2">Belum ada pesan</h6>
                <p class="text-muted">Mulai percakapan dengan mengirim pesan pertama.</p>
            </div>
        `);
            } else {
                messages.forEach(function(message) {
                    const messageHtml = createMessageHtml(message);
                    conversationList.append(messageHtml);
                });

                scrollToBottom();
            }
        }

        function createMessageHtml(message) {
            const isOwn = message.sender_id === currentUserId;
            const messageClass = isOwn ? 'right' : 'left';
            const timeFormatted = new Date(message.created_at).toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });

            let avatarHtml = '';
            if (!isOwn) {
                avatarHtml = `
            <div class="chat-avatar">
                <div class="avatar-xs">
                    <div class="avatar-title bg-primary rounded-circle">
                        ${message.sender.name.charAt(0).toUpperCase()}
                    </div>
                </div>
            </div>
        `;
            }

            let actionsHtml = '';
            if (isOwn) {
                const canEdit = new Date() - new Date(message.created_at) < 5 * 60 * 1000; // 5 minutes
                actionsHtml = `
            <div class="dropdown align-self-start message-box-drop">
                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="ri-more-2-fill"></i>
                </a>
                <div class="dropdown-menu">
                    ${canEdit ? `<a class="dropdown-item edit-message" href="#" data-message-id="${message.id}">
                            <i class="ri-edit-line me-2 text-muted align-bottom"></i>Edit</a>` : ''}
                    <a class="dropdown-item copy-message" href="#">
                        <i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>
                    <a class="dropdown-item delete-message" href="#" data-message-id="${message.id}">
                        <i class="ri-delete-bin-5-line me-2 text-muted align-bottom"></i>Delete</a>
                </div>
            </div>
        `;
            } else {
                actionsHtml = `
            <div class="dropdown align-self-start message-box-drop">
                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="ri-more-2-fill"></i>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item copy-message" href="#">
                        <i class="ri-file-copy-line me-2 text-muted align-bottom"></i>Copy</a>
                </div>
            </div>
        `;
            }

            const editedIndicator = message.edited_at ? ' <small class="text-muted">(edited)</small>' : '';

            return `
        <li class="chat-list ${messageClass}" data-message-id="${message.id}">
            <div class="conversation-list">
                ${avatarHtml}
                <div class="user-chat-content">
                    <div class="ctext-wrap">
                        <div class="ctext-wrap-content">
                            <p class="mb-0 ctext-content">${escapeHtml(message.content)}</p>
                        </div>
                        ${actionsHtml}
                    </div>
                    <div class="conversation-name">
                        <small class="text-muted time">${timeFormatted}${editedIndicator}</small>
                        ${isOwn ? '<span class="text-success check-message-icon"><i class="ri-check-double-line align-bottom"></i></span>' : ''}
                    </div>
                </div>
            </div>
        </li>
    `;
        }

        function sendMessage() {
            const input = $('#chat-input');
            const content = input.val().trim();

            if (!content || !currentConversationId) {
                return;
            }

            input.prop('disabled', true);
            $('#send-message-btn').prop('disabled', true);

            $.ajax({
                url: `{{ url('/messages/conversations') }}/${currentConversationId}/messages`,
                method: 'POST',
                data: {
                    content: content
                },
                success: function(response) {
                    const messageHtml = createMessageHtml(response.message);
                    $('#users-conversation').append(messageHtml);

                    input.val('').prop('disabled', false);
                    $('#send-message-btn').prop('disabled', false);

                    scrollToBottom();

                    updateConversationInSidebar(response.message);
                },
                error: function(xhr) {
                    console.error('Error sending message:', xhr);
                    input.prop('disabled', false);
                    $('#send-message-btn').prop('disabled', false);

                    let errorMessage = 'Gagal mengirim pesan';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage += ': ' + xhr.responseJSON.error;
                    }
                    showToast(errorMessage, 'error');
                }
            });
        }

        function deleteMessage(messageId) {
            if (!confirm('Apakah Anda yakin ingin menghapus pesan ini?')) {
                return;
            }

            $.ajax({
                url: `{{ url('/messages/messages') }}/${messageId}`,
                method: 'DELETE',
                success: function() {
                    $(`.chat-list[data-message-id="${messageId}"]`).remove();
                    showToast('Pesan berhasil dihapus');
                },
                error: function() {
                    showToast('Gagal menghapus pesan', 'error');
                }
            });
        }

        function editMessage(messageId, currentContent) {
            const newContent = prompt('Edit pesan:', currentContent);
            if (!newContent || newContent === currentContent) {
                return;
            }

            $.ajax({
                url: `{{ url('/messages/messages') }}/${messageId}`,
                method: 'PATCH',
                data: {
                    content: newContent
                },
                success: function(response) {
                    const messageElement = $(`.chat-list[data-message-id="${messageId}"]`);
                    messageElement.find('.ctext-content').text(response.message.content);
                    messageElement.find('.time').append(' <small class="text-muted">(edited)</small>');
                    showToast('Pesan berhasil diperbarui');
                },
                error: function(xhr) {
                    showToast('Gagal memperbarui pesan: ' + xhr.responseJSON.error, 'error');
                }
            });
        }

        function updateChatHeader(conversation) {
            if (conversation.active_participants && conversation.active_participants.length > 0) {
                const otherUser = conversation.active_participants[0];
                
                // Update chat header
                $('#currentChatUsername').text(otherUser.name);
                $('#currentChatAvatarText').text(otherUser.name.charAt(0).toUpperCase());
                $('#currentChatStatus').text('Online');
                
                // Simpan data user untuk profile canvas
                currentChatUser = otherUser;
            }
        }

        function updateConversationsList(conversation, unreadCount) {
            if ($(`.conversation-item[data-conversation-id="${conversation.id}"]`).length === 0) {
                const otherUser = conversation.active_participants[0];
                const conversationHtml = `
            <li class="conversation-item" data-conversation-id="${conversation.id}">
                <a href="javascript:void(0);" onclick="loadConversation(${conversation.id})">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 chat-user-img online align-self-center me-2 ms-0">
                            <div class="avatar-xxs">
                                <div class="avatar-title bg-primary rounded-circle">
                                    ${otherUser.name.charAt(0).toUpperCase()}
                                </div>
                            </div>
                            <span class="user-status"></span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-truncate mb-0">${otherUser.name}</p>
                        </div>
                        ${unreadCount > 0 ? `<div class="flex-shrink-0">
                                <span class="badge badge-soft-danger rounded p-1">${unreadCount}</span>
                            </div>` : ''}
                    </div>
                </a>
            </li>
        `;
                $('#conversationsList').prepend(conversationHtml);
            }
        }

        function updateConversationInSidebar(message) {
            const conversationItem = $(`.conversation-item[data-conversation-id="${currentConversationId}"]`);
            if (conversationItem.length > 0) {
                conversationItem.find('small').text(message.content.substring(0, 30) + (message.content.length > 30 ?
                    '...' : ''));
                $('#conversationsList').prepend(conversationItem);
            }
        }

        function scrollToBottom() {
            const chatConversation = document.getElementById('chat-conversation');
            if (chatConversation) {
                chatConversation.scrollTop = chatConversation.scrollHeight;
            }
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) {
                return map[m];
            });
        }

        function showToast(message, type = 'success') {
            const toast = $(`
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <strong class="me-auto">${type === 'success' ? 'Berhasil' : 'Error'}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">${escapeHtml(message)}</div>
            </div>
        </div>
    `);

            $('body').append(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // Variabel global untuk menyimpan data user yang sedang chat
        let currentChatUser = null;

        // Event handler untuk tombol info (buka profile canvas)
        $(document).on('click', '[data-bs-target="#userProfileCanvasExample"]', function() {
            if (currentChatUser) {
                populateUserProfile(currentChatUser);
            }
        });

        // Event handler untuk user profile show di dropdown mobile
        $(document).on('click', '.user-profile-show', function(e) {
            e.preventDefault();
            if (currentChatUser) {
                populateUserProfile(currentChatUser);
                // Trigger offcanvas programmatically
                const offcanvas = new bootstrap.Offcanvas(document.getElementById('userProfileCanvasExample'));
                offcanvas.show();
            }
        });

        function populateUserProfile(user) {
            console.log('Populating user profile with data:', user);
            
            // Update profile section
            $('#profileUsername').text(user.name || '-');
            $('#profileAvatarText').text(user.name ? user.name.charAt(0).toUpperCase() : '?');
            $('#profileStatus').text('Online');
            $('#profileRole').text(getRoleDisplayName(user.role) || '-');

            // Update personal details section
            $('#profilePhone').text(user.phone_number || user.phone || '-');
            $('#profileEmail').text(user.email || '-');
            $('#profileRoleDetail').text(getRoleDisplayName(user.role) || '-');
            $('#profileAddress').text(user.address || '-');
        }

        function getRoleDisplayName(role) {
            const roleNames = {
                'admin': 'Administrator',
                'seller': 'Penjual',
                'user': 'Pengguna'
            };
            return roleNames[role] || role;
        }
    </script>
@endpush
