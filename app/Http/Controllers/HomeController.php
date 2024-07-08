<?php

namespace App\Http\Controllers;

use App\Models\EventRegistrations;
use App\Models\Events;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $events = Events::limit(8)->orderBy('created_at', 'desc')->get();
        return view("home", [
            'title' => "Beranda",
            'data' => $events,
        ]);
    }

    public function tentang_kami()
    {
        return view("tentang_kami", [
            'title' => "Tentang Kami",
        ]);
    }

    public function events(Request $request)
    {
        $search = $request->get('search');
        $query = Events::query();
        if ($search) {
            $query->where('judul', 'like', '%' . $search . '%');
        }
        $data = $query->orderBy('created_at', 'desc')->paginate(10)->appends(['search' => $search]);

        return view("daftar_event", [
            'title' => "Daftar Event",
            'data' => $data,
            'search' => $search,
        ]);
    }

    public function detailevent($id)
    {
        $event = Events::findOrFail($id);
        if ($event->gambar == '' || $event->gambar == null) {
            $event['gambar'] = asset('assets/img/no-image.png');
        } else {
            $event['gambar'] = Storage::exists('public/' . $event->gambar)
                ? asset('storage/' . $event->gambar)
                : asset('assets/img/no-image.png');
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
            ($event->status_event == 'aktif')
        ) {
            if ($event->kuota == null || $event->kuota == "" || $event->kuota == 0) {
                $event['status_waktu_aktif'] = true;
            } else {
                if ((intval($event->kuota) > count($event->pendaftar))) {
                    $event['status_waktu_aktif'] = true;
                } else {
                    $event['status_waktu_aktif'] = false;
                }
            }
        } else {
            $event['status_waktu_aktif'] = false;
        }
        $event['jml_daftar'] = count($event->pendaftar);

        // cek apakah sudah mendaftar event
        if (Auth::user()) {
            $event_register = EventRegistrations::where('event_id', $event->id)
                ->where('user_id', Auth::user()->id)
                ->first();
            if ($event_register) {
                $event['status_daftar'] = true;
                $event['id_pendaftaran'] = $event_register->id;
            } else {
                $event['status_daftar'] = false;
                $event['id_pendaftaran'] = null;
            }
        } else {
            $event['status_daftar'] = false;
            $event['id_pendaftaran'] = null;
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
        if (($event->waktu_selesai >= $currentDateTime) &&
            ($event->status_event == 'aktif')
        ) {
            if ($event->kuota == null || $event->kuota == "" || $event->kuota == 0) {
                return view('pendaftaran', ['title' => 'Pendaftaran Event ' . $event->judul, 'event' => $event, 'user' => Auth::user()]);
            } else {
                if ((intval($event->kuota) > count($event->pendaftar))) {
                    return view('pendaftaran', ['title' => 'Pendaftaran Event ' . $event->judul, 'event' => $event, 'user' => Auth::user()]);
                } else {
                    return redirect(url('/'))->with('status', 'error#Event sudah ditutup!');
                }
            }
        } else {
            return redirect(url('/'))->with('status', 'error#Event sudah ditutup!');
        }
    }

    public function simpan_pendaftaran(Request $request)
    {
        $id = $request->event_id;
        $event = Events::findOrFail($id);
        $event_register = EventRegistrations::where('event_id', $event->id)
            ->where('user_id', Auth::user()->id)
            ->first();
        if ($event_register) {
            return redirect(url('detail-pendaftaran-event/' . $id))->with('status', 'warning#Anda sudah mendaftar di event ini!');
        }

        $currentDateTime = Carbon::now();
        if (($event->waktu_selesai >= $currentDateTime) &&
            ($event->status_event == 'aktif')
        ) {
            if ($event->kuota == null || $event->kuota == "" || $event->kuota == 0) {
            } else {
                if ((intval($event->kuota) > count($event->pendaftar))) {
                } else {
                    return redirect(url('/'))->with('status', 'error#Event sudah ditutup!');
                }
            }
        } else {
            return redirect(url('/'))->with('status', 'error#Event sudah ditutup!');
        }

        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'nama' => 'required|string|max:255',
            'telp' => 'required|string|max:50',
            'instansi' => 'required|string|max:255',
            'status' => 'required|in:mahasiswa,dosen,umum',
            'bukti_pembayaran' => 'required_if:event.jenis_event,berbayar|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan data pendaftaran
        $eventRegistration = EventRegistrations::create([
            'event_id' => $request->event_id,
            'user_id' => Auth::user()->id,
            'nama' => $request->nama,
            'telp' => $request->telp,
            'instansi' => $request->instansi,
            'status' => $request->status,
            'acc' => 0,
        ]);

        // Jika event berbayar, simpan bukti pembayaran
        if ($request->hasFile('bukti_pembayaran')) {
            $foto = $request->file('bukti_pembayaran');
            $filename = $eventRegistration->id . "." . $foto->getClientOriginalExtension();
            $foto->storeAs('public/media/event_registrations', $filename);
            $bukti_pembayaran = 'media/event_registrations/' . $filename;
            $eventRegistration->bukti_pembayaran = $bukti_pembayaran;
            $eventRegistration->save();
        }

        return redirect(url("/detail-pendaftaran-event/" . $eventRegistration->id))->with('success', 'Pendaftaran berhasil disimpan.');
    }

    public function my_events()
    {
        $events = EventRegistrations::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('my_events', [
            'title' => "Event Yang Diikuti",
            'data' => $events,
        ]);
    }

    public function detail_pendaftaran_event($id)
    {
        $event = EventRegistrations::where('user_id', Auth::user()->id)
            ->where('id', $id)
            ->firstOrFail();
        return view('detail_pendaftaran', [
            'title' => "Detail Pendaftaran Event",
            'event' => $event,
        ]);
    }
}
