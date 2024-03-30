<h2>BIODATA KARYAWAN</h2>
<div class="tengah">
  <?= $profil ?>
</div>
<table class="table">
  <tr>
    <td>NAMA</td>
    <td>:</td>
    <td>IIN SHOLIHIN</td>
  </tr>
  <tr>
    <td>NIK</td>
    <td>:</td>
    <td>3211111106870004</td>
  </tr>
  <tr>
    <td>JENIS KELAMIN</td>
    <td>:</td>
    <td>
      <div>
        <label>
          <input type="radio" name="jenis_kelamin"> Laki-laki
        </label>
      </div>
      <div>
        <label>
          <input type="radio" name="jenis_kelamin"> Perempuan
        </label>
      </div>
    </td>
  </tr>
  <tr>
    <td>TGL LAHIR</td>
    <td>:</td>
    <td>
      <input type="date" class="form-control" value="1989-06-10">
    </td>
  </tr>
  <tr>
    <td>UMUR</td>
    <td>:</td>
    <td>36 tahun</td>
  </tr>
  <tr>
    <td>ALAMAT</td>
    <td>:</td>
    <td><textarea rows="5" class="form-control"></textarea></td>
  </tr>
  <tr>
    <td>WHATSAPP</td>
    <td>:</td>
    <td>
      <input type="text" class="form-control">
    </td>
  </tr>
</table>