@php use Illuminate\Support\Str; @endphp
@extends('layout.v_template')

@section('content')

@php
function getNamaPasanganFromTanggal($tanggal, $bookings) {
    foreach ($bookings as $b) {
        if (\Carbon\Carbon::parse($b->tanggal)->format('Y-m-d') === \Carbon\Carbon::parse($tanggal)->format('Y-m-d')) {
            return $b->nama_pasangan;
        }
    }
    return null;
}
@endphp

<style>
    #calendar { max-width: 900px; margin: 20px auto; font-size: 14px; box-shadow: 0 2px 8px rgb(0 0 0 / 0.1); border-radius: 8px; background: white; padding: 15px; }
    .calendar-wrapper { max-width: 900px; margin: 0 auto 40px auto; display: flex; flex-direction: column; align-items: center; }
    .legend { margin-top: 20px; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap; font-size: 14px; color: #444; }
    .legend-item { display: flex; align-items: center; gap: 8px; padding: 6px 14px; border-radius: 5px; background: #f9f9f9; box-shadow: 0 0 5px rgb(0 0 0 / 0.1); cursor: pointer; }
    .legend-item:hover { background-color: #e0e0e0; }
    .legend-item.active { background-color: #ddd; font-weight: 600; }
    .legend-color { width: 22px; height: 22px; border-radius: 6px; flex-shrink: 0; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
    #statusModal { display: none; position: fixed; top: 25%; left: 50%; transform: translateX(-50%); background: #fff; padding: 20px; border: 1px solid #ccc; box-shadow: 0 0 10px rgb(0 0 0 / 0.2); z-index: 1000; border-radius: 8px; width: 280px; }
</style>

<h2 style="text-align:center;">Kelola Tanggal Booking</h2>

<div class="calendar-wrapper">
    <div id="calendar"></div>
    <div class="legend">
        @php $statusColors = [
            'all' => ['#ccc', 'All'],
            'booked' => ['rgba(220, 53, 69, 0.4)', 'Booked'],
            'pending' => ['rgba(23, 162, 184, 0.4)', 'Pending'],
            'cancelled' => ['rgba(108, 117, 125, 0.4)', 'Cancelled'],
            'holiday' => ['rgba(255, 193, 7, 0.4)', 'Holiday']
        ]; @endphp
        @foreach($statusColors as $key => [$color, $label])
        <div class="legend-item {{ $key === 'all' ? 'active' : '' }}" data-status="{{ $key }}">
            <div class="legend-color" style="background-color: {{ $key === 'all' ? 'transparent' : $color }}; border: 1px solid #ccc;"></div> {{ $label }}
        </div>
        @endforeach
    </div>
</div>

<div id="statusModal">
    <h4>Ubah Status Tanggal</h4>
    <form id="statusForm">
        <input type="hidden" id="selectedDate" name="tanggal">
        <label for="status">Status:</label>
        <select name="status" id="status" style="margin-left:10px;">
            <option value="available">Tersedia</option>
            <option value="pending">Pending</option>
            <option value="booked">Booked</option>
            <option value="cancelled">Cancelled</option>
            <option value="holiday">Holiday</option>
        </select>
        <br><br>
        <button type="submit" style="margin-right:10px;">Simpan</button>
        <button type="button" onclick="document.getElementById('statusModal').style.display='none'">Batal</button>
    </form>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let allEvents = [
        @foreach($dates as $date)
        {
            title: '{{ ucfirst($date->status === "available" ? "Tersedia" : $date->status) }}',
            start: '{{ $date->tanggal }}',
            status: '{{ $date->status }}',
            color:
                @switch($date->status)
                    @case('pending') 'rgba(23, 162, 184, 0.4)' @break
                    @case('booked') 'rgba(220, 53, 69, 0.4)' @break
                    @case('cancelled') 'rgba(108, 117, 125, 0.4)' @break
                    @case('holiday') 'rgba(255, 193, 7, 0.4)' @break
                    @default 'transparent'
                @endswitch,
            textColor: '{{ $date->status === "available" ? "#343a40" : "#000" }}',
            extendedProps: {
                note: `{{ $date->status === 'booked' ? (getNamaPasanganFromTanggal($date->tanggal, $bookings) ?? 'Tidak diketahui') : '-' }}`
            }
        },
        @endforeach
    ];

    let calendarEl = document.getElementById('calendar');
    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        selectable: true,
        events: allEvents,

        eventDidMount: function(info) {
            if (info.event.extendedProps.note && info.event.extendedProps.note !== '-') {
                const decodeHtml = (html) => {
                    let txt = document.createElement("textarea");
                    txt.innerHTML = html;
                    return txt.value;
                };

                let tooltip = document.createElement('div');
                tooltip.innerText = 'Catatan: ' + decodeHtml(info.event.extendedProps.note);
                tooltip.style.position = 'absolute';
                tooltip.style.background = '#333';
                tooltip.style.color = '#fff';
                tooltip.style.padding = '5px 10px';
                tooltip.style.borderRadius = '5px';
                tooltip.style.fontSize = '12px';
                tooltip.style.whiteSpace = 'nowrap';
                tooltip.style.zIndex = '9999';
                tooltip.style.display = 'none';
                document.body.appendChild(tooltip);

                info.el.addEventListener('mouseenter', (e) => {
                    tooltip.style.left = e.pageX + 'px';
                    tooltip.style.top = (e.pageY + 15) + 'px';
                    tooltip.style.display = 'block';
                });

                info.el.addEventListener('mousemove', (e) => {
                    tooltip.style.left = e.pageX + 'px';
                    tooltip.style.top = (e.pageY + 15) + 'px';
                });

                info.el.addEventListener('mouseleave', () => {
                    tooltip.style.display = 'none';
                });
            }
        },


        dateClick: function(info) {
            document.getElementById('selectedDate').value = info.dateStr;
            let selectedEvent = allEvents.find(e => e.start === info.dateStr);
            let status = selectedEvent ? selectedEvent.status : 'available';
            document.getElementById('status').value = status;
            document.getElementById('statusModal').style.display = 'block';
        }
    });

    calendar.render();

    document.querySelectorAll('.legend-item').forEach(item => {
        item.addEventListener('click', function() {
            let status = this.getAttribute('data-status');
            document.querySelectorAll('.legend-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            filterEvents(status);
        });
    });

    document.getElementById('statusForm').addEventListener('submit', function (e) {
        e.preventDefault();
        let tanggal = document.getElementById('selectedDate').value;
        let status = document.getElementById('status').value;

        axios.post('{{ route('admin.dates.updateStatus') }}', {
            tanggal: tanggal,
            status: status,
            _token: '{{ csrf_token() }}'
        })
        .then(res => {
            alert(res.data.message);
            location.reload();
        })
        .catch(err => {
            alert('Gagal update status!');
            console.error(err);
        });

        document.getElementById('statusModal').style.display = 'none';
    });

    function filterEvents(status) {
        let filtered = (status === 'all') ? allEvents : allEvents.filter(e => e.status === status);
        calendar.removeAllEvents();
        calendar.addEventSource(filtered);
    }
});
</script>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item text-sm text-dark active" aria-current="page">Kelola Tanggal</li>
@endsection
