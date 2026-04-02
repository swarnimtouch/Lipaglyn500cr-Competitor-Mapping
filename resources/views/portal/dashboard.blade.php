@extends('layouts.masterMr')

@section('title', 'Dashboard – MR Portal')
@section('page_title', 'Dashboard')

@section('content')

    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap:24px; margin-bottom:32px;">

        {{-- Doctor Count Card --}}
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user-md"></i></div>
            <div class="stat-label">My Doctors</div>
            <div class="stat-value">{{ $doctorCount }}</div>
        </div>

        {{-- Placeholder cards (extend later) --}}
        <div class="stat-card" style="--accent: #b3569f;">
            <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-label">Active Doctors</div>
            <div class="stat-value">{{ $doctorCount }}</div>
        </div>

    </div>

    {{-- Quick Links --}}
    <div class="card">
        <div class="section-header">
            <h3>Quick Actions</h3>
        </div>
        <div class="card-body" style="display:flex; gap:16px; flex-wrap:wrap;">
            <a href="{{ route('portal.doctors.index') }}" class="btn btn-outline">
                <i class="fas fa-user-md"></i> View Doctors
            </a>
            <a href="{{ route('portal.doctors.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Add Doctor
            </a>
        </div>
    </div>

@endsection