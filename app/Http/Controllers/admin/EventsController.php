<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $title = "List Events";
        $query = Events::query();
        if ($search) {
            $query->where('judul', 'like', '%' . $search . '%');
        }
        $data = $query->paginate(10)->appends(['search' => $search]);

        return view('admin.events.index', compact('data', 'title', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Tambah Event";
        return view('admin.events.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255|unique:events,judul',
            'deskripsi' => 'required|string',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'jenis_event' => 'required|in:gratis,berbayar',
            'status_event' => 'required|in:aktif,tidak aktif',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request['created_by'] = Auth::user()->id;
        $event = Events::create($request->all());
        $gambar = null;
        if ($request->hasFile('gambar')) {
            $foto = $request->file('gambar');
            $filename = $event->id . "." . $foto->getClientOriginalExtension();
            $foto->storeAs('public/media/event', $filename);
            $gambar = 'media/event/' . $filename;
        }
        $event->gambar = $gambar;
        $event->save();

        return redirect()->route('admin.events.index')->with('status', 'success#Event berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = "Detail Event";
        $event = Events::findOrFail($id);
        if ($event->gambar == "") {
            $event['gambar'] = 'assets/img/no-image.png';
        } else {
            $event['gambar'] = Storage::exists("public/" . $event->gambar) ? "storage/" . $event->gambar : 'assets/img/no-image.png';
        }

        return view('admin.events.show', compact('title', 'event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "Edit Event";
        $event = Events::findOrFail($id);
        if ($event->gambar == "") {
            $event['gambar'] = 'assets/img/no-image.png';
        } else {
            $event['gambar'] = Storage::exists("public/" . $event->gambar) ? "storage/" . $event->gambar : 'assets/img/no-image.png';
        }

        return view('admin.events.edit', compact('title', 'event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $event = Events::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255|unique:events,judul,' . $id,
            'deskripsi' => 'required|string',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'jenis_event' => 'required|in:gratis,berbayar',
            'status_event' => 'required|in:aktif,tidak aktif',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $event->judul = $request->input('judul');
        $event->deskripsi = $request->input('deskripsi');
        $event->waktu_mulai = $request->input('waktu_mulai');
        $event->waktu_selesai = $request->input('waktu_selesai');
        $event->jenis_event = $request->input('jenis_event');
        $event->status_event = $request->input('status_event');
        $event->kuota = $request->input('kuota') ? $request->input('kuota') : null;
        $event->biaya = $request->input('jenis_event') == 'gratis' ? null : $request->input('biaya');
        $event->updated_by = Auth::user()->id;

        if ($request->hasFile('gambar')) {
            $foto = $request->file('gambar');
            $filename = $event->id . "." . $foto->getClientOriginalExtension();
            $foto->storeAs('public/media/event', $filename);
            $event->gambar = 'media/event/' . $filename;
        }

        $event->save();

        return redirect()->route('admin.events.index')->with('status', 'success#Event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $event = Events::find($id);
            if ($event->gambar != "" && $event->gambar != null) {
                if (Storage::exists("public/" . $event->gambar)) {
                    Storage::delete("public/" . $event->gambar);
                }
            }
            $event->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'   => false,
                'message'  =>  'Tidak dapat mengahapus data , data event sudah berhubungan dengan data lain'
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'message' => 'Success delete event'
        ], 200);
    }
}
