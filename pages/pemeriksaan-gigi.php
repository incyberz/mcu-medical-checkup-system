<?php
echo "<h2>PEMERIKSAAN GIGI</h2>";



?>
<div class="wadah">
  UI masih bingung dalam pengisian
</div>

<script>
  $(function() {
    $('.ukuran_pupil').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      console.log(aksi, id);
      $('#ukuran_' + aksi).val(id);
      $('.' + aksi).removeClass('ukuran_pupil_selected');
      $(this).addClass('ukuran_pupil_selected');
    })
  })
</script>