@foreach ($images as $image)
    <div class="col-6 col-md-4 col-xl-2">
        <div class="tt-result-content-wrap">
            <div class="tt-generate-img-wrap position-relative mb-2">
                <div class="tt-image-gallery-magnify">
                    <a href="{{ staticAsset($image->content) }}" title="{{ $image->title }}" size="256x256"
                        class="mb-2 tt-generate-img position-relative rounded-3">
                        <img src="{{ staticAsset($image->content) }}" class="img-fluid rounded-3">
                    </a>
                </div>
                <div class="tt-overly-icon position-absolute">
                    <a href="{{ staticAsset($image->content) }}" download="{{ $image->title }}">
                        <span><i data-feather="download" class="icon-14"></i></span>
                    </a>

                    <a href="#" data-href="{{ route('images.delete', $image->id) }}"
                        onclick="confirmDelete(this)">
                        <span><i data-feather="trash" class="icon-14"></i></span>
                    </a>

                </div>
            </div>
            <p class="tt-line-clamp tt-clamp-1 mb-1">{{ $image->title }}</p>
            <p class="text-muted fs-md">Size: {{ $image->resolution }}</p>
        </div>
    </div>
@endforeach



<!--pagination start-->
<div class="d-flex align-items-center justify-content-between p-4 mt-auto">
    <span>{{ localize('Showing') }}
        {{ $images->firstItem() ?? 0 }}-{{ $images->lastItem() ?? 0 }}
        {{ localize('of') }}
        {{ $images->total() }} {{ localize('results') }}</span>
    <nav>
        {{ $images->appends(request()->input())->links() }}
    </nav>
</div>
<!--pagination end-->
