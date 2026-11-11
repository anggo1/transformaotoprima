/*!
 * TOP Chart — grafik untuk dashboard & laporan.
 * Elemen: <div class="top-chart" data-chart='{"judul":"..","tipe":"bar","labels":[..],"seri":[{"nama":"..","data":[..]}],"format":"rp"}'></div>
 *   tipe   : bar | line | area | pie | doughnut | horizontalBar | pareto
 *   format : rp | qty | persen (format angka sumbu & tooltip)
 * Setiap grafik mendapat tombol: Unduh PNG, Unduh CSV (Excel), Cetak.
 * Chart.js (v2.9) dimuat otomatis hanya bila halaman memiliki grafik.
 */
(function (w, d) {
    'use strict';
    var muat = null;
    var PALET = ['#0e7c86', '#e0a43a', '#4f46e5', '#c0392b', '#12805c', '#7c3aed', '#2563a8', '#b7791f', '#db2777', '#475467', '#0891b2', '#65a30d'];

    function css(v, d0) { try { var x = getComputedStyle(d.documentElement).getPropertyValue(v).trim(); return x || d0; } catch (e) { return d0; } }
    function warna(i) { return i === 0 ? css('--top-accent', PALET[0]) : PALET[i % PALET.length]; }
    function alpha(hex, a) {
        if (!hex || hex.charAt(0) !== '#') return hex;
        var h = hex.length === 4 ? hex.replace(/#(.)(.)(.)/, '#$1$1$2$2$3$3') : hex;
        return 'rgba(' + parseInt(h.substr(1, 2), 16) + ',' + parseInt(h.substr(3, 2), 16) + ',' + parseInt(h.substr(5, 2), 16) + ',' + a + ')';
    }
    function fmt(v, f) {
        if (v === null || v === undefined || isNaN(v)) return '-';
        if (f === 'persen') return (Math.round(v * 10) / 10).toLocaleString('en-US') + '%';
        var a = Math.abs(v);
        if (f === 'rp' && a >= 1e9) return (v / 1e9).toLocaleString('en-US', { maximumFractionDigits: 2 }) + ' M';
        if (f === 'rp' && a >= 1e6) return (v / 1e6).toLocaleString('en-US', { maximumFractionDigits: 1 }) + ' jt';
        return Math.round(v * 100) / 100 === Math.round(v) ? Math.round(v).toLocaleString('en-US') : v.toLocaleString('en-US', { maximumFractionDigits: 2 });
    }
    function fmtPenuh(v, f) {
        if (f === 'persen') return fmt(v, f);
        return (Math.round(v * 100) / 100).toLocaleString('en-US', { maximumFractionDigits: 2 });
    }

    function siapkanChartJs() {
        if (w.Chart) return Promise.resolve(w.Chart);
        if (muat) return muat;
        muat = new Promise(function (ok, gagal) {
            var s = d.createElement('script');
            s.src = ((w.TOP && TOP.baseUrl) || '/') + 'assets/plugins/chart.js/Chart.min.js';
            s.onload = function () { ok(w.Chart); };
            s.onerror = gagal;
            d.head.appendChild(s);
        });
        return muat;
    }

    function konfigurasi(sp) {
        var tipe = sp.tipe || 'bar', bulat = (tipe === 'pie' || tipe === 'doughnut'), f = sp.format || 'qty';
        var gelap = d.documentElement.className.indexOf('top-dark') > -1, teks = gelap ? '#c9d3e1' : '#475467', garis = gelap ? 'rgba(255,255,255,.07)' : 'rgba(16,24,40,.06)';
        var ds = (sp.seri || []).map(function (s, i) {
            var c = s.warna || warna(i), t = s.tipe || (tipe === 'area' ? 'line' : (tipe === 'pareto' ? 'bar' : tipe));
            var o = { label: s.nama || '', data: s.data || [], borderWidth: bulat ? 2 : (t === 'line' ? 2.5 : 0), type: t === 'horizontalBar' ? undefined : t };
            if (bulat) {
                o.backgroundColor = (s.data || []).map(function (_, j) { return s.warna_item && s.warna_item[j] ? s.warna_item[j] : warna(j); });
                o.borderColor = gelap ? '#111a2b' : '#fff';
            } else if (t === 'line') {
                o.borderColor = c; o.backgroundColor = tipe === 'area' ? alpha(c, .15) : 'transparent'; o.fill = tipe === 'area';
                o.pointRadius = 3; o.pointBackgroundColor = c; o.lineTension = .15;
            } else {
                o.backgroundColor = alpha(c, .85); o.hoverBackgroundColor = c; o.maxBarThickness = 38;
            }
            if (s.sumbu === 'kanan') o.yAxisID = 'kanan';
            return o;
        });
        if (tipe === 'pareto' && sp.seri && sp.seri[0]) {
            var tot = 0, kum = 0, data = sp.seri[0].data || [];
            data.forEach(function (v) { tot += +v || 0; });
            ds.push({ label: 'Kumulatif %', type: 'line', data: data.map(function (v) { kum += +v || 0; return tot ? Math.round(kum / tot * 1000) / 10 : 0; }),
                borderColor: '#c0392b', backgroundColor: 'transparent', yAxisID: 'kanan', pointRadius: 2, borderWidth: 2, fill: false });
        }
        var adaKanan = ds.some(function (x) { return x.yAxisID === 'kanan'; });
        var horizontal = tipe === 'horizontalBar';
        var sumbuNilai = { gridLines: { color: garis, zeroLineColor: garis }, ticks: { fontColor: teks, beginAtZero: true, callback: function (v) { return fmt(v, f); } }, stacked: !!sp.tumpuk };
        var sumbuLabel = { gridLines: { display: false }, ticks: { fontColor: teks, autoSkip: true, maxRotation: 45, callback: function (v) { v = String(v); return v.length > 22 ? v.substr(0, 21) + '…' : v; } }, stacked: !!sp.tumpuk };
        var opsi = {
            responsive: true, maintainAspectRatio: false, animation: { duration: sp.tanpaAnimasi ? 0 : 500 },
            legend: { display: bulat || ds.length > 1, position: bulat ? 'right' : 'bottom', labels: { fontColor: teks, boxWidth: 12, padding: 12 } },
            tooltips: { mode: bulat ? 'nearest' : 'index', intersect: false, callbacks: {
                label: function (it, data) {
                    var dset = data.datasets[it.datasetIndex], v = dset.data[it.index], lbl = bulat ? data.labels[it.index] : dset.label;
                    var ff = dset.yAxisID === 'kanan' ? 'persen' : f;
                    var tambahan = '';
                    if (bulat) { var t2 = dset.data.reduce(function (a, b) { return a + (+b || 0); }, 0); if (t2) tambahan = ' (' + (Math.round(v / t2 * 1000) / 10) + '%)'; }
                    return (lbl ? lbl + ': ' : '') + fmtPenuh(+v, ff) + tambahan;
                } } }
        };
        if (!bulat) {
            opsi.scales = horizontal ? { xAxes: [sumbuNilai], yAxes: [sumbuLabel] } : { xAxes: [sumbuLabel], yAxes: [sumbuNilai] };
            if (adaKanan) opsi.scales.yAxes.push({ id: 'kanan', position: 'right', gridLines: { display: false }, ticks: { fontColor: teks, beginAtZero: true, max: 100, callback: function (v) { return v + '%'; } } });
        }
        return { type: horizontal ? 'horizontalBar' : (bulat ? tipe : (tipe === 'area' || tipe === 'line' ? 'line' : 'bar')), data: { labels: sp.labels || [], datasets: ds }, options: opsi };
    }

    function csv(sp) {
        var b = [['Label'].concat((sp.seri || []).map(function (s) { return s.nama || 'Nilai'; }))];
        (sp.labels || []).forEach(function (l, i) { b.push([l].concat((sp.seri || []).map(function (s) { return s.data[i]; }))); });
        return '﻿' + b.map(function (r) { return r.map(function (x) { x = x === null || x === undefined ? '' : String(x); return /[",;\n]/.test(x) ? '"' + x.replace(/"/g, '""') + '"' : x; }).join(','); }).join('\r\n');
    }
    function unduh(nama, href) { var a = d.createElement('a'); a.href = href; a.download = nama; d.body.appendChild(a); a.click(); setTimeout(function () { d.body.removeChild(a); }, 100); }
    function namaFile(sp) { return (sp.judul || 'grafik').replace(/[^A-Za-z0-9]+/g, '_').replace(/^_|_$/g, '') + '_' + new Date().toISOString().slice(0, 10); }
    function png(canvas) {
        var c = d.createElement('canvas'); c.width = canvas.width; c.height = canvas.height;
        var x = c.getContext('2d'); x.fillStyle = '#ffffff'; x.fillRect(0, 0, c.width, c.height); x.drawImage(canvas, 0, 0);
        return c.toDataURL('image/png');
    }

    function satu(el) {
        if (el.__topChart) return Promise.resolve(el.__topChart);
        var sp; try { sp = JSON.parse(el.getAttribute('data-chart')); } catch (e) { return Promise.resolve(null); }
        var cetak = el.hasAttribute('data-cetak');
        if (!sp.labels || !sp.labels.length) { el.innerHTML = '<div class="top-chart__kosong">Belum ada data untuk grafik.</div>'; return Promise.resolve(null); }
        el.innerHTML = (sp.judul && !el.hasAttribute('data-tanpa-judul') ? '<div class="top-chart__judul">' + sp.judul + '</div>' : '') +
            (cetak ? '' : '<div class="top-chart__alat"><button type="button" data-a="png" title="Unduh gambar PNG"><i class="fas fa-image"></i> PNG</button><button type="button" data-a="csv" title="Unduh data (buka di Excel)"><i class="fas fa-file-csv"></i> Data</button><button type="button" data-a="cetak" title="Cetak grafik"><i class="fas fa-print"></i></button></div>') +
            '<div class="top-chart__kanvas" style="height:' + (sp.tinggi || 260) + 'px"><canvas></canvas></div>';
        return siapkanChartJs().then(function (Chart) {
            if (cetak) sp.tanpaAnimasi = true;
            var ch = new Chart(el.querySelector('canvas').getContext('2d'), konfigurasi(sp));
            el.__topChart = ch;
            el.addEventListener('click', function (e) {
                var b = e.target.closest ? e.target.closest('button[data-a]') : null; if (!b) return;
                var a = b.getAttribute('data-a');
                if (a === 'png') unduh(namaFile(sp) + '.png', png(ch.canvas));
                if (a === 'csv') unduh(namaFile(sp) + '.csv', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv(sp)));
                if (a === 'cetak') {
                    var win = w.open('', '_blank'); if (!win) return;
                    win.document.write('<!DOCTYPE html><html><head><title>' + (sp.judul || 'Grafik') + '</title><style>body{font-family:Segoe UI,Arial,sans-serif;margin:24px;text-align:center}img{max-width:100%}h3{margin:0 0 4px}small{color:#666}</style></head><body><h3>' + (sp.judul || '') + '</h3><small>Dicetak ' + new Date().toLocaleString('id-ID') + '</small><br><br><img src="' + png(ch.canvas) + '"><script>window.onload=function(){window.print();}<\/script></body></html>');
                    win.document.close();
                }
            });
            return ch;
        }).catch(function () { el.innerHTML = '<div class="top-chart__kosong">Pustaka grafik gagal dimuat.</div>'; return null; });
    }

    function render(root) {
        var el = (root || d).querySelectorAll('.top-chart[data-chart]'), p = [];
        for (var i = 0; i < el.length; i++) p.push(satu(el[i]));
        return Promise.all(p);
    }

    w.TOPChart = { render: render, siapkanChartJs: siapkanChartJs, format: fmt };
    if (d.readyState !== 'loading') render(); else d.addEventListener('DOMContentLoaded', function () { render(); });
})(window, document);
