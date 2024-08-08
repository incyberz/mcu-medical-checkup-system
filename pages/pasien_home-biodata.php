<?php


# ============================================================
# PROCESSORS
# ============================================================
if (isset($_POST['btn_update_biodata'])) {
  // echo '<pre>';
  // var_dump($_POST);
  // echo '</pre>';
  // exit;
  $id_pasien = $_POST['btn_update_biodata'];
  $nikepeg_or_null = strlen($_POST['nikepeg']) > 2 ? "'$_POST[nikepeg]'" : 'NULL';
  $s = "UPDATE tb_pasien SET
    nama='$_POST[nama_pasien]',
    gender='$_POST[gender]',
    tanggal_lahir='$_POST[tanggal_lahir]',
    usia='$_POST[usia]',
    kode_kec='$_POST[kode_kec]',
    no_ktp='$_POST[no_ktp]',
    nikepeg=$nikepeg_or_null,
    status=2 -- Update Biodata
  WHERE id=$id_pasien 
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));

  echo div_alert('success', 'Update biodata pasien berhasil.');
  jsurl('', 1000);
}













# ============================================================
# NORMAL FLOW
# ============================================================
$type_btn_biodata = 'secondary';
$notif_btn_biodata = "<div class='tengah f12 abu mt1' id=btn_update_biodata_info>Last update biodata: $last_update_show</div>";
$hide_form = '';
$ubah_biodata = '';
$my_biodata = '';

if ($status >= 1) {
  $belum_input = '<i class="red bold">belum input Nomor KTP</i>';
  $no_ktp_show = $no_ktp ? $no_ktp : $belum_input;
  $usia_show = $no_ktp ? "$usia tahun" : $belum_input;
  if ($gender) {
    $gender_show = strtolower($gender) == 'l' ? 'Laki-laki' : 'Perempuan';
  } else {
    $gender_show = $belum_input;
  }
  $my_biodata = "
    <table class='table table-hover td_trans'>
      <tr>
        <td class='kolom f12'>Nama</td>
        <td>$nama_user</td>
      </tr>
      <tr>
        <td class='kolom f12'>Nomor KTP</td>
        <td>$no_ktp_show</td>
      </tr>
      <tr>
        <td class='kolom f12'>N.I.Karyawan</td>
        <td>$nikepeg_or_strip</td>
      </tr>
      <tr>
        <td class='kolom f12'>Gender</td>
        <td>$gender_show</td>
      </tr>
      <tr>
        <td class='kolom f12'>Usia</td>
        <td>$usia_show</td>
      </tr>
    </table>
  ";
  $hide_form = 'hideit';
  $btn_primary = $no_ktp ? '' : 'btn btn-primary';
  $ubah_biodata = "<div class='tengah'><span class='btn_aksi darkblue f14 $btn_primary' id=form_biodata__toggle>Ubah Biodata</div>";
  $type_btn_biodata = 'primary';
  $notif_btn_biodata = "<div class='tengah f12 blue mt1 tebal' id=btn_update_biodata_info>Silahkan update biodata Anda agar dapat melihat Jadwal Medical Checkup</div>";
}

$blok_biodata = "
  <div class='card mb4 gradasi-hijau'>
    <div class='card-body'>
      <h3>Biodata Anda</h3>
      $my_biodata
      $ubah_biodata
      <form method=post class='$hide_form' id=form_biodata>
        <input type=hidden name=gender id=gender>
        <input type=hidden name=tanggal_lahir id=tanggal_lahir>
        <input type=hidden name=usia id=usia>
        <input type=hidden name=kode_kec id=kode_kec>
        <table class='table td_trans'>
          <tr><td colspan=100%><input class='form-control tengah ' value='$d[nama]' name=nama_pasien id=nama_pasien placeholder='Nama Lengkap Anda...' /></td></tr>

          <tr>
            <td colspan=100%>
              <div class='tengah blue mt2 mb2'>Isilah input nomor KTP disini dan tunjukanlah KTP asli Anda saat di Bagian Registrasi</div>
              <div class=tengah><img src='$lokasi_img/lihat_ktp.jpg' class='img-thumbnail img-fluid'></div>
              <div id=nik_divide class='nomor biru tengah mb2 mt4'></div>
              <input required minlength=16 maxlength=16 class='form-control tengah nomor' value='$d[no_ktp]' placeholder='Nomor KTP' name=no_ktp id=no_ktp auto-fill-form=false />

              <div class='f12 abu mt1 tengah' id=auto_bio></div>
              <div class='f12 abu mt1 tengah' id=info_digit>Anda mengetik <span id=digit_no_ktp>0</span> dari 16 digit KTP</div>
            </td>
          </tr>

          <tr>
            <td colspan=100%>
              <input required class='form-control tengah nomor' minlength=1 maxlength=16 value='$nikepeg' placeholder='Nomor Induk Karyawan' name=nikepeg id=nikepeg />
              <div class='f12 abu mt1 tengah'>Anda mengetik <span id=digit_nikepeg>0</span> digit No Induk Karyawan<br>Berikan tanda strip jika tidak punya</div>
            </td>
          </tr>

        </table>
        <button class='btn btn-$type_btn_biodata w-100' name=btn_update_biodata id=btn_update_biodata value=$id_pasien>Update Biodata</button>
        $notif_btn_biodata
      </form>
    </div>
  </div>
";


























?>
<script>
  $(document).ready(function() {
    $("#no_ktp").keyup(function() {
      let no_ktp = $(this).val();

      $(this).val(
        no_ktp.replace(/[^0-9]/g, '')
      );

      if (no_ktp.length != 16) {
        $("#btn_update_biodata").prop('disabled', 1);
        $("#nik_ket").show();
        $("#nik_divide").slideDown();
        $("#auto_bio").hide();
        $("#info_digit").fadeIn();
        $("#nik_divide").text(
          no_ktp.substring(0, 4) +
          "-" +
          no_ktp.substring(4, 8) +
          "-" +
          no_ktp.substring(8, 12) +
          "-" +
          no_ktp.substring(12, 16)
        );
        // $("#nama_kec").text("");
        // $("#nama_kab").text("");
        // $("#nama_prov").text("");
      } else {
        $("#nik_ket").hide();
        $("#auto_bio").fadeIn();
        $("#nik_divide").slideUp();
        $("#info_digit").fadeOut();


        let err_nik = 0;

        let prv = no_ktp.substring(0, 2);
        let kab = no_ktp.substring(2, 4);
        let kec = no_ktp.substring(4, 6);
        let tgl = no_ktp.substring(6, 8);
        let bln = no_ktp.substring(8, 10);
        let thn = no_ktp.substring(10, 12);
        let nur = no_ktp.substring(12, 16); //no_urut

        // =======================================================================
        // CEK FORMAT Nomor KTP
        // =======================================================================
        if (parseInt(prv) < 11) err_nik = 1;
        if (parseInt(kab) == 0) err_nik = 1;
        if (parseInt(kec) == 0) err_nik = 1;
        if (parseInt(tgl) == 0) err_nik = 1;
        if (parseInt(bln) == 0) err_nik = 1;
        if (parseInt(nur) == 0) err_nik = 1;

        if (parseInt(tgl) > 71 || (parseInt(tgl) > 31 && parseInt(tgl) < 41))
          err_nik = 1;
        if (parseInt(bln) > 12) err_nik = 1;
        if (parseInt(thn) > 10 && parseInt(thn) < 60) err_nik = 1;

        if (err_nik) {
          $("#auto_bio").show();
          $("#auto_bio").html(
            "<span class=red>Format Nomor KTP yang Anda masukan tidak tepat. Silahkan lihat pada KTP/KK Anda.</span>"
          );
          // setview_input(id,0);
          return;
        } else {
          $("#btn_update_biodata").prop('disabled', 0);
        }
        // =======================================================================
        // CEK FORMAT Nomor KTP
        // =======================================================================

        let gender = "laki-laki";
        let true_tgl = parseInt(tgl);
        if (parseInt(tgl) > 40) {
          gender = "perempuan";
          true_tgl = parseInt(tgl) - 40;
        }

        let nama_bulan = [
          "",
          "Januari",
          "Februari",
          "Maret",
          "April",
          "Mei",
          "Juni",
          "Juli",
          "Agustus",
          "September",
          "Oktober",
          "November",
          "Desember",
        ];

        let tahun = "";
        if (parseInt(thn) < 50) tahun = "20" + thn;
        if (parseInt(thn) >= 50) tahun = "19" + thn;

        let tanggal_lahir = tahun + "-" + bln + "-" + true_tgl;
        let tanggal_lahir_show = true_tgl + " " + nama_bulan[parseInt(bln)] + " " + tahun;

        // $("#ttl_tanggal").val(true_tgl).change();
        // $("#ttl_bulan").val(parseInt(bln)).change();
        // $("#ttl_tahun").val(parseInt(tahun)).change();

        let today = new Date();
        let birthday = new Date(bln + "/" + true_tgl + "/" + tahun);

        let ageDifMs = Date.now() - birthday.getTime();
        let ageDate = new Date(ageDifMs); // miliseconds from epoch
        let usia = Math.abs(ageDate.getUTCFullYear() - 1970);

        $("#auto_bio").html("<div class='tebal green'>Anda " + gender + ", " + "lahir " + tanggal_lahir_show + ", " + "usia " + usia + " tahun</div>");
        $("#gender").val(gender);
        $("#usia").val(usia);
        $("#tanggal_lahir").val(tanggal_lahir);
        $("#kode_kec").val(no_ktp.substring(0, 6));

        let link_ajax = "ajax/get_nama_daerah_by_nik.php?no_ktp=" + no_ktp;

        $.ajax({
          url: link_ajax,
          success: function(a) {
            if (a.substring(0, 3) == "1__") {
              // alert("Sukses, a: "+a);
              let z = a.split("__");
              let ra = z[1].split(";");
              let nama_kec = ra[0];
              let nama_kab = ra[1];
              let nama_prov = ra[2];
              let kode_pos = ra[3];

              $("#auto_bio").html(
                $("#auto_bio").html() +
                "<div class='tebal green'>dari Kecamatan " + nama_kec +
                ", " + nama_kab + ", " + nama_prov + "</div>"
              );

            } else {
              console.log(a);
              $("#auto_bio").html(
                $("#auto_bio").html() +
                "<div class='red'>Lokasi tidak ditemukan.</div>"
              );
            }
          },
        });
      }
    });
  });
</script>