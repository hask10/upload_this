<div class="d-flex justify-content-between w-100 mt-auto mb-2">
    <span class="fs-sm"><strong>{{ getUsedWordsPercentage() }}%
            {{ localize('Used') }}.</strong>
        {{ localize('Remaining Words') }}:
        <strong>{{ auth()->user()->this_month_available_words < 0 ? 0 : auth()->user()->this_month_available_words }}/{{ auth()->user()->this_month_available_words + auth()->user()->this_month_used_words }}</strong></span>
</div>

<div class="progress mb-1 w-100" style="height: 6px;">
    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ getUsedWordsPercentage() }}%"
        aria-valuenow="{{ getUsedWordsPercentage() }}" aria-valuemin="0" aria-valuemax="100"></div>
</div>
