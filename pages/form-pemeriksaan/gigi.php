<style>
  .status-gigi {
    width: 35px;
    height: 35px;
  }

  .blok-gigi {
    border: solid 3px #ccf;
    gap: 5px;
  }

  .item-gigi {
    min-width: 42px;
  }

  .item-gigi:hover {
    background: linear-gradient(#ffc, #fcf)
  }
</style>

<?php
$arr = [
  'array_gigi' => [
    'blok' => 'array_gigi',
    'question' => 'Array Gigi',
    'array_gigi' => $mcu['array_gigi'] ?? '',
  ],

];
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
        $(this).addClass('gradasi-kuning');
      } else if (status_gigi == -1) {
        status_gigi = -2;
        $('#simbol_gigi__' + id).html("X")
        $(this).removeClass('gradasi-kuning');
        $(this).addClass('gradasi-merah');
      } else {
        status_gigi = 1;
        $('#simbol_gigi__' + id).html("<i class='bx bx-check'></i>")
        $(this).removeClass('gradasi-merah');
        $(this).addClass('bg-white');
      }
      $('#status_gigi__' + id).val(status_gigi);
    })
  })
</script>