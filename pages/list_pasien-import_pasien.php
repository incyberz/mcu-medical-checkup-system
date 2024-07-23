<?php
$import_pasien = "
  <form method=post class='wadah'>
    <div class='f12 abu mb2'>Form Import Data Pasien</div>
    <p>Silahkan download File Excel yang di upload oleh user (atau via whatsapp) kemudian disesuaikan field-fieldnya, lalu import disini.</p>
    <div class='flexy'>
      <div>
        <input type='file' class='form-control' required name=excel_pasien accept='.xls,.xlsx'>
      </div>
      <div>
        <!-- <button class='btn btn-success' name=btn_import_pasien value='$order_no'>Import</button> -->
        <div class='alert alert-danger'>Fitur ini masih dalam tahap pengembangan. Untuk proses Import Pasien silahkan forward file Excel nya langsung ke Developer. Terimakasih.</div>
      </div>
    </div>
  </form>

";
