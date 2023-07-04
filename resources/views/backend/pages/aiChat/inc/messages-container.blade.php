@if (!is_null($conversation))
    <!-- chat header top start -->
    <div class="tt-chat-header p-3 border-bottom d-flex align-items-center justify-content-between">
        <div class="col-auto d-flex align-items-center">
            <div class="avatar avatar-md">
                <img class="rounded-circle" src="{{ staticAsset($conversation->category->avatar) }}" alt="avatar" />
            </div>
            <div class="ms-2 lh-1">
                <h6 class="mb-0 lh-1">{{ $conversation->category->name }}</h6>
                <span class="text-muted fst-italic fs-sm text-capitalize">{{ $conversation->category->role }}</span>
            </div>
        </div>
        <div class="tt-chat-action">
            <div class="dropdown tt-tb-dropdown">
                <button type="button" class="btn p-0" data-bs-toggle="dropdown" aria-expanded="false">
                    <i data-feather="more-vertical"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow" style="">

                    <a href="#" class="dropdown-item" onclick="confirmDelete(this)"
                        data-href="{{ route('chat.delete', $conversation->id) }}" title="{{ localize('Delete') }}">
                        <i data-feather="trash-2" class="me-2"></i>
                        {{ localize('Delete') }}
                    </a>
                </div>
            </div>


        </div>
    </div>
    <!-- chat header top end -->

    <!-- chat conversation start -->
    <div class="tt-conversation p-3 tt-custom-scrollbar">
        @php
            $messages = $conversation->messages;
        @endphp

        <div class="messages-wrapper">
            @foreach ($messages as $message)
                <!-- single chat expert start -->
                <div
                    class="d-flex {{ $message->prompt != null ? 'tt-message-end justify-content-end' : 'justify-content-start' }} mb-4 tt-message-wrap {{ $message->prompt != null ? 'tt-message-me' : '' }}">
                    <div
                        class="d-flex flex-column {{ $message->prompt != null ? 'align-items-end' : 'align-items-start' }}">
                        <div class="d-flex align-items-start">
                            @if ($message->prompt == null)
                                <div class="avatar avatar-md flex-shrink-0">
                                    <img class="rounded-circle" src="{{ staticAsset($conversation->category->avatar) }}"
                                        alt="avatar" />
                                </div>
                            @endif

                            <div
                                class="p-3  {{ $message->prompt != null ? 'me-3' : 'ms-3' }}  rounded-3 {{ $message->prompt != null ? '' : 'text-start' }} mw-450 tt-message-text">
                                {!! $message->result !!}
                            </div>

                            @if ($message->prompt != null)
                                <div class="avatar avatar-md flex-shrink-0">
                                    <img class="rounded-circle" src="{{ uploadedAsset($conversation->user->avatar) }}"
                                        alt="avatar" />
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                <!-- single chat expert end -->
            @endforeach
        </div>

        <!-- single chat expert start -->
        <div class="d-flex justify-content-start mb-4 tt-message-wrap new-msg-loader d-none">
            <div class="d-flex flex-column align-items-start">
                <div class="d-flex align-items-start">
                    <div class="avatar avatar-md  flex-shrink-0">
                        <img class="rounded-circle" src="{{ staticAsset($conversation->category->avatar) }}"
                            alt="avatar" />
                    </div>
                    <div class="p-2 ms-3  rounded-3 text-start mw-450 tt-message-text">
                        <!-- text preloader start -->
                        <div class="tt-text-preloader">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <!-- text preloader end -->
                    </div>
                </div>

            </div>
        </div>
        <!-- single chat expert end -->

    </div>

    <div class="mt-auto text-center py-3">
        <form class="d-flex align-items-center justify-content-end px-3" id="chat_form">
            <input class="form-control rounded-pill" name="prompt" id="prompt" type="text"
                placeholder="{{ localize('Type your message') }}" required>
            <button class="btn btn-primary rounded-pill ms-2 tt-send-btn msg-send-btn" type="submit">
                <i data-feather="send"></i>
            </button>
        </form>
    </div>
    <!-- chat right box end -->
@else
    <div class="d-flex h-100 align-items-center justify-content-center">
        {{ localize('Open a new conversation to chat with Ai') }}
    </div>
@endif
