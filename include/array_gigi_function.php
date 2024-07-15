<?php
function array_gigi(
  $id_detail,
  $label,
  $value_from_db = null,
  $label_class = null,
  $wrapper_class = null,
  $global_class = null,

) {

  $value_default = '1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,';
  $value_default = $value_from_db ? $value_from_db : $value_default;

  if ($value_default) {
    $arr_status_gigi = explode(',', $value_default);
  } else {
    for ($i = 0; $i < 32; $i++) {
      $arr_status_gigi[$i] = 1; // gigi OK
    }
  }

  $gigi['atas_kiri'] = [18,  17,  16,  15,  14,  13,  12,  11];
  $gigi['atas_kanan'] = [21,  22,  23,  24,  25,  26,  27,  28];
  $gigi['bawah_kiri'] = [48,  47,  46,  45,  44,  43,  42,  41];
  $gigi['bawah_kanan'] = [31,  32,  33,  34,  35,  36,  37,  38];

  $divs = '';
  $div_gigi = [];
  $i = 0;
  foreach ($gigi as $posisi => $arrg) {
    $div_gigi[$posisi] = '';
    foreach ($arrg as $kode_gigi) {
      $status_gigi = $arr_status_gigi[$i];
      $bg = 'bg-white';
      if ($status_gigi == -1) {
        $bg = 'bg-yellow';
      } elseif ($status_gigi == -2) {
        $bg = 'bg-red';
      }
      $div_gigi[$posisi] .= "
        <div class='$bg p1 bordered br5 item-gigi pointer' id=item-gigi__$kode_gigi>
          <div class='f12'>$kode_gigi</div>
          <input 
            class='debug bg-red f9 status_gigi' 
            id='status_gigi__$kode_gigi' 
            value=$status_gigi 
            style='width:20px'
          />
          <div class='f30' id='simbol_gigi__$kode_gigi' style='min-width:30px'>
            <i class='bx bx-check'></i>
          </div>
        </div>
      ";
      $i++; // next gigi
    }

    $divs .= "
      <div class='col-6 mb4'>
        <div class='blok-gigi p2 pl2 pr2 flexy flex-between br5'>
          $div_gigi[$posisi]
        </div>
      </div>
    ";
  }

  return "
    <div class='$global_class'>
      <div class='f10 miring abu flexy flex-between'>
        <div>id. $id_detail</div>
      </div>
      <label class='darkblue mb2 $label_class'>$label</label>
      <input id=array_gigi name=$id_detail class='form-control mb4 consolas f14 abu tengah' value='$value_default' readonly>
      <div class='radio-toolbar $wrapper_class'>
        <div class='row'>
          $divs
        </div>
      </div>
    </div>
    <div class='wadah f12 abu'>
      <div class='left'>Keterangan:</div>
      <div class='flexy flex-between'>
        <div>
          <i class='bx bx-check f20'></i> GIGI NORMAL
        </div>
        <div>
          <span class=' f20'>O</span> CARIES
        </div>
        <div>
          <span class=' f20'>X</span> ZZZ
        </div>
        <div>
          &nbsp;
        </div>
      </div>
      
    </div>
  ";
}
?>
<script>
  $(function() {

    $('.item-gigi').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      let status_gigi = parseInt($('#status_gigi__' + id).val());
      console.log(aksi, id, status_gigi);
      if (status_gigi == 1) {
        status_gigi = -1;
        // $('#simbol_gigi__' + id).html("<i class='bx bx-cross'></i>")
        $('#simbol_gigi__' + id).html("O")
        $(this).removeClass('bg-white');
        $(this).addClass('bg-yellow');
      } else if (status_gigi == -1) {
        status_gigi = -2;
        $('#simbol_gigi__' + id).html("X")
        $(this).removeClass('bg-yellow');
        $(this).addClass('bg-red');
      } else {
        status_gigi = 1;
        $('#simbol_gigi__' + id).html("<i class='bx bx-check'></i>")
        $(this).removeClass('bg-red');
        $(this).addClass('bg-white');
      }
      $('#status_gigi__' + id).val(status_gigi);

      let z = document.getElementsByClassName('status_gigi');
      let array_gigi = '';
      for (let i = 0; i < z.length; i++) {
        array_gigi += z[i].value + ',';
      }
      $('#array_gigi').val(array_gigi);
    })
  })
</script>