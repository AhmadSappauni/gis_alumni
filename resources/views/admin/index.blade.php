@extends('admin.layout')

@section('content')
    <header class="top-header glass-panel">
        <h1>Data Pekerjaan Alumni</h1>
        <a href="{{ route('admin.alumni.create') }}" class="btn-tambah">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"></path></svg>
            Alumni Baru
        </a>
    </header>

    @include('admin.komponen.content')
@endsection