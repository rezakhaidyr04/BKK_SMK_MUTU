<?php

namespace App\Support;

/**
 * P0 H-10: sanitasi HTML berita tanpa dependency baru.
 * Allowlist ketat + hapus event-handler + javascript:/data:text/html.
 * Dipakai di controller (saat simpan) dan blade (saat repopulasi old()).
 */
class HtmlSanitizer
{
    /**
     * Bersihkan HTML agar aman dirender dengan {!! !!}.
     */
    public static function cleanNews(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        // 1. Hanya tag aman. Semua <script>, <iframe>, <object>, <embed>,
        // <form>, <style>, dll langsung dibuang beserta isinya via strip_tags
        // (tag tidak diizinkan di-strip, isi text dipertahankan — cukup untuk P0;
        // <script> menjadi text, tidak dieksekusi).
        $allowed = '<p><br><b><strong><i><em><u><h2><h3><ul><ol><li><a><img><blockquote>';
        $clean = strip_tags($html, $allowed);

        // 2. Hapus event-handler on*="..." / on*='...' / on*=... (onclick, onerror, dll).
        // Pola: spasi + on + huruf + = + ("..." | '...' | tanpa-spasi).
        $clean = preg_replace('/\s+on[a-zA-Z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $clean);

        // 3. Hapus javascript:/vbscript:/data:text/html pada href/src.
        // Pertahankan /storage/* dan http(s) biasa.
        $clean = preg_replace_callback(
            '/(href|src)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i',
            function ($m) {
                $attr = strtolower($m[1]);
                $raw = trim($m[2], "\"' ");
                $lower = strtolower(ltrim($raw));

                // Blokir skema berbahaya.
                if (
                    str_starts_with($lower, 'javascript:') ||
                    str_starts_with($lower, 'vbscript:') ||
                    str_starts_with($lower, 'data:text/html') ||
                    str_starts_with($lower, 'data:') && ! str_starts_with($lower, 'data:image/')
                ) {
                    return $attr . '="#"';
                }

                // Untuk img: hanya izinkan /storage/, http(s), atau relative aman.
                // Tolak iri yang mengandung <>"'` atau newline (header injection).
                if (preg_match('/[<>"\'`\r\n]/', $raw)) {
                    return $attr . '="#"';
                }

                return $m[0];
            },
            $clean
        );

        // 4. Hapus <a> tanpa href yang valid? Biarkan — tidak berbahaya setelah langkah 3.

        return $clean;
    }
}
