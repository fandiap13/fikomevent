<?php

namespace App\Http\Controllers;

use App\Models\EventRegistrations;
use App\Models\Events;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $events = Events::limit(8)->get();
        return view("home", [
            'title' => "Beranda",
            'data' => $events,
        ]);
    }

    public function detailevent($id)
    {
        $event = Events::findOrFail($id);
        if ($event->gambar == '') {
            $event['gambar'] = 'assets/img/no-image.png';
        } else {
            $event['gambar'] = Storage::exists('public/' . $event->gambar)
                ? 'storage/' . $event->gambar
                : 'assets/img/no-image.png';
        }

        $event['mulai'] = date("d F Y", strtotime($event->waktu_mulai));
        $event['selesai'] = date("d F Y", strtotime($event->waktu_selesai));
        $event['tanggal_posting'] = Carbon::parse($event->created_at)->format('d F Y');
        $event['biaya'] = $event->biaya ? "Rp. " . number_format($event->biaya, 0, ",", ".") : "-";
        $event['kuota'] = $event->kuota > 0 ? $event->kuota . " orang" : "-";
        $event['jenis_event'] = $event->jenis_event == 'gratis' ? "<span class='badge badge-success'>GRATIS</span>" : "<span class='badge badge-info'>BERBAYAR</span>";

        // Cek apakah event sudah selesai
        $currentDateTime = Carbon::now();
        if (($event->waktu_selesai >= $currentDateTime) &&
            (intval($event->kuota) > count($event->pendaftar)) &&
            ($event->status_event == 'aktif')
        ) {
            $event['status_waktu_aktif'] = true;
        } else {
            $event['status_waktu_aktif'] = false;
        }
        $event['jml_daftar'] = count($event->pendaftar);

        // cek apakah sudah mendaftar event
        $event_register = EventRegistrations::where('event_id', $event->id)
            ->where('user_id', Auth::user()->id)
            ->first();
        if ($event_register) {
            $event['status_daftar'] = true;
        } else {
            $event['status_daftar'] = false;
        }

        return response()->json([
            'status' => true,
            'data' => $event,
        ], 200);
    }

    public function pendaftaran($id)
    {
        $event = Events::findOrFail($id);
        // cek apakah sudah mendaftar event
        $event_register = EventRegistrations::where('event_id', $event->id)
            ->where('user_id', Auth::user()->id)
            ->first();
        if ($event_register) {
            return redirect(url('detail-pendaftaran-event/' . $id))->with('status', 'warning#Anda sudah mendaftar di event ini!');
        }

        // cek apakah event masih berlaku
        $currentDateTime = Carbon::now();
        if (($event->waktu_selesai >= $currentDateTime) && (intval($event->kuota) > count($event->pendaftar)) && ($event->status_event == 'aktif')) {
            // $save_event_register = EventRegistrations::create([]);
            return view('pendaftaran', ['title' => 'Pendaftaran Event ' . $event->judul, 'event' => $event, 'user' => Auth::user()]);
        } else {
            return redirect(url('/'))->with('status', 'error#Event sudah ditutup!');
        }
    }
}
