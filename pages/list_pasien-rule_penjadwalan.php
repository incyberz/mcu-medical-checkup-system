<?php
$tanggal_jadwal = $_POST['tanggal_jadwal'] ?? null;
$jumlah_alokasi = $_POST['jumlah_alokasi'] ?? null;
$jam_awal = $_POST['jam_awal'] ?? '07:00:00';
$jam_akhir = $_POST['jam_akhir'] ?? '11:30:00';
$apply_dari = $_POST['apply_dari'] ?? 'belum_terjadwal';
$jumlah_pos = $_POST['jumlah_pos'] ?? '1';

if (isset($_POST['btn_apply'])) {

  // validasi bentrok jadwal
  $awal = "$_POST[tanggal_jadwal] $_POST[jam_awal]";
  $akhir = "$_POST[tanggal_jadwal] $_POST[jam_akhir]";
  $s = "SELECT COUNT(1) as jumlah_bentrok FROM tb_pasien 
  WHERE order_no='$_POST[btn_apply]' 
  AND jadwal >= '$awal'  
  AND jadwal < '$akhir'  
  ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $d = mysqli_fetch_assoc($q);
  $jumlah_bentrok = $d['jumlah_bentrok'];
  if ($jumlah_bentrok) {
    echo div_alert('danger', "Terdapat $jumlah_bentrok pasien yang terjadwal pada jam yang Anda tentukan. Silahkah perbarui jam pendaftaran.");
  } else {
    $jadwal_is_null = $_POST['apply_dari'] == 'belum_terjadwal' ? 'jadwal is null' : 1;

    $s = "SELECT 
    a.id,
    a.nama,
    b.perusahaan,
    (SELECT awal_periksa FROM tb_hasil_pemeriksaan WHERE id_pasien=a.id) awal_periksa
   
    FROM tb_pasien a 
    JOIN tb_order b ON a.order_no=b.order_no 
    WHERE a.order_no = '$_POST[btn_apply]' 
    AND $jadwal_is_null
    ";

    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    $jumlah_alokasi = $_POST['jumlah_alokasi'];
    $jumlah_pos = $_POST['jumlah_pos'];
    $jam_awal = $_POST['jam_awal'];
    $speed = $_POST['speed'];
    $alokasi_ke = 0;
    $loket = 1;
    while ($d = mysqli_fetch_assoc($q)) {
      if (!$d['awal_periksa']) {

        $alokasi_ke++;
        $jadwal = date('Y-m-d H:i:s', strtotime($awal) + (($alokasi_ke - $loket) * $speed) / $jumlah_pos);
        $pair_loket = $jumlah_pos > 1 ? ", loket=$loket" : '';
        $s2 = "UPDATE tb_pasien SET jadwal='$jadwal' $pair_loket where id=$d[id]";
        echolog("$alokasi_ke. Updating jadwal [ $jadwal$pair_loket ] for [ $d[nama] | $d[perusahaan] ]");

        $loket++;
        if ($loket > $jumlah_pos) {
          $loket = 1;
        }
        $q2 = mysqli_query($cn, $s2) or die(mysqli_error($cn));
        // echo "<br>$s2";
        if ($alokasi_ke >= $jumlah_alokasi) break;
      }
    }
    jsurl("?list_pasien&order_no=$_POST[btn_apply]");
    exit;
  }
}

$arr = [
  'belum_diperiksa' => "$jumlah_belum_periksa pasien yang belum diperiksa",
  'belum_terjadwal' => "$jumlah_belum_terjadwal pasien yang belum terjadwal saja"
];
$opt_apply_dari = '';
foreach ($arr as $key => $value) {
  $selected = $apply_dari == $key ? 'selected' : '';
  $opt_apply_dari .= "<option value=$key $selected>$value</option>";
}

$arr = [
  'belum_diperiksa' => "$jumlah_belum_periksa pasien yang belum diperiksa",
  'belum_terjadwal' => "$jumlah_belum_terjadwal pasien yang belum terjadwal saja"
];
$opt_jumlah_pos = '';
for ($i = 1; $i <= 10; $i++) {
  $selected = $jumlah_pos == $i ? 'selected' : '';
  $opt_jumlah_pos .= "<option $selected>$i</option>";
}


# ============================================================
# FINAL ECHO
# ============================================================
echo "
  <style>.label{width:200px}</style>
  <form method=post class='wadah gradasi-hijau'>
    <h3 class='f20 mt2 mb2 darkblue tengah'>Rule Penjadwalan</h3>

    <div class='flexy mb2 wadah'>
      <div class='abu miring f14 label'>Apply dari</div>
      <div>
        <select class='form-control' name=apply_dari>$opt_apply_dari</select>
      </div>
    </div>


    <div class='flexy mb2 wadah'>
      <div class='abu miring f14 label'>Untuk tanggal</div>
      <div>
        <input required type=date min='$today' value='$today' class='form-control' name=tanggal_jadwal id=tanggal_jadwal value='$tanggal_jadwal' >
      </div>
      <div>
        <input required type=time min='07:00:00' max='22:00:00' class='form-control triger_hitung' name=jam_awal id=jam_awal value='$jam_awal'>
      </div>
      <div>s.d</div>
      <div>
        <input required type=time min='07:00:00' max='22:00:00' class='form-control triger_hitung' name=jam_akhir id=jam_akhir value='$jam_akhir'>
      </div>
    </div>

    <div class='flexy mb2 wadah'>
      <div class='abu miring f14 label'>Alokasikan waktu untuk </div>
      <div>
        <input required type=number min=10 max=$jumlah_belum_periksa class='form-control triger_hitung' name=jumlah_alokasi id=jumlah_alokasi value=$jumlah_alokasi placeholder='...'>
      </div>
      <div>pasien</div>
    </div>

    <div class='flexy mb2 wadah'>
      <div class='abu miring f14 label'>Jumlah Pos Pendaftaran </div>
      <div>
        <select class='form-control' name=jumlah_pos id=jumlah_pos>$opt_jumlah_pos</select>
      </div>
      <div class='abu miring f14 label'>loket </div>
    </div>

    <div class=wadah>
      <div class='flexy mb2' style='align-items:center'>
        <div class='abu miring f14 label'>Estimasi waktu pendaftaran </div>
        <div>
          <span class='f40' id=speed_show>1.2</span>
          <input type=hidden class='bg-red' name=speed id=speed>
        </div>
        <div>per pasien</div>
      </div>

      <div class='flexy mb2' style='align-items:center'>
        <div class='abu miring f14 label'>&nbsp;</div>
        <div>
          <button class='btn btn-success hideit' name=btn_apply id=btn_apply value='$order_no' onclick='return confirm(`Apply?`)'>Apply Penjadwalan</button>
        </div>
      </div>
    </div>


  </form>
";
?>
<script>
  $(function() {
    let speed = 0;
    let speed_show = '?';

    $('#jumlah_pos').change(function() {
      $('.triger_hitung').keyup();
    });
    $('.triger_hitung').keyup(function() {
      let jam_awal = $('#jam_awal').val();
      let jam_akhir = $('#jam_akhir').val();
      // let jumlah_alokasi = parseInt($('#jumlah_alokasi').val());
      let jumlah_alokasi = $('#jumlah_alokasi').val();
      let jumlah_pos = $('#jumlah_pos').val();
      let tanggal_jadwal = $('#tanggal_jadwal').val();

      let d1 = new Date(tanggal_jadwal + ' ' + jam_awal);
      let d2 = new Date(tanggal_jadwal + ' ' + jam_akhir);

      let t1 = d1.getTime();
      let t2 = d2.getTime();
      let selisih = parseInt((t2 - t1) / 1000);

      // console.log(selisih, jumlah_alokasi);

      if (selisih > 0) {
        // if(jam_awal)
        if (jumlah_alokasi) {
          speed = Math.round((selisih / jumlah_alokasi) * jumlah_pos);
          if (speed < 60) {
            speed_show = speed + ' detik';
          } else {
            speed_show = parseInt(speed / 60) + ' menit ';
            let sisa_detik = speed % 60;
            if (sisa_detik) speed_show += sisa_detik + ' detik';
          }
          $('#btn_apply').slideDown();

        }

      } else {
        speed_show = '?';
        $('#btn_apply').slideUp();
      }
      $('#speed').val(speed);
      $('#speed_show').text(speed_show);
    });
    $('.triger_hitung').keyup();
  })
</script>