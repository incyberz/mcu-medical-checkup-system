<style>
  .gradasi-merah {
    border: solid 5px #faa
  }
</style>
<?php
echo "<h2>PEMERIKSAAN FISIK</h2>";
$arr = [];
$arr['kepala']['inspeksi'] = [['simetris', 'asimetris'], 'simetris'];
$arr['kepala']['deformitas'] = [['ada', 'tidak ada'], 'tidak ada'];
$arr['kepala']['luka'] = [['ada', 'tidak ada'], 'tidak ada'];
$arr['kepala']['tumor'] = [['ada', 'tidak ada'], 'tidak ada'];

$arr['mata']['sklera'] = [['putih', 'ikterik'], 'putih'];

$arr['hidung']['sekret hidung'] = [['normal', 'bening', 'purulent'], 'normal'];
$arr['hidung']['polip'] = [['positif', 'negatif'], 'negatif'];

$arr['mulut']['faring'] = [['normal', 'hiperemis'], 'normal'];
$arr['mulut']['tonsil kanan'] = [['normal', 'tidak normal'], 'normal'];
$arr['mulut']['tonsil kiri'] = [['normal', 'tidak normal'], 'normal'];
$arr['mulut']['stomatis'] = [['positif', 'negatif'], 'positif'];

$arr['telinga']['serumen prop'] = [['positif', 'negatif'], 'positif'];
$arr['telinga']['gendang telinga'] = [['intak', 'ruftuire'], 'intak'];
$arr['telinga']['reflek cahaya'] = [['positif', 'negatif'], 'positif'];

$arr['leher']['pembesaran kgb'] = [['positif', 'negatif'], 'positif'];
$arr['leher']['kelenjar tiroid'] = [['positif', 'negatif'], 'positif'];

$arr['jantung']['inpeksi ictus cording'] = [['terlihat', 'tidak terlihat'], 'terlihat'];
$arr['jantung']['palpasi ictus cordis'] = [['teraba', 'tidak teraba'], 'teraba'];
$arr['jantung']['perkusi'] = [['teraba', 'tidak teraba'], 'teraba'];
$arr['jantung']['aukultasi lub-lub'] = [['normal', 'tidak normal'], 'normal'];
$arr['jantung']['aukultasi murmur'] = [['negatif', 'positif (abnormal)'], 'negatif'];

$arr['paru']['inpeksi'] = [['sistematis', 'asimetris', 'retraksi'], 'sistematis'];
$arr['paru']['palpasi vocal fremitis'] = [['teraba', 'tidak teraba'], 'teraba'];
$arr['paru']['perkusi'] = [['sonor', 'redup'], 'sonor'];
$arr['paru']['aukultasi'] = [['vesikuler', 'bronchovesikuler (abnormal)'], 'vesikuler'];

$arr['suara tamabahan']['ronci'] = [['negatif', 'positif (abnormal)'], 'negatif'];
$arr['suara tamabahan']['wheezing'] = [['negatif', 'positif (abnormal)'], 'negatif'];

$arr['abdomen']['inpeksi abdomen'] = [['datar', 'cembung'], 'datar'];
$arr['abdomen']['aukultasi bising usus'] = [['3x/menit (normal)', 'hiperpristaltik', 'hipoperistaltik'], '3x/menit (normal)'];
$arr['abdomen']['palpasi liver'] = [['tidak teraba (normal)', 'teraba (ada pembesaran)'], 'tidak teraba (normal)'];
$arr['abdomen']['palpasi limpa'] = [['tidak teraba (normal)', 'teraba (ada pembesaran)'], 'tidak teraba (normal)'];
$arr['abdomen']['perkusi perut'] = [['timpani (normal)', 'abnormal'], 'timpani (normal)'];
$arr['abdomen']['perkusi liver'] = [['pekak (normal)', 'abnormal'], 'pekak (normal)'];

$arr['ginjal']['nyeri ketok'] = [['positif', 'negatif'], 'positif'];

$arr['exzternal atas']['inpeksi pergerakan tangan'] = [['normal', 'abnormal'], 'normal'];
$arr['exzternal atas']['inpeksi kekuatan otot'] = [['normal', 'abnormal'], 'normal'];
$arr['exzternal atas']['palpasi nyeri tekan'] = [['negatif', 'positif'], 'negatif'];
$arr['exzternal atas']['palpasi benjolan/massa'] = [['negatif', 'positif'], 'negatif'];
$arr['exzternal atas']['motorik besar bentuk otot'] = [['normal', 'abnormal'], 'normal'];
$arr['exzternal atas']['motorik keseimbangan'] = [['normal', 'abnormal'], 'normal'];
$arr['exzternal atas']['reflek fisiologis'] = [['normal', 'abnormal'], 'normal'];
$arr['exzternal atas']['sensorik'] = [['normal', 'abnormal'], 'normal'];

$arr['exzternal bawah']['inpeksi pergerakan kaki'] = [['normal', 'abnormal'], 'normal'];
$arr['exzternal bawah']['inpeksi kekuatan otot'] = [['normal', 'abnormal'], 'normal'];
$arr['exzternal bawah']['palpasi nyeri tekan'] = [['negatif', 'positif'], 'negatif'];
$arr['exzternal bawah']['palpasi benjolan/massa'] = [['negatif', 'positif'], 'negatif'];
$arr['exzternal bawah']['motorik besar bentuk otot'] = [['normal', 'abnormal'], 'normal'];
$arr['exzternal bawah']['motorik keseimbangan'] = [['normal', 'abnormal'], 'normal'];
$arr['exzternal bawah']['reflek fisiologis'] = [['normal', 'abnormal'], 'normal'];
$arr['exzternal bawah']['sensorik'] = [['normal', 'abnormal'], 'normal'];

$arr['anus genetalia']['hemokokel'] = [['ada', 'tidak ada'], 'tidak ada'];
$arr['anus genetalia']['hernia'] = [['ada', 'tidak ada'], 'tidak ada'];
$arr['anus genetalia']['romberg'] = [['ada', 'tidak ada'], 'tidak ada'];

$arr['kulit']['warna'] = [['cerah kecoklatan', 'putih', 'hitam kecoklatan'], 'cerah kecoklatan'];
$arr['kulit']['lesi'] = [['negatif', 'positif'], 'negatif'];
$arr['kulit']['kelembapan'] = [['lembab (tidak basah)', 'kulit kering', 'kulit basah'], 'lembab (tidak basah)'];
$arr['kulit']['suhu'] = [['36-37 derajat', '<36 derajat', '>37 derajat'], '36-37 derajat'];
$arr['kulit']['tugor'] = [['<2 detik', '>2 detik'], '<2 detik'];

$arr['rambut']['warna'] = [['hitam', 'pirang', 'putih', 'coklat', 'hitam kecoklatan'], 'hitam'];
$arr['rambut']['tiniea capitis'] = [['negatif', 'positif'], 'negatif'];
$arr['rambut']['tiniea corporis'] = [['negatif', 'positif'], 'negatif'];

$arr['kuku']['warna dasar'] = [['merah muda cerah', 'kebiruan', 'putih polos', 'merah pucat'], 'merah muda cerah'];
$arr['kuku']['capilla refill tine (crt)'] = [['<2 detik', '>2 detik'], '<2 detik'];

$count = count($arr);

$i = 0;
foreach ($arr as $key1 => $value1) {
  $i++;
  $subs = '';
  $Key1 = ucwords($key1);
  $j = 0;
  foreach ($arr[$key1] as $key2 => $value2) {
    // echo $value2[1];
    $j++;
    $pilihans = '';
    $Key2 = ucwords($key2);
    $name = str_replace(' ', '_', $key2 . '__' . $key1);
    $id_default = $name . '__default';
    foreach ($value2[0] as $key3 => $pilihan) {
      $id = $name . '__' . $pilihan;
      $caption = ucwords($pilihan);
      $checked = $pilihan == $value2[1] ? 'checked' : '';
      $pilihans .= radio($name, $id, $pilihan, $caption, $checked, $name);
    }
    $subs .= "
      <div class='wadah bg-white' id=blok_$name>
        <div><div class='f12 abu'>$Key1 </div> $i.$j. $Key2</div>
        $pilihans
        <div><span class='f12 abu'>default:</span> <span id=$id_default>$value2[1]</span></div>
      </div>
    ";
  }
  echo "
      <div class='wadah gradasi-hijau'>
        <div class='flexy flex-between '>
          <div class='mb2 darkblue'>$i. $Key1</div>
          <div class='f12 abu miring'>$i of $count</div>  
        </div>
        $subs
      </div>
    ";
}








?>

<script>
  $(function() {
    $('.opsi_radio').click(function() {
      let name = $(this).prop('name');
      let id = $(this).prop('id');
      let val = $(this).val();

      let def = $('#' + name + '__default').text();
      console.log(name, id, val,
        def);
      if (val == def) {
        $('#blok_' + name).removeClass('gradasi-merah');
        $('#blok_' + name).addClass('bg-white');
      } else {
        $('#blok_' + name).removeClass('bg-white');
        $('#blok_' + name).addClass('gradasi-merah');
      }
    })
  })
</script>