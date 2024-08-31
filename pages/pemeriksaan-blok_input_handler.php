<?php
# ============================================================
# PENENTUAN BLOK INPUT
# ============================================================
# $arr_input['blok'] : - radio-toolbar
#                      - input-range
#                      - select
# ============================================================
foreach ($arr_input as $key => $v) {

  // khusus MCU by Dokter
  if ($v['bagian']) $v['label'] = "$v[bagian] > $v[label]";

  # ============================================================
  # FILL DATA FROM DB
  # ============================================================
  $id_detail = $v['id'];
  if (array_key_exists($id_detail, $arr_id_detail)) {
    $v['value'] = $arr_id_detail[$id_detail];
  }

  $v['required'] = 'required'; // ZZZ forced to required
  $required = $v['required'] ?? 'required';

  if ($v == 'separator') {
    $blok_sub_input = '';
  } elseif ($v['blok'] == 'input-range') {

    # ============================================================
    # CREATE RANGE
    # ============================================================
    $arr_range = [];
    $jumlah_titik = 8;
    $div_range = '';
    $min_range = $v['minrange'];
    $max_range = $v['maxrange'];
    $durasi_range = $max_range - $min_range;

    $precission = 0;
    $step_range = 1;
    if ($durasi_range < 4) {
      $step_range = 0.01;
      $min_range = round($min_range, 2);
      $precission = 2;
    } elseif ($durasi_range < 8) {
      $step_range = 0.1;
      $min_range = round($min_range, 1);
      $precission = 1;
    } elseif ($durasi_range > 800) {
      $step_range = 10;
    } elseif ($durasi_range > 8000) {
      $step_range = 100;
    }

    for ($i = 0; $i <= $jumlah_titik; $i++) {
      $range_value = round($min_range + ($i * ($durasi_range / $jumlah_titik)), $precission);
      $div_range .= "<div>$range_value</div>";
    }

    $value = $v['value'] ?? '';
    $val_range = $value ? $value : intval(($max_range - $min_range) / 2) + $min_range;
    $step = $v['step'] ?? 1;
    $placeholder = $v['placeholder'] ?? '...';
    $min = $v['min'] ?? '';
    $max = $v['max'] ?? '';
    $class = $v['class'] ?? '';
    $satuan = $v['satuan'] ?? '';



    // $step_range = round($step_range);


    $blok_sub_input = "
      <div class='flexy flex-between' >
        <div class='f10 abu miring'>id: $id_detail</div>
        <div>
          <span class='btn_aksi' id='blok_set_no_data$id_detail" . "__toggle'>$img_delete</span>

          <a href='?manage_pemeriksaan_detail&id_detail=$id_detail' target=_blank onclick='return confirm(`Edit Pertanyaan ini?`)'>
            <img src='assets/img/icon/edit5.png' height=20px>
          </a>

          <div class='wadah gradasi-kuning mt2 hideit' id=blok_set_no_data$id_detail>
            <div class='mb1'>$v[label]</div>
            <span class='btn btn-warning btn_set_no_data' id=btn_set_no_data__$id_detail>Set No Data</span>
            <span class='btn btn-secondary btn_cancel' id=btn_cancel__$id_detail>Cancel</span>
          </div>
        </div>
      </div>

      <div class=hideit id=blok_input_no_data$id_detail>
        $v[label] : <i class='consolas abu'>-- Set to No Data --</i>  
        <span class='pointer darkblue btn_cancel_no_data' id=btn_cancel_no_data__$id_detail>[ Cancel No Data ]</span>
      </div>
      <div id=blok_input$id_detail>
        <div class='flexy flex-center'>
          <div class='f14 darkblue miring pt1'>$v[label]</div>
          <div>
            <input 
              id='$key' 
              name='$key' 
              value='$value' 
              step='$step' 
              placeholder='$placeholder' 
              type='number' 
              $required
              class='form-control mb2 $class' 
              min='$min' 
              max='$max' 
              style='max-width:100px'
            >          
          </div>
          <div class='f14 abu miring pt1'>$satuan</div>
        </div>
        <input type='range' class='form-range range' min='$min_range' max='$max_range' id='range__$key' value='$val_range' step='$step_range'>
        <div class='flexy flex-between f12 consolas abu'>
          $div_range
        </div>
      </div>
    ";
  } elseif ($v['blok'] == 'radio_toolbar' || $v['blok'] == 'radio-toolbar') {
    # ============================================================
    # RADIO TOOLBAR FUNCTION
    # ============================================================
    $blok_sub_input = radio_toolbar2(
      $v['label'],
      $id_detail,
      $v['option_values'],
      $v['option_labels'],
      $v['class'],
      $v['option_class'],
      $v['option_default'],
      $v['value'] // value from DB
    );
  } elseif ($v['blok'] == 'array-gigi') {
    include 'include/array_gigi_function2.php';
  } elseif ($v['blok'] == 'input') {
    $blok_sub_input = 'BLOK INPUT BELUM DITENTUKAN';
    echo div_alert('danger', "Blok [input] dari detail pemeriksaan belum ditentukan. | <a href='?manage_pemeriksaan_detail&id_detail=$id_detail'>Manage</a>");
    exit;
  } else {
    die(div_alert('danger', "Belum ada UI untuk v-blok: <b class=darkblue>$v[blok]</b>. Harap segera lapor developer!"));
  }

  # ============================================================
  # FINAL BLOK INPUT HANDLER
  # ============================================================
  if ($v['blok'] == 'separator' || $v['label'] == 'separator') {
    $blok_inputs .= '<div class="flexy flex-center br10" style="height: 200px; align-items: center; background: #eee; margin: 15px 0"><div class="abu f10 miring" >separator</div></div>';
  } else {
    $blok_inputs .= !$blok_sub_input ? '<hr style="border: solid 5px #ccc; margin:50px 0">' : "
      <div class='wadah gradasi-toska' >
        $blok_sub_input
      </div>  
    ";
  }
}


?>
<script>
  $(function() {
    $('.btn_cancel').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      $('#blok_set_no_data' + id).slideUp();
    });
    $('.btn_set_no_data').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      console.log(id);

      $('#blok_input' + id).slideUp();
      $('#blok_input_no_data' + id).slideDown();
      $('#blok_set_no_data' + id).slideUp();
      $('#' + id).prop('required', false);
      $('#' + id).val('');
    });
    $('.btn_cancel_no_data').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      console.log(id);

      $('#blok_input' + id).slideDown();
      $('#blok_input_no_data' + id).slideUp();
      $('#' + id).prop('required', true);
    });
  })
</script>