<div class="d-flex justify-content-between w-100 mt-auto mb-2">
    <span class="fs-sm"><strong>{{ getUsedImagesPercentage() }}%
            {{ localize('Used') }}.</strong>
        {{ localize('Remaining Images') }}:
        <strong>{{ auth()->user()->this_month_available_images }}/{{ auth()->user()->this_month_available_images + auth()->user()->this_month_used_images }}</strong></span>
</div>

<div class="progress mb-1 w-100" style="height: 6px;">
    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ getUsedImagesPercentage() }}%"
        aria-valuenow="{{ getUsedImagesPercentage() }}" aria-valuemin="0" aria-valuemax="100"></div>
</div>
