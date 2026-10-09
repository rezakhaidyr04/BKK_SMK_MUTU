<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTracerStudyRequest;

class TracerStudyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['index', 'store']);
    }

    /**
     * Form tracer — khusus role umum.
     */
    public function index()
    {
        if (auth()->user()->role !== 'umum') {
            return redirect()->route('dashboard')->with('error', 'Halaman tracer study khusus untuk pencari kerja.');
        }

        $tracer = auth()->user()->tracerStudy;

        return view('tracer.index', compact('tracer'));
    }

    /**
     * Simpan / perbarui (satu baris per user via updateOrCreate).
     */
    public function store(StoreTracerStudyRequest $request)
    {
        $validated = $request->validated();

        // Normalisasi boolean dari select "1"/"0"/"".
        if (array_key_exists('is_relevant', $validated)) {
            $validated['is_relevant'] = $validated['is_relevant'] === null || $validated['is_relevant'] === ''
                ? null
                : (bool) $validated['is_relevant'];
        }

        auth()->user()->tracerStudy()->updateOrCreate(
            ['user_id' => auth()->id()],
            $validated + ['filled_at' => now()],
        );

        return back()->with('success', 'Terima kasih! Data tracer study berhasil disimpan.');
    }
}
