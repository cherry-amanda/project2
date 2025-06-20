@extends('layout.v_template4')
@extends('layout.v_nav4')


@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Dashboard Klien</h2>

    <div id="dashboard-content">
        <div class="text-center my-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Memuat...</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function loadDashboardData() {
        fetch("{{ route('klien.dashboard.data') }}")
            .then(res => res.text())
            .then(html => {
                document.getElementById('dashboard-content').innerHTML = html;
            })
            .catch(() => {
                document.getElementById('dashboard-content').innerHTML = '<div class="alert alert-danger">Gagal memuat data.</div>';
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        loadDashboardData();
        setInterval(loadDashboardData, 15000); // refresh tiap 15 detik
    });
</script>
@endpush
