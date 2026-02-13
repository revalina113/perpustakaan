@extends('layouts.admin')

@section('title', 'Grafik Kunjungan - PERPUSTAKAAN')

@section('content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Grafik Kunjungan</h2>
            <p class="text-gray-600 mt-1">Jumlah pengunjung per tanggal</p>
        </div>
        <div class="text-4xl">📈</div>
    </div>
</div>

<!-- statistics cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Hari Ini</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $totalToday ?? 0 }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-7H3v7a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Bulan Ini</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $totalMonth ?? 0 }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
        <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18"></path>
                </svg>
            </div>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-500">Total Periode</p>
            <p class="text-4xl font-extrabold text-gray-900">{{ $data->sum('total') }}</p>
        </div>
    </div>
</div>

<!-- month filter -->
<div class="bg-white rounded-xl shadow-md p-4 mb-6">
    <form method="GET" action="{{ route('admin.pengunjung.grafik') }}" class="flex flex-wrap items-end gap-4">
        <div class="flex flex-col">
            <label class="text-sm font-medium text-gray-700">Bulan</label>
            <input type="month" name="bulan" value="{{ $month ?? now()->format('Y-m') }}" class="mt-1 block rounded-md border-gray-300 shadow-sm" />
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tampilkan</button>
    </form>
</div>

@if($data->count())
<div class="bg-white rounded-xl shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Jumlah Pengunjung per Hari<br><span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($month)->format('F Y') }}</span></h3>
    <canvas id="pengunjungChart"
            style="height:300px;"
            data-month="{{ $month ?? now()->format('Y-m') }}"
            data-labels='{{ json_encode($labels) }}'
            data-totals='{{ json_encode($totals) }}'>
    </canvas>
</div>
@else
<div class="bg-white rounded-xl shadow-md p-6 text-center">
    <p class="text-gray-600">Belum ada data kunjungan pada periode ini.</p>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('pengunjungChart');
        const ctx = canvas.getContext('2d');
        const month = canvas.dataset.month; // format YYYY-MM
        const rawLabels = JSON.parse(canvas.dataset.labels);
        const rawTotals = JSON.parse(canvas.dataset.totals);

        // build map of existing totals
        const totalsMap = {};
        rawLabels.forEach((lab, i) => { totalsMap[lab] = rawTotals[i]; });

        // generate full date list for month
        const [y, m] = month.split('-').map(Number);
        const daysInMonth = new Date(y, m, 0).getDate();
        const fullLabels = [];
        const fullTotals = [];
        for (let d = 1; d <= daysInMonth; d++) {
            const day = String(d).padStart(2,'0');
            const date = `${y}-${String(m).padStart(2,'0')}-${day}`;
            fullLabels.push(date);
            fullTotals.push(totalsMap[date] || 0);
        }

        const data = {
            labels: fullLabels,
            datasets: [{
                label: 'Jumlah Pengunjung',
                data: fullTotals,
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 3,
            }]
        };

        new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    });
</script>
@endpush
