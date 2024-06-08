<div class="card">
    <div class="modal-content">
        <div class="card-body">

            <div class="col-12 ">
                <div class="table-responsive">
                    <table width="100%" class="table table-bordered table-hover nowrap" id="list-data">
                        <thead>
                            <tr>
                                <th>Tgl Keluar</th>
                                <th>No Ref</th>
                                <th>No PK</th>
                                <th>No Body</th>
                                <th>Proses</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>QTY</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php 
    ?>
    <script type="text/javascript">
    //var out = jQuery.parseJSON(hasil);
    //var model = out.detailKeluar;
    var model = [{
        "tgl_keluar": "2023-06-02",
        "id_keluar": "PPB-23060007",
        "no_pk": "PK20230521006",
        "no_body": "RKJ229",
        "ket_pk": "BODY",
        "no_part": "BBS0008",
        "nama_part": "Besi Holo 40X60 Tb.3,0mm @3,0mm",
        "jumlah": "1"
    }, {
        "tgl_keluar": "2023-06-02",
        "id_keluar": "PPB-23060007",
        "no_pk": "PK20230521006",
        "no_body": "RKJ229",
        "ket_pk": "BODY",
        "no_part": "BBS0007",
        "nama_part": "Besi Holo 40X40 Tb.2,3mm @1,9mm",
        "jumlah": "1"
    }, {
        "tgl_keluar": "2023-06-02",
        "id_keluar": "PPB-23060017",
        "no_pk": "PK20230521006",
        "no_body": "RKJ229",
        "ket_pk": "BODY",
        "no_part": "PLL0036",
        "nama_part": "Sikalube 227 Uk 360ml",
        "jumlah": "1"
    }];
    //var model=[{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060007","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"BBS0008","nama_part":"Besi Holo 40X60 Tb.3,0mm @3,0mm","jumlah":"1"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060007","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"BBS0007","nama_part":"Besi Holo 40X40 Tb.2,3mm @1,9mm","jumlah":"1"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060017","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"PLL0036","nama_part":"Sikalube 227 Uk 360ml","jumlah":"1"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060017","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"ISK0007","nama_part":"Sekrup FH 8X3\/4","jumlah":"50"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060018","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"ISK0005","nama_part":"Sekrup FH 8X1","jumlah":"100"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060019","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"BST0002","nama_part":"Gaspring Stabilus 60cm 750N Bagasi","jumlah":"6"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060019","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"IKN0007","nama_part":"Kunci Bagasi Mekanik RH","jumlah":"3"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060019","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"IKN0006","nama_part":"Kunci Bagasi Mekanik LH","jumlah":"3"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060019","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"BBD0002","nama_part":"Baud M6X15","jumlah":"15"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060019","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"BES0011","nama_part":"Capit Udang","jumlah":"1"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060019","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"BES0001","nama_part":"Engsel Bushing AS 19","jumlah":"4"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060020","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"IKN0008","nama_part":"Kunci Mekanik T","jumlah":"4"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060020","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"BBD0009","nama_part":"Baud M8X25 Kuning","jumlah":"15"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060020","no_pk":"PK20230521006","no_body":"RKJ229","ket_pk":"BODY","no_part":"BBD0030","nama_part":"RING M8","jumlah":"15"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060015","no_pk":"PK20230521007","no_body":"RKJ660","ket_pk":"BODY","no_part":"BBS0003","nama_part":"Besi Holo 15X30 Tb.2,0mm @1,6mm","jumlah":"1"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060009","no_pk":"PK20230521010","no_body":"RKJ267","ket_pk":"CAT","no_part":"CAP0002","nama_part":"Ampelas Niken NO120","jumlah":"5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060009","no_pk":"PK20230521010","no_body":"RKJ267","ket_pk":"CAT","no_part":"CAP0004","nama_part":"Ampelas Niken NO240","jumlah":"5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060009","no_pk":"PK20230521010","no_body":"RKJ267","ket_pk":"CAT","no_part":"CAP0005","nama_part":"Ampelas Niken NO400","jumlah":"5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060014","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"IMK0003","nama_part":"Lis Mika Cover Lampu Plafon UP","jumlah":"3"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060014","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"ILM0001","nama_part":"Lem Doubletape 3M","jumlah":"5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060014","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"ISK0006","nama_part":"Sekrup FH 8X2","jumlah":"50"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060014","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"ISK0018","nama_part":"Sekrup PH 8X1 Kuning","jumlah":"50"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060014","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"IKR0018","nama_part":"Karet Spon T10","jumlah":"1"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060016","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"BES0013","nama_part":"Engsel Pintu Area 3\"X2,5\"X2,5\"","jumlah":"1"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060016","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"IKN0001","nama_part":"Door Closer","jumlah":"1"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060016","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"ISK0018","nama_part":"Sekrup PH 8X1 Kuning","jumlah":"50"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060016","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"ISK0005","nama_part":"Sekrup FH 8X1","jumlah":"50"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060024","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"CTN0001","nama_part":"Thiner SWD ND @200L","jumlah":".5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060026","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"IKA0004","nama_part":"Kain Inlet \/ Kasa Nyamuk","jumlah":"1.5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060026","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"EKB0001","nama_part":"Kabel Tis NO 150","jumlah":"12"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060026","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"ISK0006","nama_part":"Sekrup FH 8X2","jumlah":"50"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060026","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"IAB0005","nama_part":"Gordeng Holder","jumlah":"12"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060026","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"IGO0001","nama_part":"Roda Gordeng","jumlah":"120"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060027","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"EKB0021","nama_part":"Kabel General @2.00mm Hitam","jumlah":"18.5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060027","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"EKB0026","nama_part":"Kabel General @1.50mm Hitam","jumlah":"12.5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060027","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"EKB0011","nama_part":"Kabel General @0.85mm Biru","jumlah":"17.5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060027","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"EKB0007","nama_part":"Kabel General @0.85mm Putih","jumlah":"5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060027","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"EKB0014","nama_part":"Kabel General @0.85mm Ungu","jumlah":"2"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060028","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"EKB0008","nama_part":"Kabel General @0.85mm Merah","jumlah":"7.5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060028","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"EKB0013","nama_part":"Kabel General @0.85mm Pink","jumlah":"3.5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060028","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"EKB0009","nama_part":"Kabel General @0.85mm Kuning","jumlah":"5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060028","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"EKB0012","nama_part":"Kabel General @0.85mm Orange","jumlah":"17.5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060028","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"ILM0009","nama_part":"Solasiban Hitam","jumlah":"2"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060030","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"IKC0009","nama_part":"Kaca Jetliner Pnt Dpn A 42X62 R\/L ARH","jumlah":"2"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060030","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"IKC0010","nama_part":"Kaca Jetliner Pnt Dpn 129X90 RH ARH","jumlah":"1"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060030","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"IKC0033","nama_part":"Kaca Jetliner Pnt Dpn 128X79 LH ARH","jumlah":"1"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060030","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"IKC0089","nama_part":"Kaca Jetliner Pnt Blk 46x74.5 ARH","jumlah":"1"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060030","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"IKC0088","nama_part":"Kaca Jetliner Pnt Blk Ats 36x84.5 ARH","jumlah":"1"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060030","no_pk":"PK20230521016","no_body":"RKJ180","ket_pk":"INTERIOR","no_part":"IKC0011","nama_part":"Kaca Jetliner Blk 73X202.5 PP","jumlah":"1"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060010","no_pk":"PK20230522001","no_body":"RKJ322","ket_pk":"CAT","no_part":"CAP0001","nama_part":"Ampelas Niken NO80","jumlah":"5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060010","no_pk":"PK20230522001","no_body":"RKJ322","ket_pk":"CAT","no_part":"CAP0002","nama_part":"Ampelas Niken NO120","jumlah":"5"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060010","no_pk":"PK20230522001","no_body":"RKJ322","ket_pk":"CAT","no_part":"PGR0001","nama_part":"Mata Grinda Ampelas 4","jumlah":"8"},{"tgl_keluar":"2023-06-02","id_keluar":"PPB-23060005","no_pk":"PK20230522005","no_body":"RKJ399","ket_pk":"INTERIOR","no_part":"ISL0010","nama_part":"Silikon Renz 600ml 43 Grey","jumlah":"2"}];

    var dataGroup;

    $(document).ready(function() {
        tampilGroup(model);
    });

    function tampilGroup(model) {
        dataGroup = $('#list-data').DataTable({
            //"bRetrieve": true,
            "sPaginationType": "full_numbers",
            paging: true,
            //"bProcessing": true,
            //"bAutoWidth": false,
            //"bStateSave": true,
            "aaSorting": [
                [1, 'asc']
            ],
            "aaData": model,
            rowsGroup: [1, 2, 3,4],
            "aoColumns": [{
                    "data": "tgl_keluar",
                    sDefaultContent: ""
                },
                {
                    "data": "id_keluar",
                    sDefaultContent: ""
                },
                {
                    "data": "no_pk",
                    sDefaultContent: ""
                },
                {
                    "data": "no_body",
                    sDefaultContent: ""
                },
                {
                    "data": "ket_pk",
                    sDefaultContent: ""
                },
                {
                    "data": "no_part",
                    sDefaultContent: ""
                },
                {
                    "data": "nama_part",
                    sDefaultContent: ""
                },
                {
                    "data": "jumlah",
                    sDefaultContent: ""
                }
            ]
        });
    }
    </script>