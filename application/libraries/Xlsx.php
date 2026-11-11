<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Baca & tulis Excel (.xlsx) tanpa pustaka tambahan (hanya ekstensi zip + SimpleXML bawaan PHP).
 *  - baca($file)        -> array baris (baris pertama = judul kolom). Mendukung .xlsx dan .csv.
 *  - tulis($baris, $namaSheet) -> isi biner .xlsx (sel teks / angka; baris pertama ditebalkan)
 * Bila PhpSpreadsheet terpasang (composer), pembacaan memakai PhpSpreadsheet agar format apa pun didukung.
 */
class Xlsx
{
    public $error = '';

    public function baca($file, $namaAsli = '')
    {
        $ext = strtolower(pathinfo($namaAsli ?: $file, PATHINFO_EXTENSION));
        if ($ext === 'csv' || $ext === 'txt') return $this->baca_csv($file);
        if (class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
            try {
                $s = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
                return $this->rapikan($s->getActiveSheet()->toArray(null, true, false, false));
            } catch (\Throwable $e) { /* lanjut ke pembaca bawaan */ }
        }
        if ($ext !== 'xlsx') { $this->error = 'Format file harus .xlsx atau .csv (file .xls lama: simpan ulang sebagai .xlsx).'; return false; }
        if ( ! class_exists('ZipArchive')) { $this->error = 'Ekstensi PHP zip tidak aktif. Aktifkan extension=zip di php.ini atau gunakan file .csv.'; return false; }
        $z = new ZipArchive();
        if ($z->open($file) !== true) { $this->error = 'File Excel tidak dapat dibuka (rusak / bukan .xlsx).'; return false; }
        $shared = array();
        if (($x = $z->getFromName('xl/sharedStrings.xml')) !== false) {
            $sx = simplexml_load_string($x);
            foreach ($sx->si as $si) {
                if (isset($si->t)) $shared[] = (string) $si->t;
                else { $t = ''; foreach ($si->r as $r) $t .= (string) $r->t; $shared[] = $t; }
            }
        }
        // sheet pertama
        $sheetPath = 'xl/worksheets/sheet1.xml';
        if (($wb = $z->getFromName('xl/workbook.xml')) !== false && ($rel = $z->getFromName('xl/_rels/workbook.xml.rels')) !== false) {
            $wbx = simplexml_load_string($wb); $relx = simplexml_load_string($rel);
            $ns = $wbx->getNamespaces(true);
            $first = $wbx->sheets->sheet[0];
            if ($first) {
                $rid = (string) $first->attributes(isset($ns['r']) ? $ns['r'] : 'http://schemas.openxmlformats.org/officeDocument/2006/relationships')->id;
                foreach ($relx->Relationship as $r) if ((string) $r['Id'] === $rid) { $t = ltrim((string) $r['Target'], '/'); $sheetPath = strpos($t, 'xl/') === 0 ? $t : 'xl/' . $t; }
            }
        }
        $xml = $z->getFromName($sheetPath);
        $z->close();
        if ($xml === false) { $this->error = 'Sheet pertama tidak ditemukan.'; return false; }
        $sx = simplexml_load_string($xml);
        $rows = array();
        foreach ($sx->sheetData->row as $row) {
            $r = array();
            foreach ($row->c as $c) {
                $ref = (string) $c['r']; $col = $this->kolom(preg_replace('/\d+/', '', $ref));
                $t = (string) $c['t']; $v = isset($c->v) ? (string) $c->v : '';
                if ($t === 's') $v = isset($shared[(int) $v]) ? $shared[(int) $v] : '';
                elseif ($t === 'inlineStr') $v = isset($c->is->t) ? (string) $c->is->t : '';
                elseif ($t === 'b') $v = $v === '1' ? 'TRUE' : 'FALSE';
                $r[$col] = $v;
            }
            if ($r) { $max = max(array_keys($r)); $isi = array(); for ($i = 0; $i <= $max; $i++) $isi[] = isset($r[$i]) ? $r[$i] : ''; $rows[(int) $row['r'] - 1] = $isi; }
        }
        ksort($rows);
        return $this->rapikan(array_values($rows));
    }

    private function baca_csv($file)
    {
        $h = fopen($file, 'r');
        if ( ! $h) { $this->error = 'File tidak dapat dibaca.'; return false; }
        $awal = fgets($h); rewind($h);
        $awal = preg_replace('/^\xEF\xBB\xBF/', '', (string) $awal);
        $sep = substr_count($awal, ';') > substr_count($awal, ',') ? ';' : (substr_count($awal, "\t") > substr_count($awal, ',') ? "\t" : ',');
        $rows = array(); $i = 0;
        while (($r = fgetcsv($h, 0, $sep, '"', '\\')) !== false) { if ($i++ === 0 && isset($r[0])) $r[0] = preg_replace('/^\xEF\xBB\xBF/', '', $r[0]); $rows[] = $r; }
        fclose($h);
        return $this->rapikan($rows);
    }

    private function rapikan(array $rows)
    {
        $out = array();
        foreach ($rows as $r) {
            $r = array_map(function ($v) { return is_string($v) ? trim($v) : ($v === null ? '' : $v); }, (array) $r);
            if (implode('', $r) === '') continue;
            $out[] = $r;
        }
        return $out;
    }

    private function kolom($huruf)
    {
        $n = 0;
        foreach (str_split(strtoupper($huruf)) as $c) $n = $n * 26 + (ord($c) - 64);
        return $n - 1;
    }

    private static function huruf($i)
    {
        $s = ''; $i++;
        while ($i > 0) { $m = ($i - 1) % 26; $s = chr(65 + $m) . $s; $i = (int) (($i - $m) / 26); }
        return $s;
    }

    /** buat .xlsx sederhana; $lebar = array lebar kolom (opsional) */
    public function tulis(array $baris, $namaSheet = 'Data', array $lebar = array())
    {
        if ( ! class_exists('ZipArchive')) { $this->error = 'Ekstensi PHP zip tidak aktif.'; return false; }
        $esc = function ($s) { return htmlspecialchars((string) $s, ENT_XML1 | ENT_QUOTES, 'UTF-8'); };
        $sd = '';
        foreach (array_values($baris) as $ri => $r) {
            $sd .= '<row r="' . ($ri + 1) . '">';
            foreach (array_values((array) $r) as $ci => $v) {
                $ref = self::huruf($ci) . ($ri + 1); $st = $ri === 0 ? ' s="1"' : '';
                if (is_int($v) || is_float($v)) $sd .= '<c r="' . $ref . '"' . $st . '><v>' . $v . '</v></c>';
                elseif ($v !== '' && $v !== null) $sd .= '<c r="' . $ref . '" t="inlineStr"' . $st . '><is><t xml:space="preserve">' . $esc($v) . '</t></is></c>';
            }
            $sd .= '</row>';
        }
        $cols = '';
        if ($lebar) { $cols = '<cols>'; foreach (array_values($lebar) as $i => $w) $cols .= '<col min="' . ($i + 1) . '" max="' . ($i + 1) . '" width="' . (float) $w . '" customWidth="1"/>'; $cols .= '</cols>'; }
        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        $z = new ZipArchive(); $z->open($tmp, ZipArchive::OVERWRITE);
        $z->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/><Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/></Types>');
        $z->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $z->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="' . $esc(substr($namaSheet, 0, 31)) . '" sheetId="1" r:id="rId1"/></sheets></workbook>');
        $z->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/><Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/></Relationships>');
        $z->addFromString('xl/styles.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><fonts count="2"><font><sz val="11"/><name val="Calibri"/></font><font><b/><sz val="11"/><name val="Calibri"/></font></fonts><fills count="3"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill><fill><patternFill patternType="solid"><fgColor rgb="FFE7F4F5"/></patternFill></fill></fills><borders count="1"><border/></borders><cellStyleXfs count="1"><xf/></cellStyleXfs><cellXfs count="2"><xf/><xf fontId="1" fillId="2" applyFont="1" applyFill="1"/></cellXfs></styleSheet>');
        $z->addFromString('xl/worksheets/sheet1.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetViews><sheetView workbookViewId="0"><pane ySplit="1" topLeftCell="A2" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>' . $cols . '<sheetData>' . $sd . '</sheetData></worksheet>');
        $z->close();
        $bin = file_get_contents($tmp); @unlink($tmp);
        return $bin;
    }
}
