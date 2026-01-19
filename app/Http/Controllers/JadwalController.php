<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of jadwal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $query = Jadwal::query();

        // Filter by bulan
        if ($request->filled('bulan')) {
            $bulan = $request->input('bulan');
            $query->whereMonth('tanggal', $bulan);
        }

        // Filter by tahun
        if ($request->filled('tahun')) {
            $tahun = $request->input('tahun');
            $query->whereYear('tanggal', $tahun);
        }

        // Search by nama_kegiatan or lokasi
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_kegiatan', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%");
        }

        $jadwals = $query->orderBy('tanggal', 'asc')->paginate(10);

        return view('jadwal.index', compact('jadwals'));
    }

    /**
     * Show the form for creating a new jadwal.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('jadwal.create');
    }

    /**
     * Store a newly created jadwal in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (Auth::user()->role === 'viewer') {
            abort(403, 'Anda tidak memiliki izin untuk menambah jadwal.');
        }

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'lokasi' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::user()->name;

        Jadwal::create($validated);

        return redirect()->route('jadwal')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    /**
     * Display the specified jadwal.
     *
     * @param  Jadwal  $jadwal
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Jadwal $jadwal)
    {
        return view('jadwal.show', compact('jadwal'));
    }

    /**
     * Show the form for editing the specified jadwal.
     *
     * @param  Jadwal  $jadwal
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Jadwal $jadwal)
    {
        return view('jadwal.edit', compact('jadwal'));
    }

    /**
     * Update the specified jadwal in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Jadwal  $jadwal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jadwal $jadwal)
    {
        if (Auth::user()->role === 'viewer') {
            abort(403, 'Anda tidak memiliki izin untuk mengubah jadwal.');
        }

        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'lokasi' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $jadwal->update($validated);

        // Check if request expects JSON (AJAX from modal)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diperbarui!',
                'jadwal' => $jadwal
            ]);
        }

        return redirect()->route('jadwal')->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Remove the specified jadwal from storage.
     *
     * @param  Jadwal  $jadwal
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Jadwal $jadwal)
    {
        if (Auth::user()->role === 'viewer') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus jadwal.');
        }

        $jadwal->delete();

        return redirect()->route('jadwal')->with('success', 'Jadwal berhasil dihapus!');
    }

    /**
     * Get jadwal data for modal (JSON)
     *
     * @param  Jadwal  $jadwal
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJadwal(Jadwal $jadwal)
    {
        return response()->json([
            'id' => $jadwal->id,
            'nama_kegiatan' => $jadwal->nama_kegiatan,
            'tanggal' => $jadwal->tanggal->format('Y-m-d'),
            'tanggal_format' => $jadwal->tanggal_format,
            'jam_mulai' => $jadwal->jam_mulai,
            'jam_selesai' => $jadwal->jam_selesai,
            'jam_format' => $jadwal->jam_format,
            'datetime_format' => $jadwal->datetime_format,
            'lokasi' => $jadwal->lokasi,
            'pic' => $jadwal->pic,
            'deskripsi' => $jadwal->deskripsi,
            'created_by' => $jadwal->created_by,
            'created_at' => $jadwal->created_at->format('d F Y H:i'),
        ]);
    }
}
