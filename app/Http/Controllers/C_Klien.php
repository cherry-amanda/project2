<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\CashDeadlineReminderMail;

class C_Klien extends Controller
{
    public function index()
    {
        $pengguna = Auth::user();

        // Ambil booking aktif berdasarkan pengguna
        $booking = Booking::where('pengguna_id', $pengguna->id)->latest()->first();

        // Ambil event yang sudah dipublish
        $event = $booking
            ? Event::where('booking_id', $booking->id)
                ->where('is_published', 1)
                ->with(['details.activity', 'details.staff'])
                ->first()
            : null;

        // Hitung countdown ke Hari-H
        $tanggalEvent = $event ? Carbon::parse($event->booking->tanggal) : null;
        $now = Carbon::now();

        $countdown = $tanggalEvent && $now->lt($tanggalEvent)
            ? [
                'days' => $now->diffInDays($tanggalEvent),
                'hours' => $now->diff($tanggalEvent)->h,
                'minutes' => $now->diff($tanggalEvent)->i,
                'seconds' => $now->diff($tanggalEvent)->s,
                'tanggal_formatted' => $tanggalEvent->translatedFormat('l, d F Y')
            ]
            : null;

        $event_datetime = $tanggalEvent?->toIso8601String();

        // Ambil pembayaran cash yang masih menunggu verifikasi
        $cashPayment = $booking
            ? Payment::where('booking_id', $booking->id)
                ->where('metode', 'cash')
                ->where('status', 'menunggu_verifikasi_admin')
                ->latest()
                ->first()
            : null;

        // Kirim notifikasi email jika waktu cash tinggal 6 jam lagi
        if ($cashPayment) {
            $deadline = Carbon::parse($cashPayment->created_at)->addDay();
            $selisihJam = $now->diffInHours($deadline, false); // bisa negatif

            if ($selisihJam === 6) {
                // Hindari pengiriman berulang: misalnya dengan flag, redis, atau pengecekan lainnya
                Mail::to($pengguna->email)->send(new CashDeadlineReminderMail($booking));
            }
        }

        return view('klien.v_dashboard-klien', compact(
            'pengguna',
            'event',
            'countdown',
            'event_datetime',
            'cashPayment'
        ));
    }
}
