/*!
 * TOP ERP - helper modul akuntansi (grid rincian, submit AJAX, modal)
 */
(function ($, w) {
    'use strict';
    var ACC = w.ACC = {};

    /* muat konten modal dari URL ke #acc-modal */
    ACC.modal = function (url, data) {
        var $m = $('#acc-modal');
        if (!$m.length) $m = $('<div class="modal fade" id="acc-modal" tabindex="-1" role="dialog"><div class="modal-dialog modal-xl modal-dialog-scrollable" role="document"><div class="modal-content"></div></div></div>').appendTo('body');
        $m.find('.modal-content').html('<div class="p-5 text-center text-muted"><i class="fas fa-circle-notch fa-spin"></i> Memuat...</div>');
        $m.modal('show');
        $.get(url, data || {}).done(function (html) {
            $m.find('.modal-content').html(html);
            ACC.init($m);
            var size = $m.find('[data-modal-size]').data('modal-size');
            $m.find('.modal-dialog').removeClass('modal-sm modal-lg modal-xl').addClass(size ? 'modal-' + size : 'modal-xl');
        }).fail(function (x) {
            if (x.status !== 401) $m.find('.modal-content').html('<div class="p-4 text-danger">Gagal memuat form.</div>');
        });
        return $m;
    };

    /* inisialisasi elemen dalam container (select2, total) */
    ACC.init = function ($c) {
        if ($.fn.select2) {
            $c.find('select.select2').not('.select2-hidden-accessible').each(function () {
                $(this).select2({ theme: 'bootstrap4', width: '100%', dropdownParent: $(this).closest('.modal').length ? $(this).closest('.modal') : $(document.body) });
            });
        }
        $c.find('table.acc-lines').each(function () { ACC.hitung($(this)); });
    };

    /* tambah baris dari <template> */
    $(document).on('click', '[data-add-line]', function (e) {
        e.preventDefault();
        var $t = $($(this).data('add-line')), $tpl = $($t.data('template'));
        ACC.seq = (ACC.seq || 0) + 1;
        var $row = $($tpl.html().replace(/__i__/g, ACC.seq));
        $t.find('tbody').append($row);
        ACC.init($row);
        if (!$(this).data('no-focus')) $row.find('select,input').first().trigger('focus');
    });
    $(document).on('click', '.acc-del-line', function (e) {
        e.preventDefault();
        var $t = $(this).closest('table.acc-lines');
        if ($t.find('tbody tr').length > 1) $(this).closest('tr').remove();
        else $(this).closest('tr').find('input').val('').end().find('select').val('').trigger('change');
        ACC.hitung($t);
    });
    $(document).on('input change', 'table.acc-lines input, [data-acc-recalc]', function () {
        $('table.acc-lines').each(function () { ACC.hitung($(this)); });
    });

    /* hitung total baris: kolom input[name$="[jumlah]"] atau qty*harga */
    ACC.hitung = function ($t) {
        var total = 0;
        $t.find('tbody tr').each(function () {
            var $r = $(this), qty = $r.find('.acc-qty'), harga = $r.find('.acc-harga'), jml = $r.find('.acc-jumlah');
            var v;
            if (harga.length) {
                v = TOP.parse(qty.length ? (qty.val() || 1) : 1) * TOP.parse(harga.val());
                jml.text ? jml.text(TOP.fmt(v, 2)) : null;
            } else {
                v = TOP.parse(jml.val());
            }
            total += v;
        });
        var $f = $($t.data('form'));
        $f.find('.acc-subtotal').text(TOP.fmt(total, 2)).data('nilai', total);
        var disk = TOP.parse($f.find('[name=diskon]').val()), ppnp = TOP.parse($f.find('[name=ppn_persen]').val());
        var dpp = total - disk, ppn = Math.round(dpp * ppnp) / 100;
        $f.find('.acc-ppn').text(TOP.fmt(ppn, 2));
        $f.find('.acc-total').text(TOP.fmt(dpp + ppn, 2));
        return total;
    };

    /* submit form AJAX: <form class="acc-ajax" data-sukses="reload|close"> */
    $(document).on('submit', 'form.acc-ajax', function (e) {
        e.preventDefault();
        var $f = $(this), $b = $f.find('[type=submit]').prop('disabled', true);
        var old = $b.html(); $b.html('<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...');
        $.ajax({ url: $f.attr('action'), method: 'POST', data: $f.serialize(), dataType: 'json' })
            .done(function (r) {
                if (r.status) {
                    toastr.success(r.pesan || 'Berhasil disimpan');
                    $f.closest('.modal').modal('hide');
                    if (r.redirect) { w.location.href = r.redirect; return; }
                    if (r.cetak) w.open(r.cetak, '_blank');
                    setTimeout(function () { w.location.reload(); }, 450);
                } else {
                    toastr.error(r.pesan || 'Gagal menyimpan');
                }
            })
            .fail(function (x) { if (x.status !== 401 && x.status !== 403) toastr.error('Terjadi kesalahan pada server.'); })
            .always(function () { $b.prop('disabled', false).html(old); });
    });

    /* tombol aksi POST dengan konfirmasi: <a data-acc-post="url" data-konfirmasi="..."> */
    $(document).on('click', '[data-acc-post]', function (e) {
        e.preventDefault();
        var $a = $(this), go = function () {
            $.post($a.data('acc-post'), $a.data('param') || {}, null, 'json').done(function (r) {
                if (r.status) { toastr.success(r.pesan || 'Berhasil'); setTimeout(function () { w.location.reload(); }, 450); }
                else toastr.error(r.pesan || 'Gagal');
            });
        };
        if ($a.data('konfirmasi')) TOP.konfirmasi($a.data('konfirmasi'), $a.data('teks') || '', go, $a.data('tombol'));
        else go();
    });
    $(document).on('click', '[data-acc-modal]', function (e) {
        e.preventDefault();
        ACC.modal($(this).data('acc-modal'));
    });

    $(function () {
        if ($.fn.DataTable) {
            $('table.acc-dt').each(function () {
                $(this).DataTable({ pageLength: 25, order: [], responsive: false, autoWidth: false,
                    dom: "<'row align-items-center'<'col-sm-6'l><'col-sm-6 text-right'f>>t<'row align-items-center'<'col-sm-6'i><'col-sm-6'p>>" });
            });
        }
    });
})(jQuery, window);
