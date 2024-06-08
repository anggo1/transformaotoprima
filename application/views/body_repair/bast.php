<style>
.table.DataTable {
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 10px;
}

table.DataTable td {
    padding: 10px 2px 5px 2px;
}

table.DataTable th {
    padding: 10px 2px 5px 2px;
    font-display: block;
}

input[type="checkbox"] {
    /* Add if not using autoprefixer */
    -webkit-appearance: none;
    /* Remove most all native input styles */
    appearance: none;
    /* For iOS < 15 */
    background-color: var(--form-background);
    /* Not removed via appearance */
    margin: 0;

    font: inherit;
    color: currentColor;
    width: 1.15em;
    height: 1.15em;
    border: 0.15em solid currentColor;
    border-radius: 0.15em;
    transform: translateY(-0.075em);

    display: grid;
    place-content: center;
    outline: max(2px, 0.15em) solid green;
    outline-offset: max(2px, 0.15em);


}

input[type="checkbox"]::before {
    content: "";
    width: 0.65em;
    height: 0.65em;
    clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    transform: scale(0);
    transform-origin: bottom left;
    transition: 120ms transform ease-in-out;
    box-shadow: inset 1em 1em var(--form-control-color);
    /* Windows High Contrast Mode */
    background-color: green;


}

input[type="checkbox"]:checked::before {
    transform: scale(1);
}

input[type="checkbox"]:focus {
    outline: max(2px, 0.15em) solid currentColor;
    outline-offset: max(2px, 0.15em);

}

input-besar,
textarea {
    text-transform: uppercase;
}
</style>


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title text-blue"><span
                                class="ion-soup-can-outline ion-lg text-blue"></span>&nbsp; Proses Berita Acara Serah
                            Terima (BAST)</h3>
                    </div>
                    <div class="modal-body text-sm">
                        <form id="formBast" name="formBast" method="POST">

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tanggal</label>
                                <div class="col-sm-4">
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">

                                        <input type="text" name="tgl_bast" id="tgl_bast"
                                            class="form-control tgl_bast datetimepicker" data-toggle="datetimepicker"
                                            data-target=".tgl_bast" data-format="yyy-mm-dd" required>

                                        <div class="input-group-append" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label class="col-sm-2 col-form-label">Surat Jalan</label>
                                <div class="col-sm-4">
                                    <input type="text" name="no_sj" class="form-control"
                                        placeholder="Nomor Surat Jalan ?">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="No Part" class="col-sm-2 col-form-label">No Body</label>
                                <div class="col-sm-4">
                                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                        <input type="text" name="no_body" value=""
                                            onkeyup="this.value = this.value.toUpperCase();" id="no_part"
                                            class="form-control">
                                        <span class="input-group-append">
                                            <button type="button" class="btn btn-info" data-toggle="modal"
                                                data-target="#modal_form"><i class="glyphicon glyphicon-plus-sign"><i
                                                        class="fa fa-search"></i></button></i>
                                        </span>
                                    </div>
                                </div>
                                <label class="col-sm-2 col-form-label">No Pol</label>
                                <div class="col-sm-4">
                                    <input type="text" name="no_pol" class="form-control"
                                        onkeyup="this.value = this.value.toUpperCase();" placeholder="Nomor Polisi ?">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">NIK Pengemudi</label>
                                <div class="col-sm-4">
                                    <input type="text" name="nip_sp" class="form-control" placeholder="NIP Pengemudi">
                                </div>
                                <label class="col-sm-2 col-form-label">Nama Pengemudi</label>
                                <div class="col-sm-4">
                                    <input type="text" name="nama_sp" class="form-control"
                                        placeholder="Nama Pengemudi ?">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Keterangan</label>
                                <div class="col-sm-4">
                                    <input type="text" name="keterangan" id="keterangan" value="" class="form-control"
                                        placeholder="Keterangan">
                                </div>

                                <label class="col-sm-2 col-form-label">Status</label>
                                <div class="col-sm-4">
                                    <select name="status_bus" class="form-control">
                                        <option value="">Pilih Status ...</option>
                                        <option value="PPU">PPU</option>
                                        <option value="MPU">MPU</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="user" id="user"
                                value="<?php echo $this->session->userdata['full_name']; ?>" class="form-control">
                            <div class="modal-footer center-content-between">
                                <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span>
                                    Simpan</button>
                            </div>
                    </div>
                </div>
                <!-- /.card -->
                <div class="modal fade" id="modal_form" role="dialog">
                    <div class="modal-dialog modal-xm">
                        <div class="modal-content">
                            <div class="modal-body form">
                                <div class="card card-first card-outline">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table no-wrap table-hover nowrap" id="table-body">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>No Body</th>
                                                        <th>No Pol</th>
                                                        <th>Type</th>
                                                        <th>Merk</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot></tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- general form elements -->
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-striped  table-bordered table-hover nowrap" id="list-po">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Body</th>
                                        <th>NoPol</th>
                                        <th>Pengemudi</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="data-bast"></tbody>
                                <tfoot></tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div id="modal-bast"></div>
                </div>

            </div>

            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-blue"><span class="ion-android-alert ion-lg"></span>&nbsp; Detail
                            Berita Acara Serah Terima (BAST)</h3>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-hover nowrap DataTable" id="DataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Perlengkapan</th>
                                    <th>Ket</th>
                                    <th>No</th>
                                    <th>Perlengkapan</th>
                                    <th>Ket</th>
                                    <th>No</th>
                                    <th>Perlengkapan</th>
                                    <th>Ket</th>
                                    <th>No</th>
                                    <th>Perlengkapan</th>
                                    <th>Ket</th>
                                </tr>
                            </thead>
                            <tbody id="data-po">
                                <tr>
                                    <td>1.</td>
                                    <td>Kaca Depan</td>
                                    <td><input type="checkbox" name="ket1" id="ket1" value="1" checked></td>
                                    <td>24.</td>
                                    <td>Lampu Signal depan RH</td>
                                    <td><input type="checkbox" name="ket24" id="ket24" value="1" checked></td>
                                    <td>47.</td>
                                    <td>Unit AC</td>
                                    <td><input type="checkbox" name="ket47" id="ket47" value="1" checked></td>
                                    <td>70.</td>
                                    <td>Kunci Roda &amp; Stang</td>
                                    <td><input type="checkbox" name="ket70" id="ket70" value="1" checked></td>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td>Kaca Kiri</td>
                                    <td><input type="checkbox" name="ket2" id="ket2" value="1" checked></td>
                                    <td>25.</td>
                                    <td>Lampu Signal Samping</td>
                                    <td><input type="checkbox" name="ket25" id="ket25" value="1" checked></td>
                                    <td>48.</td>
                                    <td>Kursi Kernet</td>
                                    <td><input type="checkbox" name="ket48" id="ket48" value="1" checked></td>
                                    <td>71.</td>
                                    <td>Dash Board</td>
                                    <td><input type="checkbox" name="ket71" id="ket71" value="1" checked></td>
                                </tr>
                                <tr>
                                    <td>3.</td>
                                    <td>Kaca Kanan</td>
                                    <td><input type="checkbox" name="ket3" id="ket3" value="1" checked></td>
                                    <td>26.</td>
                                    <td>Lampu Plat Nomor</td>
                                    <td><input type="checkbox" name="ket26" id="ket26" value="1" checked></td>
                                    <td>49.</td>
                                    <td>Speedometer</td>
                                    <td><input type="checkbox" name="ket49" id="ket49" value="1" checked></td>
                                    <td>72.</td>
                                    <td>Sikring Kaca &amp; Batu</td>
                                    <td><input type="checkbox" name="ket72" id="ket72" value="1" checked></td>
                                </tr>
                                <tr>
                                    <td>4.</td>
                                    <td>Kaca Belakang</td>
                                    <td><input type="checkbox" name="ket4" id="ket4" value="1" checked></td>
                                    <td>27.</td>
                                    <td>Kursi Penumpang</td>
                                    <td><input type="checkbox" name="ket27" id="ket27" value="1" checked></td>
                                    <td>50.</td>
                                    <td>Tutup Seat</td>
                                    <td><input type="checkbox" name="ket50" id="ket50" value="1" checked></td>
                                    <td>73.</td>
                                    <td>Radio Tape</td>
                                    <td><input type="checkbox" name="ket73" id="ket73" value="1" checked></td>
                                </tr>
                                <tr>
                                    <td>5.</td>
                                    <td>Spion Kanan</td>
                                    <td><input type="checkbox" name="ket5" id="ket5" value="1" checked></td>
                                    <td>28.</td>
                                    <td>Kursi Pengemudi</td>
                                    <td><input type="checkbox" name="ket28" id="ket28" value="1" checked></td>
                                    <td>51.</td>
                                    <td>Gundu Persneling</td>
                                    <td><input type="checkbox" name="ket51" id="ket51" value="1" checked></td>
                                    <td>74.</td>
                                    <td>Video / CD</td>
                                    <td><input type="checkbox" name="ket74" id="ket74" value="1" checked></td>
                                </tr>
                                <tr>
                                    <td>6.</td>
                                    <td>Spion Kiri</td>
                                    <td><input type="checkbox" name="ket6" id="ket6" value="1" checked></td>
                                    <td>29.</td>
                                    <td>Sabuk Pengaman</td>
                                    <td><input type="checkbox" name="ket29" id="ket29" value="1" checked></td>
                                    <td>52.</td>
                                    <td>Tabung Air Wiper</td>
                                    <td><input type="checkbox" name="ket52" id="ket52" value="1" checked></td>
                                    <td>75.</td>
                                    <td>Kaset Video /CD</td>
                                    <td><input type="checkbox" name="ket75" id="ket75" value="1" checked></td>
                                </tr>
                                <tr>
                                    <td>7.</td>
                                    <td>Kaca Spion Dalam</td>
                                    <td><input type="checkbox" name="ket7" id="ket7" value="1" checked></td>
                                    <td>30.</td>
                                    <td>Footrest</td>
                                    <td><input type="checkbox" name="ket30" id="ket30" value="1" checked></td>
                                    <td>53.</td>
                                    <td>Accu</td>
                                    <td><input type="checkbox" name="ket53" id="ket53" value="1" checked></td>
                                    <td>76.</td>
                                    <td>TV</td>
                                    <td><input type="checkbox" name="ket76" id="ket76" value="1" checked></td>
                                </tr>
                                <tr>
                                    <td>8.</td>
                                    <td height="22">Body Depan</td>
                                    <td><input type="checkbox" name="ket8" id="ket8" value="1" checked></td>
                                    <td>31.</td>
                                    <td>Sarung Jok</td>
                                    <td><input type="checkbox" name="ket31" id="ket31" value="1" checked></td>
                                    <td>54.</td>
                                    <td>Solar,Tangki,Tutup tangki</td>
                                    <td><input type="checkbox" name="ket54" id="ket54" value="1" checked></td>
                                    <td>77.</td>
                                    <td>Remote Control</td>
                                    <td><input type="checkbox" name="ket77" id="ket77" value="1" checked></td>
                                <tr>
                                    <td>9.</td>
                                    <td height="22">Bemper Depan</td>
                                    <td><input type="checkbox" name="ket9" id="ket9" value="1" checked></td>
                                    <td>32.</td>
                                    <td>Gorden</td>
                                    <td><input type="checkbox" name="ket32" id="ket32" value="1" checked></td>
                                    <td>55.</td>
                                    <td>Wheel Dop</td>
                                    <td><input type="checkbox" name="ket55" id="ket55" value="1" checked></td>
                                    <td>78.</td>
                                    <td>Inverter</td>
                                    <td><input type="checkbox" name="ket78" id="ket78" value="1" checked></td>
                                <tr>
                                    <td>10.</td>
                                    <td height="22">Body Kiri</td>
                                    <td><input type="checkbox" name="ket10" id="ket10" value="1" checked></td>
                                    <td>33.</td>
                                    <td>Tempat Sampah</td>
                                    <td><input type="checkbox" name="ket33" id="ket33" value="1" checked></td>
                                    <td>56.</td>
                                    <td>Wiper</td>
                                    <td><input type="checkbox" name="ket56" id="ket56" value="1" checked></td>
                                    <td>79.</td>
                                    <td>Equalizer</td>
                                    <td><input type="checkbox" name="ket79" id="ket79" value="1" checked></td>
                                <tr>
                                    <td>11.</td>
                                    <td height="22">Body Kanan</td>
                                    <td><input type="checkbox" name="ket11" id="ket11" value="1" checked></td>
                                    <td>34.</td>
                                    <td>Smoking Area</td>
                                    <td><input type="checkbox" name="ket34" id="ket34" value="1" checked></td>
                                    <td>57.</td>
                                    <td>Ban Step</td>
                                    <td><input type="checkbox" name="ket57" id="ket57" value="1" checked></td>
                                    <td>80.</td>
                                    <td>Microphone</td>
                                    <td><input type="checkbox" name="ket80" id="ket80" value="1" checked></td>
                                <tr>
                                    <td>12.</td>
                                    <td height="22">Body Belakang</td>
                                    <td><input type="checkbox" name="ket12" id="ket12" value="1" checked></td>
                                    <td>35.</td>
                                    <td>Toilet + Kaca</td>
                                    <td><input type="checkbox" name="ket35" id="ket35" value="1" checked></td>
                                    <td>58.</td>
                                    <td>Engkol Ban</td>
                                    <td><input type="checkbox" name="ket58" id="ket58" value="1" checked></td>
                                    <td>81.</td>
                                    <td>Speaker</td>
                                    <td><input type="checkbox" name="ket81" id="ket81" value="1" checked></td>
                                <tr>
                                    <td>13.</td>
                                    <td height="22">Bemper Belakang</td>
                                    <td><input type="checkbox" name="ket13" id="ket13" value="1" checked></td>
                                    <td>36.</td>
                                    <td>Plafon + Interior</td>
                                    <td><input type="checkbox" name="ket36" id="ket36" value="1" checked></td>
                                    <td>59.</td>
                                    <td>klakson</td>
                                    <td><input type="checkbox" name="ket59" id="ket59" value="1" checked></td>
                                    <td>82.</td>
                                    <td>Power</td>
                                    <td><input type="checkbox" name="ket82" id="ket82" value="1" checked></td>
                                <tr>
                                    <td>14.</td>
                                    <td height="22">Pintu Depan LH</td>
                                    <td><input type="checkbox" name="ket14" id="ket14" value="1" checked></td>
                                    <td>37.</td>
                                    <td>Palu Pemecah Kaca</td>
                                    <td><input type="checkbox" name="ket37" id="ket37" value="1" checked></td>
                                    <td>60.</td>
                                    <td>Knalpot</td>
                                    <td><input type="checkbox" name="ket60" id="ket60" value="1" checked></td>
                                    <td>83.</td>
                                    <td>Subwofer</td>
                                    <td><input type="checkbox" name="ket83" id="ket83" value="1" checked></td>
                                <tr>
                                    <td>15.</td>
                                    <td height="22">Pintu Depan RH</td>
                                    <td><input type="checkbox" name="ket15" id="ket15" value="1" checked></td>
                                    <td>38.</td>
                                    <td>Bagasi Atas</td>
                                    <td><input type="checkbox" name="ket38" id="ket38" value="1" checked></td>
                                    <td>61.</td>
                                    <td>Kompresor</td>
                                    <td><input type="checkbox" name="ket61" id="ket61" value="1" checked></td>
                                    <td>84.</td>
                                    <td>Surat-surat</td>
                                    <td><input type="checkbox" name="ket84" id="ket84" value="1" checked></td>
                                <tr>
                                    <td>16.</td>
                                    <td height="22">Pintu Belakang LH</td>
                                    <td><input type="checkbox" name="ket16" id="ket16" value="1" checked></td>
                                    <td>39.</td>
                                    <td>Lampu Dalam</td>
                                    <td><input type="checkbox" name="ket39" id="ket39" value="1" checked></td>
                                    <td>62.</td>
                                    <td>Altenator</td>
                                    <td><input type="checkbox" name="ket62" id="ket62" value="1" checked></td>
                                    <td>85.</td>
                                    <td>STNK</td>
                                    <td><input type="checkbox" name="ket85" id="ket85" value="1" checked></td>
                                <tr>
                                    <td>17.</td>
                                    <td height="22">Lampu Depan LH</td>
                                    <td><input type="checkbox" name="ket17" id="ket17" value="1" checked></td>
                                    <td>40.</td>
                                    <td>Kotak P3K</td>
                                    <td><input type="checkbox" name="ket40" id="ket40" value="1" checked></td>
                                    <td>63.</td>
                                    <td>Altenator AC</td>
                                    <td><input type="checkbox" name="ket63" id="ket63" value="1" checked></td>
                                    <td>86.</td>
                                    <td>KPS</td>
                                    <td><input type="checkbox" name="ket86" id="ket86" value="1" checked></td>
                                <tr>
                                    <td>18.</td>
                                    <td height="22">Lampu Depan RH</td>
                                    <td><input type="checkbox" name="ket18" id="ket18" value="1" checked></td>
                                    <td>41.</td>
                                    <td>Segitiga Pengaman</td>
                                    <td><input type="checkbox" name="ket41" id="ket41" value="1" checked></td>
                                    <td>64.</td>
                                    <td>Control Panel</td>
                                    <td><input type="checkbox" name="ket64" id="ket64" value="1" checked></td>
                                    <td>87.</td>
                                    <td>KIR</td>
                                    <td><input type="checkbox" name="ket87" id="ket87" value="1" checked></td>
                                <tr>
                                    <td>19.</td>
                                    <td height="22">Lampu Stop Belakang LH</td>
                                    <td><input type="checkbox" name="ket19" id="ket19" value="1" checked></td>
                                    <td>42.</td>
                                    <td>Pewangi Ruangan</td>
                                    <td><input type="checkbox" name="ket42" id="ket42" value="1" checked></td>
                                    <td>65.</td>
                                    <td>Kaper Gembok Kunci</td>
                                    <td><input type="checkbox" name="ket65" id="ket65" value="1" checked></td>
                                    <td>88.</td>
                                    <td>Bintang Mercy</td>
                                    <td><input type="checkbox" name="ket88" id="ket88" value="1" checked></td>
                                <tr>
                                    <td>20.</td>
                                    <td height="22">Lampu Stop Belakang RH</td>
                                    <td><input type="checkbox" name="ket20" id="ket20" value="1" checked></td>
                                    <td>43.</td>
                                    <td>Pewangi Toilet</td>
                                    <td><input type="checkbox" name="ket43" id="ket43" value="1" checked></td>
                                    <td>66.</td>
                                    <td>Stik Oli</td>
                                    <td><input type="checkbox" name="ket66" id="ket66" value="1" checked></td>
                                    <td>89.</td>
                                    <td>Plat Nomor</td>
                                    <td><input type="checkbox" name="ket89" id="ket89" value="1" checked></td>
                                <tr>
                                    <td>21.</td>
                                    <td height="22">Lampu Signal Belakang LH</td>
                                    <td><input type="checkbox" name="ket21" id="ket21" value="1" checked></td>
                                    <td>44.</td>
                                    <td>Bangku Tambahan</td>
                                    <td><input type="checkbox" name="ket44" id="ket44" value="1" checked></td>
                                    <td>67.</td>
                                    <td>Tutup Oli</td>
                                    <td><input type="checkbox" name="ket67" id="ket67" value="1" checked></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                <tr>
                                    <td>22.</td>
                                    <td height="22">Lampu Signal Belakang RH</td>
                                    <td><input type="checkbox" name="ket22" id="ket22" value="1" checked></td>
                                    <td>45.</td>
                                    <td>Pipa Pegangan</td>
                                    <td><input type="checkbox" name="ket45" id="ket45" value="1" checked></td>
                                    <td>68.</td>
                                    <td>Dinamo Wiper</td>
                                    <td><input type="checkbox" name="ket68" id="ket68" value="1" checked></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                <tr>
                                    <td>23.</td>
                                    <td height="22">Lampu Signal Depan LH</td>
                                    <td><input type="checkbox" name="ket23" id="ket23" value="1" checked></td>
                                    <td>46.</td>
                                    <td>Tutup Radiator</td>
                                    <td><input type="checkbox" name="ket46" id="ket46" value="1" checked></td>
                                    <td>69.</td>
                                    <td>Dongkrang &amp; Stang</td>
                                    <td><input type="checkbox" name="ket69" id="ket69" value="1" checked></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </form>

</section>
<?php show_my_confirm('hapusBast', 'hapus-bast', 'Hapus Data Ini?', 'Ya, Hapus Data Ini', 'Batal Hapus data'); ?>

</section><!-- /.modal-content -->
<script type="text/javascript">
$('#tgl_bast,#tgl_awal,#tgl_akhir').datetimepicker({
    format: 'DD-MM-YYYY',
    date: moment()
});
window.onload = function() {
    tampilDetail();
}

var checkbox = document.getElementsByName("lb_kn");
if (checkbox.checked) {

    document.getElementById("lb_kn").value = 0;
    $('#nama').attr('value', '0');
    $(this).attr('checked', true).val(0);
}

function refresh() {
    MyTable = $('#list-po').dataTable();
}
$(document).ready(function() {
    table = $('#table-body').dataTable({
        "responsive": false,
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": false,
        "info": false,
        "processing": true,
        "serverSide": true,
        "pageLength": 5, // Defaults number of rows to display in table
        "order": [],
        "ajax": {
            "url": "<?php echo site_url('Bast/cari_body') ?>",
            "type": "POST"
        },
        "columnDefs": [{
            "targets": [0],
            "orderable": false,
        }, ],
        "dom": '<"top"f>rt<"bottom"lp><"clear">',
        "fnDrawCallback": function() {
            $("input[type                                = 'search']").attr("id", "searchBox");
            $('#table-select').css('cssText', "margin-top: 0px !important;");
            $('#searchBox').css("width", "300px").focus();
            $('#table-select_filter').removeClass('dataTables_filter');
        }
    });
});
var tableBast = $('#list-bast').DataTable({
    "responsive": false,
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "info": false,
    "autoWidth": true,
    "pageLength": 5
});

$('#formBast').submit(function(e) {
    var data = $(this).serialize();

    $.ajax({
            method: 'POST',
            url: '<?php echo base_url('Bast/prosesBast'); ?>',
            data: data
        })
        .done(function(data) {
            var out = jQuery.parseJSON(data);

            if (out.status == 'form') {
                $('.form-msg').html(out.msg);
                effect_msg_form();
            } else {
                tampilDetail();
                document.getElementById("formBast").reset();
                $('.msg').html(out.msg);
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1500
                })
            }
        })

    e.preventDefault();
});

function tampilDetail() {
    $.get('<?php echo base_url('Bast/tampilDetail'); ?>', function(data) {
        tableBast.destroy();
        $('#data-bast').html(data);
        refresh();
    });
}

$(document).on("click", ".cetak-bast", function() {
    var id = $(this).attr("data-id");
    //var id = document.getElementById('next_proses').value=datakode;
    $.ajax({
            method: "POST",
            url: "<?php echo base_url('Bast/cetak'); ?>",
            data: "id=" + id
        })
        .done(function(data) {
            $('#modal-bast').html(data);
            $('#cetak-bast').modal('show');
        })
})
$(document).on("click", ".delete-bast", function() {
    id_lapor = $(this).attr("data-id");
})
$(document).on("click", ".hapus-bast", function() {
    var id = id_lapor;

    $.ajax({
            method: "POST",
            url: "<?php echo base_url('Bast/deleteBast'); ?>",
            data: "id=" + id
        })

        .done(function(data) {
            var out = jQuery.parseJSON(data);
            tampilDetail();
            $('.msg').html(out.msg);
            $('#hapusBast').modal('hide');
            if (out.status != 'form') {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: out.msg,
                    showConfirmButton: false,
                    timer: 1200
                })
            }
        })
})

function selectBody(no_body, no_pol) {
    //var nopol=no_pol;
    //var matches = nopol.match(/[a-zA-Z]/g);
    $('[name = "no_body"]').val(no_body);
    $('[name = "no_pol"]').val(no_pol);
    //document.getElementById('supplier').innerHTML   = data.supplier;


    $('#modal_form').modal('hide');
}
</script>