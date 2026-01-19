<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratController extends Controller
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
     * Display a listing of the surat.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $query = Surat::query();

        // Filter berdasarkan tipe surat
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_surat', $request->bulan);
        }

        // Search berdasarkan nomor atau perihal
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%$search%")
                  ->orWhere('perihal', 'like', "%$search%");
            });
        }

        // Sort by tanggal terbaru
        $surats = $query->orderBy('tanggal_surat', 'desc')->paginate(10);

        return view('surat.index', compact('surats'));
    }

    /**
     * Show the form for creating a new surat.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('surat.create');
    }

    /**
     * Store a newly created surat in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (Auth::user()->role === 'viewer') {
            abort(403, 'Anda tidak memiliki izin untuk menambah surat.');
        }

        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surats,nomor_surat',
            'tipe' => 'required|in:masuk,keluar',
            'perihal' => 'required|string',
            'tanggal_surat' => 'required|date',
            'dari_tujuan' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $path = $file->store('surats', 'public');
            $validated['file_path'] = $path;
        }

        $validated['created_by'] = Auth::user()->name;

        Surat::create($validated);

        return redirect()->route('surat')->with('success', 'Surat berhasil ditambahkan!');
    }

    /**
     * Display the specified surat.
     *
     * @param  Surat  $surat
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Surat $surat)
    {
        return view('surat.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified surat.
     *
     * @param  Surat  $surat
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit(Surat $surat)
    {
        return view('surat.edit', compact('surat'));
    }

    /**
     * Update the specified surat in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Surat  $surat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Surat $surat)
    {
        if (Auth::user()->role === 'viewer') {
            abort(403, 'Anda tidak memiliki izin untuk mengubah surat.');
        }

        $validated = $request->validate([
            'nomor_surat' => 'required|string|unique:surats,nomor_surat,' . $surat->id,
            'tipe' => 'required|in:masuk,keluar',
            'perihal' => 'required|string',
            'tanggal_surat' => 'required|date',
            'dari_tujuan' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Handle file upload
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $path = $file->store('surats', 'public');
            $validated['file_path'] = $path;
        }

        $surat->update($validated);

        // Check if request expects JSON (AJAX from modal)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Surat berhasil diperbarui!',
                'surat' => $surat
            ]);
        }

        return redirect()->route('surat')->with('success', 'Surat berhasil diperbarui!');
    }

    /**
     * Remove the specified surat from storage.
     *
     * @param  Surat  $surat
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Surat $surat)
    {
        if (Auth::user()->role === 'viewer') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus surat.');
        }

        $surat->delete();

        return redirect()->route('surat')->with('success', 'Surat berhasil dihapus!');
    }

    /**
     * Get surat data for modal (JSON)
     *
     * @param  Surat  $surat
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSurat(Surat $surat)
    {
        return response()->json([
            'id' => $surat->id,
            'nomor_surat' => $surat->nomor_surat,
            'tipe' => $surat->tipe,
            'tipe_label' => $surat->tipe_label,
            'perihal' => $surat->perihal,
            'tanggal_surat' => $surat->tanggal_surat->format('Y-m-d'),
            'tanggal_format' => $surat->tanggal_format,
            'dari_tujuan' => $surat->dari_tujuan,
            'keterangan' => $surat->keterangan,
            'file_path' => $surat->file_path ? asset('storage/' . $surat->file_path) : null,
            'created_by' => $surat->created_by,
            'created_at' => $surat->created_at->format('d F Y H:i'),
        ]);
    }
}
