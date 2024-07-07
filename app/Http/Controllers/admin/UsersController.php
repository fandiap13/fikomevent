<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin(Request $request)
    {
        $search = $request->get('search');
        $title = "List Admin";
        $query = User::query()->where('role', 'admin');
        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }
        $data = $query->paginate(10)->appends(['search' => $search]);
        return view('admin.users.index', compact('data', 'title', 'search'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendaftar(Request $request)
    {
        $search = $request->get('search');
        $title = "List Pendaftar";
        $query = User::query()->where('role', 'pendaftar');
        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }
        $data = $query->paginate(10)->appends(['search' => $search]);
        return view('admin.users.index', compact('data', 'title', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Tambah User";
        return view('admin.users.create', compact('title'));
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
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'nim' => 'nullable|numeric',
            'kelas' => 'nullable|string|max:255',
            'prodi' => 'nullable|string|max:255',
            'telp' => 'required|numeric',
            'instansi' => 'required|string|max:255',
            'status' => 'required|string|in:mahasiswa,dosen,umum',
            'role' => 'required|string|in:pendaftar,admin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nim' => $request->nim,
            'kelas' => $request->kelas,
            'telp' => $request->telp,
            'prodi' => $request->prodi,
            'instansi' => $request->instansi,
            'status' => $request->status,
            'role' => $request->role,
        ]);

        if ($request->role == 'admin') {
            return redirect()->route('admin.users.admin')
                ->with('success', 'User ' . $request->role .  ' berhasil dibuat.');
        } else {
            return redirect()->route('admin.users.pendaftar')
                ->with('success', 'User ' . $request->role .  ' berhasil dibuat.');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $title = "Detail User " . $user->nama;
        return view('admin.users.show', compact('user', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "Edit User";
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user', 'title'));
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
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'nim' => 'nullable|numeric',
            'kelas' => 'nullable|string|max:255',
            'prodi' => 'nullable|string|max:255',
            'telp' => 'required|numeric',
            'instansi' => 'required|string|max:255',
            'status' => 'required|string|in:mahasiswa,dosen,umum',
            'role' => 'required|string|in:pendaftar,admin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::findOrFail($id);

        $user->nama = $request->nama;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->nim = $request->nim;
        $user->kelas = $request->kelas;
        $user->telp = $request->telp;
        $user->prodi = $request->prodi;
        $user->instansi = $request->instansi;
        $user->status = $request->status;
        $user->role = $request->role;
        $user->save();

        if ($user->role == 'admin') {
            return redirect()->route('admin.users.admin')
                ->with('success', 'User ' . $user->role .  ' berhasil diperbarui.');
        } else {
            return redirect()->route('admin.users.pendaftar')
                ->with('success', 'User ' . $user->role .  ' berhasil diperbarui.');
        }
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
            $user = User::find($id);
            $user->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'   => false,
                'message'  =>  'Tidak dapat mengahapus data , data user sudah berhubungan dengan data lain'
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'message' => 'Success delete user'
        ], 200);
    }
}
