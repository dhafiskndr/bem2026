<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnggaranController extends Controller
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
     * Display a listing of anggaran.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $query = Anggaran::query();

        // Filter by jenis
        if ($request->filled('jenis')) {
            $jenis = $request->input('jenis');
            $query->where('jenis', $jenis);
        }

        // Filter by bulan
        if ($request->filled('bulan')) {
            $bulan = $request->input('bulan');
            $query->whereMonth('tanggal', $bulan);
        }

        // Search by keterangan
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('keterangan', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%");
        }

        $anggaran = $query->orderBy('tanggal', 'desc')->paginate(10);

        return view('anggaran.index', compact('anggaran'));
    }

    /**
     * Show the form for creating a new anggaran.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('anggaran.create');
    }

    /**
     * Store a newly created anggaran in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (Auth::user()->role === 'viewer') {
            abort(403, 'Anda tidak memiliki izin untuk menambah anggaran.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1000',
            'pic' => 'required|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::user()->name;

        Anggaran::create($validated);

        return redirect()->route('anggaran')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    /**
     * Display the specified anggaran.
     *
     * @param  Anggaran  $anggaran
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Anggaran $anggaran)
    {
        return view('anggaran.show', compact('anggaran'));
    }

    /**
     * Show the form for editing the specified anggaran.
     *
     * @param  Anggaran  $anggaran
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Anggaran $anggaran)
    {
        return view('anggaran.edit', compact('anggaran'));
    }

    /**
     * Update the specified anggaran in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Anggaran  $anggaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Anggaran $anggaran)
    {
        if (Auth::user()->role === 'viewer') {
            abort(403, 'Anda tidak memiliki izin untuk mengubah anggaran.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1000',
            'pic' => 'required|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $anggaran->update($validated);

        // Check if request expects JSON (AJAX from modal)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui!',
                'anggaran' => $anggaran
            ]);
        }

        return redirect()->route('anggaran')->with('success', 'Transaksi berhasil diperbarui!');
    }

    /**
     * Remove the specified anggaran from storage.
     *
     * @param  Anggaran  $anggaran
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Anggaran $anggaran)
    {
        if (Auth::user()->role === 'viewer') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus anggaran.');
        }

        $anggaran->delete();

        return redirect()->route('anggaran')->with('success', 'Transaksi berhasil dihapus!');
    }

    /**
     * Get anggaran data for modal (JSON)
     *
     * @param  Anggaran  $anggaran
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnggaran(Anggaran $anggaran)
    {
        return response()->json([
            'id' => $anggaran->id,
            'tanggal' => $anggaran->tanggal->format('Y-m-d'),
            'tanggal_format' => $anggaran->tanggal_format,
            'jenis' => $anggaran->jenis,
            'jenis_label' => $anggaran->jenis_label,
            'keterangan' => $anggaran->keterangan,
            'jumlah' => $anggaran->jumlah,
            'jumlah_format' => $anggaran->jumlah_format,
            'pic' => $anggaran->pic,
            'catatan' => $anggaran->catatan,
            'created_by' => $anggaran->created_by,
            'created_at' => $anggaran->created_at->format('d F Y H:i'),
        ]);
    }
}
