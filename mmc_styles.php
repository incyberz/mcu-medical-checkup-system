<style>
  <?php if ($dm) {
    echo '.debug{display:inline; background:yellow; color: blue}';
  } else {
    echo '.debug{display:none;}';
  }
  ?>@media (max-width: 500px) {
    .desktop-only {
      display: none;
    }
  }

  section {
    padding-top: 140px;
    <?php if ($parameter) echo "padding-bottom: 15px;"; ?>
    /* min-height: 100vh; bahaya untuk section count */
  }

  #footer .footer-top {
    padding: 0;
  }
</style>


<style>
  /*--------------------------------------------------------------
# Tim
--------------------------------------------------------------*/
  .tim {
    background: #fff;
  }

  .tim .member {
    position: relative;
    box-shadow: 0px 2px 15px rgba(44, 73, 100, 0.08);
    padding: 30px;
    border-radius: 10px;
  }

  .tim .member .pic {
    overflow: hidden;
    width: 180px;
    border-radius: 50%;
  }

  .tim .member .pic img {
    transition: ease-in-out 0.3s;
  }

  .tim .member:hover img {
    transform: scale(1.1);
  }

  .tim .member .member-info {
    padding-left: 30px;
  }

  .tim .member h4 {
    font-weight: 700;
    margin-bottom: 5px;
    font-size: 20px;
    color: #2c4964;
  }

  .tim .member span {
    display: block;
    font-size: 15px;
    padding-bottom: 10px;
    position: relative;
    font-weight: 500;
  }

  .tim .member span::after {
    content: "";
    position: absolute;
    display: block;
    width: 50px;
    height: 1px;
    background: #b2c8dd;
    bottom: 0;
    left: 0;
  }

  .tim .member p {
    margin: 10px 0 0 0;
    font-size: 14px;
  }

  .tim .member .social {
    margin-top: 12px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
  }

  .tim .member .social a {
    transition: ease-in-out 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50px;
    width: 32px;
    height: 32px;
    background: #a0bcd5;
  }

  .tim .member .social a i {
    color: #fff;
    font-size: 16px;
    margin: 0 2px;
  }

  .tim .member .social a:hover {
    background: #1977cc;
  }

  .tim .member .social a+a {
    margin-left: 8px;
  }
</style>

<style>
  .produk .icon-box {
    text-align: center;
    border: 1px solid #d5e1ed;
    padding: 80px 20px;
    transition: all ease-in-out 0.3s;
  }

  .produk .icon-box .icon {
    margin: 0 auto;
    width: 64px;
    height: 64px;
    background: #1977cc;
    border-radius: 5px;
    transition: all 0.3s ease-out 0s;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    transform-style: preserve-3d;
    position: relative;
    z-index: 2;
  }

  .produk .icon-box .icon i {
    color: #fff;
    font-size: 28px;
    transition: ease-in-out 0.3s;
  }

  .produk .icon-box .icon::before {
    position: absolute;
    content: "";
    left: -8px;
    top: -8px;
    height: 100%;
    width: 100%;
    background: rgba(25, 119, 204, 0.2);
    border-radius: 5px;
    transition: all 0.3s ease-out 0s;
    transform: translateZ(-1px);
    z-index: -1;
  }

  .produk .icon-box h4 {
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 24px;
  }

  .produk .icon-box h4 a {
    color: #2c4964;
  }

  .produk .icon-box p {
    line-height: 24px;
    font-size: 14px;
    margin-bottom: 0;
  }

  .produk .icon-box:hover {
    background: #1977cc;
    border-color: #1977cc;
  }

  .produk .icon-box:hover .icon {
    background: #fff;
  }

  .produk .icon-box:hover .icon i {
    color: #1977cc;
  }

  .produk .icon-box:hover .icon::before {
    background: rgba(255, 255, 255, 0.3);
  }

  .produk .icon-box:hover h4 a,
  .produk .icon-box:hover p {
    color: #fff;
  }

  /* my styles */
  .img-mcu {
    width: 280px;
    height: 200px;
    object-fit: cover;
    margin: 0 0 15px 0;
    transition: .2s;
    border-radius: 10px;
  }

  .img-mcu:hover {
    transform: scale(1.1);
  }

  .shout {
    color: #33a;
    font-weight: bold;
    font-size: 24px;
    font-family: consolas;
  }

  .produk .icon-box:hover .shout {
    color: #ff0;
  }
</style>


<style>
  .btn_aksi {
    cursor: pointer;
  }

  .btn-edit-page {
    font-family: "Raleway", sans-serif;
    text-transform: uppercase;
    font-weight: 500;
    font-size: 14px;
    letter-spacing: 1px;
    display: inline-block;
    padding: 12px 35px;
    margin-top: 10px;
    border-radius: 50px;
    transition: 0.5s;
    color: #fff;
    background: #844;
    text-shadow: none;
    text-align: center;
  }

  .btn-edit-page:hover {
    background: #3291e6;
    color: #ff0;
  }

  @media (max-width:400px) {
    .btn-edit-page {
      width: 100%;
    }
  }

  .log {
    background: yellow;
  }

  .btn-transparan {
    border: none;
    background: none;
  }
</style>

<style>
  .img-tim {
    width: 150px;
    height: 150px;
    object-fit: cover;
    transition: .2s;
    border-radius: 50%;
    border: solid 5px white;
    box-shadow: 0 0 5px gray;
    cursor: pointer;
  }

  @media (max-width:1024px) {
    .img-tim {
      border: solid 4px white;
      width: 120px;
      height: 120px;
    }

  }

  @media (max-width:600px) {
    .img-tim {
      border: solid 3px white;
      width: 90px;
      height: 90px;
    }

  }

  @media (max-width:360px) {
    .img-tim {
      border: solid 2px white;
      width: 70px;
      height: 70px;
    }

  }


  /* SOCIAL SPAN OR LINK */
  .tim .member .social div {
    transition: ease-in-out 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50px;
    width: 32px;
    height: 32px;
    background: #d0bcd5;
    margin-right: 10px;
    cursor: no-drop;
  }

  .tim .member .social div i {
    color: #fff;
    font-size: 16px;
    margin: 0 2px;
  }

  .tim .member .social div:hover {
    background: #cc7719;
  }
</style>


<style>
  td {
    background: none !important;
  }
</style>