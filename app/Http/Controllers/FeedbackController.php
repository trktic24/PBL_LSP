<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\FeedbackItem;
use App\Models\Asesi;
use App\Models\Asesor;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function showForm($id = null)
    {
        // Kalau $id ada, ambil feedback lama beserta relasinya
        if ($id) {
            $feedback = Feedback::with(['asesi', 'asesor', 'items'])->find($id);
        } else {
            $feedback = null;
        }

        return view('umpan_balik', [
            'feedback' => $feedback,
            'asesi'    => $feedback?->asesi,
            'asesor'   => $feedback?->asesor,
        ]);
    }

    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'tuk' => 'required|string',
            'items' => 'required|array',
            'items.*.nomor' => 'required|integer',
            'items.*.pernyataan' => 'required|string',
            'items.*.ya' => 'nullable|boolean',
            'items.*.tidak' => 'nullable|boolean',
            'items.*.catatan' => 'nullable|string',
            'catatan_tambahan' => 'nullable|string',
        ]);

        // Jika $id ada, ambil feedback lama; jika tidak ada, buat baru
        $feedback = $id ? Feedback::find($id) : null;

        if (!$feedback) {
            // Kita butuh id_asesi dan id_asesor dari frontend
            $feedback = Feedback::create([
                'id_asesi' => $asesi->id,
                'id_asesor' => $asesor->id,
            ]);
        }

        // Update TUK & catatan tambahan
        $feedback->update([
            'tuk' => $validated['tuk'],
            'catatan_tambahan' => $validated['catatan_tambahan'] ?? null,
        ]);

        // Hapus items lama (jika edit)
        if ($feedback->items()->count() > 0) {
            $feedback->items()->delete();
        }

        // Simpan semua item baru
        foreach ($validated['items'] as $item) {
        FeedbackItem::create([
        'feedback_id' => $feedback->id,
        'nomor' => $item['nomor'],
        'pernyataan' => $item['pernyataan'],
        'ya' => isset($item['jawaban']) && $item['jawaban'] == 1,
        'tidak' => isset($item['jawaban']) && $item['jawaban'] == 0,
        'catatan' => $item['catatan'] ?? null,
        ]);
    }



        return redirect()->back()->with('success', 'Feedback berhasil disimpan!');
    }
}