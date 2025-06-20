@extends('layout.v_template')
@section('content')
<h1>Grafik Keuangan</h1>
<canvas id="keuanganChart" height="100"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = @json($data->groupBy('jenis'));
    const labels = [...new Set(chartData['pemasukan']?.map(i => i.bulan) ?? [])];
    const pemasukan = chartData['pemasukan']?.map(i => i.total) ?? [];
    const pengeluaran = chartData['pengeluaran']?.map(i => i.total) ?? [];
    const ctx = document.getElementById('keuanganChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    backgroundColor: 'green',
                    data: pemasukan
                },
                {
                    label: 'Pengeluaran',
                    backgroundColor: 'red',
                    data: pengeluaran
                }
            ]
        }
    });
</script>
@endsection