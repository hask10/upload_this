@extends('backend.layouts.master')


@section('title')
    {{ localize('Subscription Histories') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection


@section('contents')
    <section class="tt-section py-4">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="tt-page-header">
                        <div class="d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title mb-3 mb-lg-0">
                                <h1 class="h4 mb-lg-1">{{ localize('Subscription Histories') }}</h1>
                                <ol class="breadcrumb breadcrumb-angle text-muted">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('writebot.dashboard') }}">{{ localize('Dashboard') }}</a>
                                    </li>
                                    <li class="breadcrumb-item">{{ localize('Subscription Histories') }}</li>
                                </ol>
                            </div>
                            <div class="tt-action">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card mb-4" id="section-1">
                        <form class="app-search" action="{{ Request::fullUrl() }}" method="GET">
                            <div class="card-header border-bottom-0">
                                <div class="row justify-content-between g-3">
                                    <div class="col-auto flex-grow-1">
                                        <div class="tt-search-box">
                                            <div class="input-group">
                                                <span class="position-absolute top-50 start-0 translate-middle-y ms-2">
                                                    <i data-feather="search"></i></span>
                                                <input class="form-control rounded-start w-100" type="text"
                                                    id="search" name="search" placeholder="{{ localize('Search') }}..."
                                                    @isset($searchKey)
                                                value="{{ $searchKey }}"
                                            @endisset>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary">
                                            <i data-feather="search" width="18"></i>
                                            {{ localize('Search') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>


                        <table class="table tt-footable border-top align-middle" data-use-parent-width="true">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ localize('S/L') }}</th>
                                    @if (auth()->user()->user_type != 'customer')
                                        <th>{{ localize('User') }}</th>
                                    @endif
                                    <th>{{ localize('Package') }}</th>
                                    <th data-breakpoints="xs sm">{{ localize('Price') }}</th>
                                    <th data-breakpoints="xs sm">{{ localize('Start Date') }}</th>
                                    <th data-breakpoints="xs sm">{{ localize('Expire Date') }}</th>
                                    <th data-breakpoints="xs sm">{{ localize('Payment Method') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($histories as $key => $history)
                                    <tr>
                                        <td class="text-center fs-sm">
                                            {{ $key + 1 + ($histories->currentPage() - 1) * $histories->perPage() }}
                                        </td>

                                        @if (auth()->user()->user_type != 'customer')
                                            <td>
                                                <a href="javascript:void(0);" class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm">
                                                        <img class="rounded-circle"
                                                            src="{{ uploadedAsset($history->user->avatar) }}"
                                                            alt=""
                                                            onerror="this.onerror=null;this.src='{{ staticAsset('backend/assets/img/placeholder-thumb.png') }}';" />
                                                    </div>
                                                    <h6 class="fs-sm mb-0 ms-2">{{ $history->user->name }}
                                                    </h6>
                                                </a>
                                            </td>
                                        @endif


                                        <td class="text-capitalize fw-sm">
                                            {{ $history->subscriptionPackage->title }}/{{ $history->subscriptionPackage->package_type == 'starter' ? localize('Monthly') : $history->subscriptionPackage->package_type }}
                                        </td>

                                        <td class="text-capitalize fw-sm">
                                            {{ $history->subscriptionPackage->price > 0 ? formatPrice($history->subscriptionPackage->price) : localize('Free') }}
                                        </td>

                                        <td>
                                            <span
                                                class="fs-sm">{{ date('d M, Y', strtotime($history->created_at)) }}</span>
                                        </td>

                                        <td>
                                            @php
                                                // check validity
                                                $days = 30;
                                                if ($history->subscriptionPackage->package_type == 'yearly') {
                                                    $days = 365; // 1 year
                                                }
                                                
                                                if ($history->subscriptionPackage->package_type == 'lifetime') {
                                                    $days = 365 * 100; // 100 years
                                                }
                                            @endphp
                                            <span
                                                class="fs-sm text-capitalize">{{ date('d M, Y', strtotime($history->created_at->addDays($days))) }}
                                        </td>

                                        <td>

                                            <span class="badge bg-soft-primary rounded-pill text-capitalize">
                                                {{ $history->payment_method }}
                                            </span>
                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>


                        <!--pagination start-->
                        <div class="d-flex align-items-center justify-content-between px-4 pb-4">
                            <span>{{ localize('Showing') }}
                                {{ $histories->firstItem() ?? 0 }}-{{ $histories->lastItem() ?? 0 }}
                                {{ localize('of') }}
                                {{ $histories->total() }} {{ localize('results') }}</span>
                            <nav>
                                {{ $histories->appends(request()->input())->links() }}
                            </nav>
                        </div>
                        <!--pagination end-->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
