<button class="btn btn-block w-100 mb-4 {{ $package->is_featured == 1 ? 'btn-primary' : 'btn-outline-primary' }}"
    data-package-id="{{ $package->id }}" data-price="{{ $package->price }}"
    @if ($disabled) disabled
@else
onclick="handlePackagePayment(this)" @endif>
    {{ localize($name) }}
</button>
