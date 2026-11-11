/*!
 * TOP ERP - skrip global (ringan, tanpa dependensi selain jQuery)
 * Dimuat sekali di layout: pengaturan default plugin, sidebar, pencarian menu,
 * penanganan sesi habis pada AJAX, dan helper format angka.
 */
(function ($, w, d) {
    'use strict';
    var TOP = w.TOP = w.TOP || {};

    /* ---------- helper angka ---------- */
    TOP.parse = function (v) {
        if (typeof v === 'number') return v;
        v = String(v == null ? '' : v).replace(/[^0-9.\-]/g, '');
        var n = parseFloat(v);
        return isNaN(n) ? 0 : n;
    };
    TOP.fmt = function (n, dec) {
        dec = dec == null ? 0 : dec;
        n = TOP.parse(n);
        var neg = n < 0; n = Math.abs(n);
        var s = n.toFixed(dec).split('.');
        s[0] = s[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        return (neg ? '-' : '') + s.join('.');
    };
    /* input angka berformat ribuan: <input class="input-angka"> */
    $(d).on('focus', 'input.input-angka', function () { this.select && this.select(); })
        .on('blur', 'input.input-angka', function () {
            if (this.value !== '') this.value = TOP.fmt(this.value, this.getAttribute('data-dec') || 0);
        });

    /* ---------- toastr & sweetalert ---------- */
    if (w.toastr) {
        toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-top-right', timeOut: 3500, newestOnTop: true };
    }
    TOP.konfirmasi = function (judul, teks, onYa, tombol) {
        if (!w.Swal) { if (confirm(judul + '\n' + (teks || ''))) onYa(); return; }
        Swal.fire({ title: judul, text: teks || '', icon: 'warning', showCancelButton: true,
            confirmButtonText: tombol || 'Ya, lanjutkan', cancelButtonText: 'Batal', reverseButtons: true,
            confirmButtonColor: '#0e7c86', cancelButtonColor: '#94a3b8'
        }).then(function (r) { if (r.isConfirmed || r.value) onYa(); });
    };

    /* ---------- AJAX: sesi habis / akses ditolak ---------- */
    $(d).ajaxError(function (e, xhr) {
        if (xhr.status === 401) {
            if (w.toastr) toastr.warning('Sesi Anda telah berakhir. Mengalihkan ke halaman login...');
            setTimeout(function () { w.location.href = TOP.siteUrl + 'login'; }, 1500);
        } else if (xhr.status === 403) {
            var m = 'Anda tidak memiliki hak akses.';
            try { m = JSON.parse(xhr.responseText).pesan || m; } catch (er) {}
            if (w.toastr) toastr.error(m);
        }
    });

    /* ---------- DataTables default (bahasa sesuai pilihan pengguna) ---------- */
    var EN = (w.TOP && TOP.lang === 'en');
    if ($.fn.dataTable) {
        $.extend(true, $.fn.dataTable.defaults, {
            language: EN ? {
                decimal: '', emptyTable: 'No data available', info: 'Showing _START_ - _END_ of _TOTAL_ entries',
                infoEmpty: 'Showing 0 entries', infoFiltered: '(filtered from _MAX_ entries)', thousands: ',',
                lengthMenu: 'Show _MENU_', loadingRecords: 'Loading...', processing: 'Processing...',
                search: '', searchPlaceholder: 'Search...', zeroRecords: 'No matching records found',
                paginate: { first: '&laquo;', last: '&raquo;', next: '&rsaquo;', previous: '&lsaquo;' }
            } : {
                decimal: '', emptyTable: 'Tidak ada data', info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Menampilkan 0 data', infoFiltered: '(disaring dari _MAX_ data)', thousands: ',',
                lengthMenu: 'Tampil _MENU_', loadingRecords: 'Memuat...', processing: 'Memproses...',
                search: '', searchPlaceholder: 'Cari...', zeroRecords: 'Data tidak ditemukan',
                paginate: { first: '&laquo;', last: '&raquo;', next: '&rsaquo;', previous: '&lsaquo;' }
            }
        });
        $.fn.dataTable.ext.errMode = function (s, tn, msg) { if (w.console) console.warn(msg); };
    }

    /* ---------- pdfmake lazy-load ----------
     * Tombol PDF DataTables memanggil pdfMake.createPdf(doc).download()/open().
     * Stub ini memuat pdfmake asli hanya saat dibutuhkan agar setiap halaman lebih ringan. */
    if (!w.pdfMake) {
        var pdfLoad = null;
        var muatPdf = function () {
            if (pdfLoad) return pdfLoad;
            var base = (TOP.baseUrl || '/') + 'assets/plugins/pdfmake/';
            pdfLoad = $.Deferred();
            var stub = w.pdfMake;
            $.ajax({ url: base + 'pdfmake.min.js', dataType: 'script', cache: true })
                .then(function () { return $.ajax({ url: base + 'vfs_fonts.js', dataType: 'script', cache: true }); })
                .done(function () { pdfLoad.resolve(w.pdfMake); })
                .fail(function () { w.pdfMake = stub; pdfLoad = null; if (w.toastr) toastr.error('Gagal memuat modul PDF.'); });
            return pdfLoad;
        };
        w.pdfMake = {
            _stub: true,
            createPdf: function (doc) {
                var jalan = function (m) { return function () { var a = arguments; muatPdf().done(function (real) { var p = real.createPdf(doc); p[m].apply(p, a); }); }; };
                return { download: jalan('download'), open: jalan('open'), print: jalan('print'), getBlob: jalan('getBlob'), getBase64: jalan('getBase64') };
            }
        };
    }

    /* ---------- DataTables Buttons: gaya tombol seragam ---------- */
    if ($.fn.dataTable && $.fn.dataTable.Buttons) {
        $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn btn-sm btn-outline-secondary';
    }

    /* ---------- Select2 ---------- */
    if ($.fn.select2) {
        $.fn.select2.defaults.set('theme', 'bootstrap4');
        $.fn.select2.defaults.set('width', '100%');
    }

    $(function () {
        /* status sidebar diingat per browser */
        $(d).on('collapsed.lte.pushmenu', function () { try { localStorage.setItem('top.sidebar', 'collapsed'); } catch (e) {} })
            .on('shown.lte.pushmenu', function () { try { localStorage.setItem('top.sidebar', 'open'); } catch (e) {} });

        /* pencarian menu di sidebar */
        var $cari = $('#top-menu-search');
        $cari.on('input', function () {
            var q = $.trim(this.value).toLowerCase();
            var $root = $('.top-sidebar .nav-sidebar');
            if (!q) {
                $root.find('> .nav-item').show().find('.nav-treeview > .nav-item').show();
                $root.find('> .nav-item.top-search-open').removeClass('menu-open top-search-open').find('> .nav-treeview').css('display', '');
                return;
            }
            $root.find('> .nav-item').each(function () {
                var $li = $(this), parentHit = $li.children('.nav-link').text().toLowerCase().indexOf(q) > -1, any = false;
                $li.find('.nav-treeview > .nav-item').each(function () {
                    var hit = parentHit || $(this).text().toLowerCase().indexOf(q) > -1;
                    $(this).toggle(hit); any = any || hit;
                });
                var show = parentHit || any;
                $li.toggle(show);
                if (show && $li.hasClass('has-treeview') && !$li.hasClass('menu-open')) {
                    $li.addClass('menu-open top-search-open').children('.nav-treeview').css('display', 'block');
                }
            });
        }).on('keydown', function (e) {
            if (e.key === 'Enter') { var $a = $('.top-sidebar .nav-treeview .nav-link:visible, .top-sidebar .nav-sidebar > .nav-item:not(.has-treeview) > .nav-link:visible').first(); if ($a.length && $a.attr('href') !== '#') w.location.href = $a.attr('href'); }
            if (e.key === 'Escape') { this.value = ''; $(this).trigger('input'); }
        });
        /* pintasan "/" untuk fokus ke pencarian menu */
        $(d).on('keydown', function (e) {
            if (e.key === '/' && !$(e.target).is('input,textarea,select,[contenteditable]')) { e.preventDefault(); $cari.focus(); }
        });

        /* jam */
        var $clock = $('#top-clock');
        if ($clock.length) {
            var hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            var bln = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            var tick = function () {
                var t = new Date(), p = function (x) { return (x < 10 ? '0' : '') + x; };
                $clock.text(hari[t.getDay()] + ', ' + t.getDate() + ' ' + bln[t.getMonth()] + ' ' + t.getFullYear() + '  ' + p(t.getHours()) + ':' + p(t.getMinutes()));
            };
            tick(); setInterval(tick, 30000);
        }

        /* layar penuh */
        $('#top-fullscreen').on('click', function (e) {
            e.preventDefault();
            if (!d.fullscreenElement) { (d.documentElement.requestFullscreen || function () {}).call(d.documentElement); }
            else if (d.exitFullscreen) { d.exitFullscreen(); }
        });

        /* menu favorit: sematkan / lepas */
        $(d).on('click', '.top-fav-btn', function (e) {
            e.preventDefault(); e.stopPropagation();
            var link = $(this).data('link');
            $.post(TOP.siteUrl + 'Tampilan/favorit', { link: link }).done(function (r) {
                if (typeof r === 'string') { try { r = JSON.parse(r); } catch (er) { return; } }
                if (!r || !r.status) { if (r && r.pesan && w.toastr) toastr.warning(r.pesan); return; }
                var $nav = $('.top-sidebar .nav-sidebar');
                $nav.children('.top-sb-fav-head, .top-sb-fav, .top-sb-fav-empty').remove();
                $nav.prepend(r.html);
                $nav.find('.nav-treeview .top-fav-btn').each(function () {
                    var on = $nav.children('.top-sb-fav[data-link="' + $(this).data('link') + '"]').length > 0;
                    $(this).toggleClass('is-on', on).attr('title', on ? TOP.txt.lepas : TOP.txt.sematkan)
                        .find('i').attr('class', (on ? 'fas' : 'far') + ' fa-star');
                });
            });
        });

        /* mode gelap cepat dari navbar */
        $('#top-tema-toggle').on('click', function (e) {
            e.preventDefault();
            var html = d.documentElement, gelap = !$(html).hasClass('top-dark');
            html.setAttribute('data-tema', gelap ? 'gelap' : 'terang');
            $(html).toggleClass('top-dark', gelap);
            $(this).find('i').attr('class', 'fas ' + (gelap ? 'fa-sun' : 'fa-moon'));
            $.post(TOP.siteUrl + 'Tampilan/simpan', { tema: gelap ? 'gelap' : 'terang' });
        });

        /* tooltip bootstrap */
        if ($.fn.tooltip) $('[data-toggle="tooltip"]').tooltip({ container: 'body' });
    });
})(jQuery, window, document);
