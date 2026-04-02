@extends('layouts.masterMr')

@section('title', 'Dashboard – MR Portal')
@section('page_title', 'Dashboard')

@section('content')

    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap:24px; margin-bottom:32px;">

        {{-- Doctor Count Card --}}
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-md"></i></div>
            <div class="stat-label">Doctors</div>
            <div class="stat-value">{{ $doctorCount }}</div>
        </div>

    </div>

    

@endsection