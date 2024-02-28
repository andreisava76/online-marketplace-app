@extends("layouts.app")
@section("content")
    <section style="background-color: #eee;">
        <div class="container py-5">
            <div class="row position-relative">
                <div class="col-md-6 col-lg-5 col-xl-4 mb-4 mb-md-0">
                    <h5 class="font-weight-bold mb-3 text-center text-lg-start">{{ __('Chatroom') }}</h5>
                    <div class="card">
                        <div class="card-body">
                            <ul class="list-unstyled mb-0 direct-chat-contacts">
                                @foreach($chat_rooms as $chat_room)
                                    <li class="p-2"
                                        data-recipient_id="{{ auth()->id() === $chat_room->sender_id ? $chat_room->recipient_id : $chat_room->sender_id}}"
                                        data-id="{{ $chat_room->id }}" id="chat-container">
                                        <a class="d-flex justify-content-between" style="text-decoration:none">
                                            <div class="d-flex flex-row">
                                                <img src="https://eu.ui-avatars.com/api/?name=
                                                    {{ Str::camel($chat_user=Auth::user()->name===\App\Models\User::query()->firstWhere('id', $chat_room->recipient_id)->name ?
                                                            \App\Models\User::query()->firstWhere('id', $chat_room->sender_id)->name :
                                                            \App\Models\User::query()->firstWhere('id', $chat_room->recipient_id)->name)}}"
                                                     alt="avatar" width="60"
                                                     class="rounded-circle d-flex align-self-center me-3 shadow-1-strong">
                                                <div class="pt-1">
                                                    <p class="fw-bold mb-0">{{$chat_user}}</p>
                                                    <p class="small text-muted"
                                                       id="last-message">{{ substr(($chat_room->latestMessage->message ?? '-'), 0, 13) }}</p>
                                                </div>
                                            </div>
                                            <div class="pt-1">
                                                <p class="small text-muted mb-1"
                                                   id="last-message-time">{{ isset($chat_room->latestMessage->created_at) ? $chat_room->latestMessage->diffForHumans : '-' }}</p>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-7 col-xl-8 direct-chat-messages">
                    <ul class="list-unstyled" id="chat_messages">
                        <li class='d-flex justify-content-center'>
                            <div class='card w-50'>
                                <div class='card-body mx-auto'>{{ __('Select the chatroom to send messages') }}</div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div
                    class="col-md-6 col-lg-7 col-xl-8 box-footer border rounded-1 bg-secondary-subtle position-absolute bottom-0 end-0">
                    <div class="input-group">
                        <input type="text" id="message" placeholder="Write here..." class="form-control">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-primary" data-chat-button>{{ __('Send') }}</button>
                          </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script>
        const PARAMS = {
            userId: @json(auth()->id()),
        }

        $(document).ready(() => {
            let recipientId;
            let chatroomId;
            let CHAT_BUTTON = $('[data-chat-button]');
            let CHAT_MESSAGES = $('#chat_messages');
            if (!chatroomId) {
                CHAT_BUTTON.attr("disabled", true);
            }

            listenNewMessageReceived();
            @if(isset($response_chat_room))
            let selected_chat = @JSON($response_chat_room);
            jQuery(function () {
                $('li[data-id=' + selected_chat.id + ']').trigger('click');
                if (!selected_chat.id) {
                    $('li[data-id=""]').trigger('click');
                }
            });
            @endif

            $('li#chat-container').on('click', (e) => {
                CHAT_BUTTON.attr("disabled", false);
                chatroomId = $(e.currentTarget).data('id');
                recipientId = $(e.currentTarget).data('recipient_id');


                if (chatroomId) {
                    listenMessageFromChatroom();
                }

                CHAT_MESSAGES.html('')
                if (!chatroomId) {
                    CHAT_MESSAGES.append($("<li class='d-flex justify-content-center'><div class='card w-25'><div class='card-body mx-auto'>{{ __('No message') }}</div></div></li>"));
                } else {
                    axios.get('room/' + chatroomId + '/messages', {
                        chatroomId: chatroomId,
                    })
                        .then((messages) => {
                            if (JSON.parse(messages.request.response).length === 0) {
                                CHAT_MESSAGES.append($("<li class='d-flex justify-content-center'><div class='card w-25'><div class='card-body mx-auto'>{{ __('No message') }}</div></div></li>"));
                            } else {
                                JSON.parse(messages.request.response).forEach(message => {
                                    let logged_in_user = <?= json_encode(auth()->id()); ?>;
                                    if (logged_in_user === message.sender_id) {
                                        // console.log('user');
                                        let msg_html = $("<li class='d-flex justify-content-between mb-4'><img src='https://eu.ui-avatars.com/api/?name={{Auth::user()->name}}' alt='avatar' class='rounded-circle d-flex align-self-start me-3 shadow-1-strong' width='60'><div class='card w-100'><div class='card-header d-flex justify-content-between p-3'><p class='fw-bold mb-0'>{{Auth::user()->name}} </p><p class='text-muted small mb-0'>" + message.diff_for_humans + "</p></div><div class='card-body'><p class='mb-0'>" + message.message + "</p></div></div></li>");
                                        CHAT_MESSAGES.append(msg_html);
                                    } else {
                                        // console.log('recipient');
                                        let msg_html = $("<li class='d-flex justify-content-between mb-4'><div class='card w-100'><div class='card-header d-flex justify-content-between p-3'><p class='fw-bold mb-0'>" + message.name_sender + "</p><p class='text-muted small mb-0'>" + message.diff_for_humans + "</p></div><div class='card-body'><p class='mb-0'>" + message.message + "</p></div></div><img src='https://eu.ui-avatars.com/api/?name=" + message.name_sender + "' class='rounded-circle d-flex align-self-start ms-3 shadow-1-strong' width='60'></li>");
                                        CHAT_MESSAGES.append(msg_html);
                                    }
                                });
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        })
                }
            }).on('mouseenter', (event) => {
                $(event.currentTarget).css('cursor', 'pointer');
            }).on('click', (event) => {
                $(event.currentTarget).addClass("chat-container-bg-selected").removeClass("bg-primary-subtle")
                    .siblings('#chat-container').removeClass("chat-container-bg-selected");
                $(event.currentTarget).find("span.badge").remove();
            });

            CHAT_BUTTON.on('click', () => {
                if (document.getElementById("message").value === '') {
                    return
                }
                axios
                    .post('room/message', {
                        chat_room_id: chatroomId || null,
                        message: document.getElementById("message").value,
                        recipient_id: recipientId
                    })
                    .then(({
                               data: {
                                   message: { message, diff_for_humans, sender_id, chat_room_id, recipient_id },
                                   html_recipient,
                                   html_sender
                               }
                           }) => {
                        chatroomId = chat_room_id
                        $(`li[data-recipient_id='${recipient_id}']`).data('id', chat_room_id).attr('data-id', chat_room_id)
                        $(`li[data-id='${chatroomId}']`)
                            .find('#last-message').html(message.substring(0, 13)).end()
                            .find('#last-message-time').html(diff_for_humans)
                        if (PARAMS.userId === sender_id)
                            CHAT_MESSAGES.prepend(html_sender)
                        else
                            CHAT_MESSAGES.prepend(html_recipient)
                        document.getElementById("message").value = '';
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            })

            function listenMessageFromChatroom() {
                Echo.private('chatroom.' + chatroomId)
                    .listen('.new.message', (response) => {
                        if (PARAMS.userId === response.content.sender_id)
                            CHAT_MESSAGES.prepend(response.html_sender)
                        else
                            CHAT_MESSAGES.prepend(response.html_recipient)
                    })
            }

            function listenNewMessageReceived() {
                Echo.private('rooms')
                    .listen('.new.message.received', ({
                                                          message: { message, diff_for_humans, recipient_id },
                                                          chat_room_id
                                                      }) => {
                        console.log(chat_room_id)
                        // noinspection JSJQueryEfficiency
                        $(`li[data-id='${chat_room_id}']`).prependTo('.direct-chat-contacts').end().find('#last-message').html(message.substring(0, 13))
                        // noinspection JSJQueryEfficiency
                        $(`li[data-id='${chat_room_id}']`).find('#last-message-time').html(diff_for_humans)
                        if (recipient_id === PARAMS.userId) {
                            $(`li[data-id='${chat_room_id}']`).addClass("bg-primary-subtle");
                            $(`li[data-id='${chat_room_id}'] a div #last-message-time`).after("<span class='badge bg-danger float-end'>1</span>");
                        }
                    })
            }
        })

    </script>
@endpush
