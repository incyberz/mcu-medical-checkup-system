<?php
// v. 2.0.1 updated with array or non-array input users

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

function only($roles = ['admin'], $pesan = "Maaf, Anda harus login dengan role yang tepat untuk melihat page ini.<hr>Redirecting to homepage...", $timeout = 3000)
{

  $allowed = 0;
  if (isset($_SESSION['mmc_username'])) {
    if (isset($_SESSION['mmc_role'])) {
      if (is_array($roles)) {
        foreach ($roles as $role) {
          // allowed for all user
          if (
            $role == 'users' // all user
            || $_SESSION['mmc_role'] == $role
          ) {
            $allowed = 1;
            break;
          }
        }
      } else { // bukan array
        if (
          $roles == 'users' // all user
          || $_SESSION['mmc_role'] == $roles
        ) {
          $allowed = 1;
        }
      }
    }
  }

  if (!isset($_SESSION['mmc_username']) || !$allowed) {
    die("
    <div style='display:flex;justify-content:center; color:red'>
      <div style='border:solid 1px red; background: #fcc; padding:100px 30px; border-radius:10px; text-align:center'>
        $pesan
      </div>
    </div>
    <script>setTimeout(()=>{location.replace('?')},$timeout);</script>
    ");
  }
}
