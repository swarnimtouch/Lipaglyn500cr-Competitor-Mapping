{{--
    ┌────────────────────────────────────────────────┐
    │  Breadcrumb Component                          │
    │  Usage: @include('components.breadcum')        │
    │                                                │
    │  Auto-generates from current URL segments.    │
    │  Optionally pass $breadcrumbs array:           │
    │  [['label'=>'Doctors','url'=>route(...)], ...]  │
    └────────────────────────────────────────────────┘
--}}

<style>
    .wd-breadcrumb-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
    }

    .wd-page-heading {
        font-family: 'Syne', sans-serif;
        font-size: 22px;
        font-weight: 700;
        color: #1a1d2e;
        line-height: 1.2;
    }

    .wd-breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        list-style: none;
        padding: 6px 14px;
        margin: 0;
        background: #fff;
        border: 1px solid #e4e8f0;
        border-radius: 30px;
        font-size: 12.5px;
        font-weight: 500;
    }

    .wd-breadcrumb li {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #8a8fa8;
    }

    .wd-breadcrumb li a {
        color: #8a8fa8;
        text-decoration: none;
        transition: color .2s;
    }

    .wd-breadcrumb li a:hover { color: #A11A20; }

    .wd-breadcrumb li.active {
        color: #A11A20;
        font-weight: 600;
    }

    .wd-breadcrumb .sep {
        font-size: 10px;
        color: #c8ccd8;
    }

    .wd-breadcrumb .bc-home {
        width: 22px; height: 22px;
        background: #A11A20;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: 10px;
        text-decoration: none;
        flex-shrink: 0;
    }

    .wd-breadcrumb .bc-home:hover { background: #7e1419; color: #fff; }
</style>

@php
    /*
     * If $breadcrumbs is not passed, auto-generate from URL segments.
     * Format: [['label' => 'Text', 'url' => 'https://...'], ...]
     * Last item is treated as active (no link).
     */
    if (!isset($breadcrumbs)) {
        $segments   = array_filter(request()->segments());
        $breadcrumbs = [];
        $url         = '';
        foreach ($segments as $segment) {
            $url .= '/' . $segment;
            $breadcrumbs[] = [
                'label' => ucfirst(str_replace(['-', '_'], ' ', $segment)),
                'url'   => $url,
            ];
        }
    }

    // Page title = last breadcrumb label
    $pageTitle = !empty($breadcrumbs) ? end($breadcrumbs)['label'] : 'Dashboard';
@endphp

<div class="wd-breadcrumb-bar">

    {{-- Page Heading --}}
    <h2 class="wd-page-heading">{{ $pageTitle }}</h2>

    {{-- Breadcrumb Trail --}}
    <ol class="wd-breadcrumb">
        {{-- Home --}}
        <li>
            <a href="{{ route('portal.dashboard') }}" class="bc-home" title="Home">
                <i class="fas fa-home"></i>
            </a>
        </li>

        @foreach ($breadcrumbs as $index => $crumb)
            <li class="{{ $loop->last ? 'active' : '' }}">
                <span class="sep"><i class="fas fa-chevron-right"></i></span>
                @if ($loop->last)
                    {{ $crumb['label'] }}
                @else
                    <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                @endif
            </li>
        @endforeach
    </ol>

</div>
