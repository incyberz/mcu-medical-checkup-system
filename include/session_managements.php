<?php

function login_only($pesan = 'Maaf, Anda harus login untuk melihat page ini.<hr>Redirecting to homepage...', $timeout = 3000)
{
  if (!isset($_SESSION['mmc_username']))
    die("
    <div style='display:flex;justify-content:center; color:red'>
      <div style='border:solid 1px red; background: #fcc; padding:100px 30px; border-radius:10px; text-align:center'>
        $pesan
      </div>
    </div>
    <script>setTimeout(()=>{location.replace('?')},$timeout);</script>
    ");
}

function admin_only($pesan = 'Maaf, Anda harus login sebagai <u>admin</u> untuk melihat page ini.<hr>Redirecting to homepage...', $timeout = 3000)
{
  if (!isset($_SESSION['mmc_username']) || $_SESSION['mmc_role'] != 'admin')
    die("
    <div style='display:flex;justify-content:center; color:red'>
      <div style='border:solid 1px red; background: #fcc; padding:100px 30px; border-radius:10px; text-align:center'>
        $pesan
      </div>
    </div>
    <script>setTimeout(()=>{location.replace('?')},$timeout);</script>
    ");
}
