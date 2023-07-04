<div class="tt-single-template d-flex flex-column h-100 position-relative">
    <div class="card flex-column h-100 tt-template-card tt-corner-shape border-0">

        <a href="{{ route('custom.templates.show', $template->code) }}" class="card-body d-flex flex-column h-100">
            <div class="tt-card-info mb-4">
                <div class="tt-template-icon mb-3">
                    {!! $template->icon !!}
                </div>
                <h3 class="h6">{{ $template->name }}</h3>
                <p class="mb-0">{{ $template->description }}
                </p>
            </div>
            <div class="mt-auto">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="fs-md text-muted d-block">
                        @auth
                            @if (auth()->user()->user_type != 'customer')
                                {{ formatWords($template->total_words_generated) }}
                            @else
                                {{ formatWords($template->templateUsage()->sum('total_used_words')) }}
                            @endif
                            {{ localize('Words Generated') }}
                        @endauth
                        @guest
                            {{ formatWords($template->total_words_generated) }}
                            {{ localize('Words Generated') }}
                        @endguest
                    </span>
                </div>
            </div>
        </a>

        <!-- custom template edit and delete icon -->
        <div class="tt-template-edit position-absolute d-flex align-items-center">
            @if (auth()->user()->user_type != 'customer')
                <a href="{{ route('custom.templates.edit', $template->id) }}" class="p-1 tt-edit"
                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ localize('Edit') }}">
                    <i data-feather="edit-3" class="icon-14"></i>
                </a>
                <a href="javascript::void(0);" data-href="{{ route('custom.templates.delete', $template->id) }}"
                    onclick="confirmDelete(this)" class="p-1 tt-delete" data-bs-toggle="tooltip" data-bs-placement="top"
                    data-bs-title="{{ localize('Delete') }}">
                    <i data-feather="trash" class="icon-14"></i>
                </a>
            @else
                @if ($template->user_id == auth()->user()->id)
                    <a href="{{ route('custom.templates.edit', $template->id) }}" class="p-1 tt-edit"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="{{ localize('Edit') }}">
                        <i data-feather="edit-3" class="icon-14"></i>
                    </a>

                    <a href="javascript::void(0);" data-href="{{ route('custom.templates.delete', $template->id) }}"
                        onclick="confirmDelete(this)" class="p-1 tt-delete" data-bs-toggle="tooltip"
                        data-bs-placement="top" data-bs-title="{{ localize('Delete') }}">
                        <i data-feather="trash" class="icon-14"></i>
                    </a>
                @else
                    <span class="badge bg-soft-warning">
                        {{ localize('Created by admin') }}
                    </span>
                @endif
            @endif
        </div>
    </div>
</div>
