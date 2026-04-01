@extends('layouts.masterMr')

@section('title', 'Dashboard – MR Portal')
@section('page_title', 'Dashboard')

@section('content')

    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:20px; margin-bottom:32px;">

        {{-- Doctor Count Card --}}
        <div class="stat-card">
            <div class="stat-icon">👨‍⚕️</div>
            <div class="stat-label">My Doctors</div>
            <div class="stat-value">{{ $doctorCount }}</div>
        </div>

        {{-- Placeholder cards (extend later) --}}
        <div class="stat-card" style="--accent: #00c2a8;">
            <div class="stat-icon">📋</div>
            <div class="stat-label">Active Doctors</div>
            <div class="stat-value">{{ $doctorCount }}</div>
        </div>

    </div>

    {{-- Quick Links --}}
    <div class="card">
        <div class="section-header">
            <h3>Quick Actions</h3>
        </div>
        <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('portal.doctors.index') }}" class="btn btn-outline">👨‍⚕️ View Doctors</a>
            <a href="{{ route('portal.doctors.create') }}" class="btn btn-primary">+ Add Doctor</a>
        </div>
    </div>

@endsection
