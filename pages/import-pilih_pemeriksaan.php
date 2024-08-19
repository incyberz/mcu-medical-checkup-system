<?php
$s = "SELECT * FROM tb_pemeriksaan WHERE untuk='cor' ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$opt = '';
while ($d = mysqli_fetch_assoc($q)) {
  $opt .= "<option value=$d[id]>$d[nama]</option>";
  $arr_pemeriksaan[$d['id']] = [
    'nama' => ucwords(strtolower($d['nama'])),
    'singkatan' => strtolower($d['singkatan']),
  ];
}
if (!$id_pemeriksaan || !$id_perusahaan) {
  $opt2 = '';
  $s = "SELECT * FROM tb_perusahaan ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  while ($d = mysqli_fetch_assoc($q)) {
    // $id=$d['id'];
    $opt2 .= "<option value=$d[id]>$d[nama]</option>";
  }


  echo "
    <form method=post class='wadah gradasi-hijau'>
      <div class=flexy>
        <div>
          <select class='form-control' name=id_pemeriksaan>
            $opt
          </select>
        </div>
        <div class='pt2 f14 abu'>untuk</div>
        <div>
          <select class='form-control' name=id_perusahaan>
            $opt2
          </select>
        </div>
        <div>
          <button class='btn btn-primary'>Next Import</button>
        </div>
      </div>
    </form>
  ";
  exit;
}
