@foreach ($chatExperts as $expert)
    <li class="text-center mb-1">
        <a href="javascript:void(0);"
            @if (request()->expert != null) class="{{ request()->expert == $expert->id ? 'active' : '' }}" 
            @else
            class="{{ $loop->iteration == 1 ? 'active' : '' }}" @endif
            data-category_id="{{ $expert->id }}" onclick="getConversations(this, {{ $expert->id }})">
            <div class="avatar avatar-md" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="{{ $expert->name }} {{ $expert->role == 'default' ? '' : ' / ' . $expert->role }}">
                <img class="rounded-circle" src="{{ staticAsset($expert->avatar) }}" alt="" />
            </div>
        </a>
    </li>
@endforeach
