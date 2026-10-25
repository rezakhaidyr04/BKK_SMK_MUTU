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

        // Jika konten polos (tanpa tag HTML), format jadi HTML rapi:
        // paragraf, heading (baris ALL CAPS), dan list (baris "-"/"1.").
        if (! preg_match('/<\s*\/?\s*[a-zA-Z][^>]*>/', $html)) {
            $html = self::formatPlainText($html);
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

    /**
     * Format teks polos menjadi HTML rapi (paragraf, heading, list).
     */
    private static function formatPlainText(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $blocks = preg_split('/\n[ \t]*\n/', $text);
        $out = [];

        foreach ($blocks as $block) {
            $block = trim($block);
            if ($block === '') {
                continue;
            }

            $lines = [];
            foreach (explode("\n", $block) as $ln) {
                $ln = trim($ln);
                if ($ln !== '') {
                    $lines[] = $ln;
                }
            }
            if (empty($lines)) {
                continue;
            }

            // Semua baris adalah item list.
            if (self::allListItems($lines)) {
                $out[] = self::renderList($lines);
                continue;
            }

            // Sebagian baris item list.
            $listLines = [];
            $textLines = [];
            foreach ($lines as $ln) {
                if (self::isListItem($ln)) {
                    $listLines[] = $ln;
                } else {
                    $textLines[] = $ln;
                }
            }

            if (count($listLines) > 0 && count($listLines) >= count($lines) - 1) {
                foreach ($textLines as $tl) {
                    $out[] = self::renderTextLine($tl);
                }
                $out[] = self::renderList($listLines);
                continue;
            }

            // Satu baris heading.
            if (count($lines) === 1 && self::isHeading($lines[0])) {
                $out[] = '<h2>' . htmlspecialchars($lines[0], ENT_QUOTES, 'UTF-8') . '</h2>';
                continue;
            }

            // Paragraf biasa: gabung baris jadi satu paragraf.
            $para = implode(' ', $lines);
            $out[] = '<p>' . htmlspecialchars($para, ENT_QUOTES, 'UTF-8') . '</p>';
        }

        return implode("\n", $out);
    }

    private static function isListItem(string $line): bool
    {
        return (bool) preg_match('/^(\-|\*|•|\d+[\.\)])\s+.+/', $line);
    }

    private static function allListItems(array $lines): bool
    {
        if (empty($lines)) {
            return false;
        }
        foreach ($lines as $ln) {
            if (! self::isListItem($ln)) {
                return false;
            }
        }
        return true;
    }

    private static function renderList(array $lines): string
    {
        $ordered = false;
        foreach ($lines as $ln) {
            if (preg_match('/^\d+[\.\)]\s+/', $ln)) {
                $ordered = true;
                break;
            }
        }

        $tag = $ordered ? 'ol' : 'ul';
        $items = [];
        foreach ($lines as $ln) {
            $item = preg_replace('/^(\-|\*|•|\d+[\.\)])\s+/', '', $ln);
            $items[] = '<li>' . htmlspecialchars((string) $item, ENT_QUOTES, 'UTF-8') . '</li>';
        }

        return '<' . $tag . '>' . implode('', $items) . '</' . $tag . '>';
    }

    private static function isHeading(string $line): bool
    {
        $letters = preg_replace('/[^A-Za-z]/u', '', $line);
        if ($letters === '' || mb_strlen($line) > 90) {
            return false;
        }
        // ALL CAPS (boleh ada angka/simbol).
        if (mb_strtoupper($line, 'UTF-8') === $line) {
            return true;
        }
        // Baris pendek yang diakhiri titik dua.
        if (mb_strlen($line) <= 70 && str_ends_with($line, ':')) {
            return true;
        }

        return false;
    }

    private static function renderTextLine(string $line): string
    {
        if (self::isHeading($line)) {
            if (mb_strtoupper($line, 'UTF-8') === $line) {
                return '<h2>' . htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . '</h2>';
            }

            return '<h3>' . htmlspecialchars(rtrim($line, ':'), ENT_QUOTES, 'UTF-8') . '</h3>';
        }

        return '<p>' . htmlspecialchars($line, ENT_QUOTES, 'UTF-8') . '</p>';
    }
}
