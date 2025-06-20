<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventDetail;
use App\Models\Activity;
use App\Models\Staff;
use App\Models\Booking;
use Illuminate\Http\Request;

class c_event extends Controller
{
    public function index()
    {
        $events = Event::with(['booking.package', 'details.activity', 'details.staff'])->get();
        return view('admin.event.v_kelolakegiatan', compact('events'));
    }

    public function create()
    {
        $bookings = Booking::with('package')->get();
        $activities = Activity::all();
        $staffs = Staff::all();

        return view('admin.event.v_create', compact('bookings', 'activities', 'staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'location' => 'required|string|max:255',
            'activity_id' => 'required|array|min:1',
            'staff_id' => 'required|array|min:1',
            'time' => 'required|array|min:1',
        ]);

        $event = Event::create([
            'booking_id' => $request->booking_id,
            'location' => $request->location,
            'is_published' => $request->has('is_published')
        ]);

        foreach ($request->activity_id as $key => $activityId) {
            EventDetail::create([
                'event_id' => $event->id,
                'activity_id' => $activityId,
                'staff_id' => $request->staff_id[$key],
                'time' => $request->time[$key],
            ]);
        }

        return redirect()->route('admin.event.index')->with('success', 'Kegiatan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $event = Event::with('details')->findOrFail($id);
        $bookings = Booking::with('package')->get();
        $activities = Activity::all();
        $staffs = Staff::all();

        return view('admin.event.v_edit', compact('event', 'bookings', 'activities', 'staffs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'location' => 'required|string|max:255',
            'activity_id' => 'required|array|min:1',
            'staff_id' => 'required|array|min:1',
            'time' => 'required|array|min:1',
        ]);

        $event = Event::findOrFail($id);

        $event->update([
            'booking_id' => $request->booking_id,
            'location' => $request->location,
            'is_published' => $request->has('is_published')
        ]);

        EventDetail::where('event_id', $id)->delete();

        foreach ($request->activity_id as $key => $activityId) {
            EventDetail::create([
                'event_id' => $event->id,
                'activity_id' => $activityId,
                'staff_id' => $request->staff_id[$key],
                'time' => $request->time[$key],
            ]);
        }

        return redirect()->route('admin.event.index')->with('success', 'Kegiatan berhasil diperbarui');
    }

    public function show($id)
    {
        $event = Event::with(['booking.package', 'details.activity', 'details.staff'])->findOrFail($id);
        return view('admin.event.v_detail', compact('event'));
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->details()->delete();
        $event->delete();

        return redirect()->route('admin.event.index')->with('success', 'Kegiatan berhasil dihapus');
    }
    public function togglePublish($id)
    {
        $event = Event::findOrFail($id);
        $event->is_published = !$event->is_published;
        $event->save();

        return response()->json(['success' => true]);
    }

}
