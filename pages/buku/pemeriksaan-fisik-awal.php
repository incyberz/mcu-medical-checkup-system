<?php

echo "<h2>PEMERIKSAAN FISIK AWAL</h2>";
$arr = [
  ['Tekanan Darah', 'mmHg', 'number', 1, 100, 3, 999],
  ['Nadi', 'permenit', 'number', 1, 100, 3, 999],
  ['Pernafasan', 'permenit', 'number', 1, 100, 3, 999],
  ['Suhu', 'celsius', 'number', 20, 45, 3, 999],
  ['Saturasi Oksigen', '', 'number', 40, 100, 3, 999],
  ['IMT', '', 'number', 1, 200, 3, 999],
  ['Lingkar Pinggang', 'cm', 'number', 1, 200, 3, 999],
  ['Tinggi Badan', 'cm', 'number', 100, 200, 3, 999],
  ['Berat Badan', 'kg', 'number', 35, 200, 3, 999],
];

$tr = '';
foreach ($arr as $item) {
  $name = 'rp_' . strtolower($item[0]);
  $name = str_replace(' ', '_', $name);
  $satuan = $item[1] ?? '';
  $tr .= tr_input($item[0], $satuan, $name, $item[0], $item[2], $item[3], $item[4], $item[5], $item[6]);
}
echo "<table class=table>$tr</table>";





?>

<script>
  $(function() {
    $('.opsi_radio').click(function() {
      let name = $(this).prop('name');
      let val = parseInt($(this).val());

      $("#input_" + name).prop('disabled', !val);
      if (!val) {
        $("#input_" + name).val('-');
        // $("#input_" + name).slideUp();
        $("#input_" + name).fadeOut();
      } else {
        // $("#input_" + name).slideDown();
        $("#input_" + name).fadeIn();
      }


      console.log(name, val);

    })
  })
</script>