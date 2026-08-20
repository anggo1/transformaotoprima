// 1. Inisialisasi Komponen UI
$('#customer').select2({
    escapeMarkup: function(markup) {
        return markup; 
    },
    templateResult: function(data) {
        if (!data.id) {
            return data.text; 
        }
        var col1 = data.text;
        var col2 = $(data.element).data('details') || '';
        
        return '<div style="display: flex; justify-content: space-between;">' +
                 '<span style="font-weight: bold;">' + col1 + '</span>' +
                 '<span style="color: gray;">' + col2 + '</span>' +
               '</div>';
    }
});

function fn(o) {
    o.value = o.value.toUpperCase().replace(/([^0-9(),-/])/g, '');
}

$('#date_open_wo,#last_service_date,#dead_line').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});

$(function () {
    $('#timepicker,#timepicker2').datetimepicker({
        format: 'H:mm',
        time: moment()
    });
});

// 2. Server-side Datatables Configuration
var table;
$(document).ready(function() {
    table = $("#tabel-appointment").DataTable({
        "dom": "<'row'<'col-sm-3 text-left'l><'col-sm-5 text-center'B><'col-sm-4 text-right'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>",
        "buttons": [
            {
                extend: 'copyHtml5',
                text: '<i class="fas fa-copy"></i> Copy',
                titleAttr: 'Copy',
                title: 'Data Barang',
                className: 'btn btn-sm btn-outline-secondary',
                init: function (api, node, config) { $(node).removeClass('btn-secondary'); }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                titleAttr: 'Excel',
                title: 'Data Barang',
                className: 'btn btn-outline-secondary',
                init: function (api, node, config) { $(node).removeClass('btn-secondary'); }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                titleAttr: 'PDF',
                title: 'Data Barang',
                className: 'btn btn-outline-secondary',
                init: function (api, node, config) { $(node).removeClass('btn-secondary'); }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Cetak',
                titleAttr: 'Print',
                title: 'Data Barang',
                className: 'btn btn-outline-secondary',
                init: function (api, node, config) { $(node).removeClass('btn-secondary'); },
                exportOptions: {
    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] // Array angka kolom ini sempat terhapus sebelumnya
}
            },
            {
                extend: 'colvis',
                text: '<i class="fas fa-eye"></i> Tampilan',
                titleAttr: 'Costum Tampilan',
                className: 'btn btn-outline-secondary',
                init: function (api, node, config) { $(node).removeClass('btn-secondary'); }
            }
        ],
        "responsive": true,
        "paging": true,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "processing": true, 
        "serverSide": true,
        "language": {
            "sEmptyTable": "Data Service Appointment Belum Ada",
            "processing": '<i class="fa fa-spinner fa-spin fa-3x"></i>'
        },
        "order": [],
        "ajax": {
            // Menggunakan konfigurasi URL dari view secara aman
            "url": AppConfig.siteUrl + '/ServiceAppointment/ajax_list',
            "type": "POST"
        },
        "columnDefs": [{
    "targets": [0, 12], // Nilai target ini sempat terhapus sebelumnya dan menyisakan tanda koma
    "orderable": false,
}],
    });
});

// 3. Operasi AJAX (Tambah, Ubah, Hapus)
$('#form-tambah-appointment').submit(function(e) {
    e.preventDefault();
    var data = $(this).serialize();

    $.ajax({
        method: 'POST',
        url: AppConfig.baseUrl + 'ServiceAppointment/prosesTappointment',
        data: data
    })
    .done(function(data) {
        var out = jQuery.parseJSON(data);
        if (out.status == 'form') {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: out.msg,
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            document.getElementById("form-tambah-appointment").reset();
            $('#tambah-appointment').modal('hide');
            $('.msg').html(out.msg);
            table.ajax.reload();
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: out.msg,
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
});

$(document).on("click", ".update-appointment", function() {
    var id = $(this).attr("data-id");

    $.ajax({
        method: "POST",
        url: AppConfig.baseUrl + 'ServiceAppointment/updateAppointment',
        data: { id: id } // Struktur data objek lebih direkomendasikan daripada string
    })
    .done(function(data) {
        $('#tempat-modal').html(data);
        $('#update-appointment').modal('show');
    });
});

// Perbaikan kode yang terpotong di bagian Submit Update
$(document).on('submit', '#form-update-appointment', function(e) {
    e.preventDefault();
    var data = $(this).serialize();

    $.ajax({
        method: 'POST',
        url: AppConfig.baseUrl + 'ServiceAppointment/prosesUappointment',
        data: data
    })
    .done(function(data) {
        var out = jQuery.parseJSON(data);
        if (out.status == 'form') {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: out.msg,
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            $('#update-appointment').modal('hide');
            $('.msg').html(out.msg);
            table.ajax.reload();
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: out.msg,
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
});
