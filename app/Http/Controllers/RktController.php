<?php

namespace App\Http\Controllers;

use App\Models\Rkt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class RktController extends Controller
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
     * Display a listing of the rkt.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $query = Rkt::query();

        // Filter by status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filter by divisi
        if (request('divisi')) {
            $query->where('divisi', request('divisi'));
        }

        // Search
        if (request('search')) {
            $query->where('nama_program', 'like', '%' . request('search') . '%')
                ->orWhere('pic', 'like', '%' . request('search') . '%');
        }

        $rkt = $query->orderBy('tanggal_mulai', 'asc')->paginate(10);

        return view('rkt.index', compact('rkt'));
    }

    /**
     * Store a newly created rkt in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->role === 'viewer') {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki izin untuk menambah RKT.'], 403);
        }

        try {
            $validated = $request->validate([
                'nama_program' => 'required|string|max:255',
                'divisi' => 'required|in:agama,dageri,minba,sospen,kominfo',
                'deskripsi' => 'nullable|string',
                'tujuan' => 'nullable|string',
                'target_peserta' => 'nullable|integer|min:0',
                'lokasi' => 'nullable|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'pic' => 'required|string',
                'anggaran' => 'required|numeric|min:0',
            ]);

            $validated['created_by'] = auth()->user()->name;
            $validated['status'] = 'belum';

            Rkt::create($validated);

            return response()->json(['success' => true, 'message' => 'Program berhasil ditambahkan']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => implode(', ', array_merge(...array_values($e->errors())))], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified rkt.
     *
     * @param  \App\Models\Rkt  $rkt
     * @return \Illuminate\Http\Response
     */
    public function show(Rkt $rkt)
    {
        return response()->json($rkt);
    }

    /**
     * Show the form for editing the specified rkt.
     *
     * @param  \App\Models\Rkt  $rkt
     * @return \Illuminate\Http\Response
     */
    public function edit(Rkt $rkt)
    {
        return response()->json($rkt);
    }

    /**
     * Update the specified rkt in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rkt  $rkt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rkt $rkt)
    {
        if (Auth::user()->role === 'viewer') {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki izin untuk mengubah RKT.'], 403);
        }

        try {
            $validated = $request->validate([
                'nama_program' => 'required|string|max:255',
                'divisi' => 'required|in:agama,dageri,minba,sospen,kominfo',
                'deskripsi' => 'nullable|string',
                'tujuan' => 'nullable|string',
                'target_peserta' => 'nullable|integer|min:0',
                'lokasi' => 'nullable|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'pic' => 'required|string',
                'anggaran' => 'required|numeric|min:0',
                'status' => 'required|in:belum,berjalan,selesai',
            ]);

            $rkt->update($validated);

            return response()->json(['success' => true, 'message' => 'Program berhasil diperbarui']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => implode(', ', array_merge(...array_values($e->errors())))], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified rkt from storage.
     *
     * @param  \App\Models\Rkt  $rkt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rkt $rkt)
    {
        if (Auth::user()->role === 'viewer') {
            abort(403, 'Anda tidak memiliki izin untuk menghapus RKT.');
        }

        $rkt->delete();

        return redirect()->route('rkt')->with('success', 'Program berhasil dihapus');
    }

    /**
     * Get RKT data for AJAX
     *
     * @param  \App\Models\Rkt  $rkt
     * @return \Illuminate\Http\Response
     */
    public function getRkt(Rkt $rkt)
    {
        return response()->json([
            'id' => $rkt->id,
            'nama_program' => $rkt->nama_program,
            'divisi' => $rkt->divisi,
            'divisi_label' => $rkt->divisi_label,
            'deskripsi' => $rkt->deskripsi,
            'tujuan' => $rkt->tujuan,
            'target_peserta' => $rkt->target_peserta,
            'lokasi' => $rkt->lokasi,
            'tanggal_mulai' => $rkt->tanggal_mulai->format('Y-m-d'),
            'tanggal_selesai' => $rkt->tanggal_selesai->format('Y-m-d'),
            'tanggal_mulai_format' => $rkt->tanggal_mulai_format,
            'tanggal_selesai_format' => $rkt->tanggal_selesai_format,
            'pic' => $rkt->pic,
            'anggaran' => $rkt->anggaran,
            'anggaran_format' => $rkt->anggaran_format,
            'status' => $rkt->status,
            'status_label' => $rkt->status_label,
            'status_color' => $rkt->status_color,
            'durasi' => $rkt->durasi,
            'progress' => $rkt->progress_percent,
        ]);
    }

    /**
     * Export RKT to PDF
     *
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $query = Rkt::query();

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by divisi
        if ($request->divisi) {
            $query->where('divisi', $request->divisi);
        }

        $rkt = $query->orderBy('tanggal_mulai', 'asc')->get();

        $pdf = Pdf::loadView('rkt.pdf', compact('rkt'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('RKT_BEM_' . now()->format('d-m-Y') . '.pdf');
    }
}
