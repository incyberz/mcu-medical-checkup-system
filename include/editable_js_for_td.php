<script>
  $(function() {
    let old_val = '';
    $(".editable").click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let kolom = rid[0];
      let tabel = rid[1];
      let id = rid[2];
      console.log('editable clicked', kolom, tabel, id);

      old_val = $(this).text();
      let new_val = prompt("Value baru:", old_val);

      if (new_val === null) {
        console.log('canceled', new_val);
        return;
      } else if (old_val == new_val) {
        console.log('old_val==new_val :: aborted');
        return;
      } else if (new_val === '') {
        let y = confirm('Yakin ingin mengosongkan nilai?');
        if (!y) {
          console.log('aborted :: tidak jadi mengosongkan nilai');
          return;
        } else {
          new_val = 'NULL';
        }
      }

      let link_ajax = `ajax/crud.php?tb=${tabel}&aksi=update&id=${id}&kolom=${kolom}&value=${new_val}`;

      $.ajax({
        url: link_ajax,
        success: function(a) {
          console.log('reply from AJAX: ', a);
          if (a.trim() == 'sukses') {
            $('#' + tid).text(new_val);
          } else {
            alert(a);
          }
        }
      })

    })
  })
</script>