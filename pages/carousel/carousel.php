<link rel="stylesheet" href="assets/css/carousel2.css">
<style>
  /* @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
body{
    margin: 0;
    background-color: #000;
    color: #eee;
    font-family: Poppins;
    font-size: 12px;
}
a{
    text-decoration: none;
}
header{
    width: 1140px;
    max-width: 80%;
    margin: auto;
    height: 50px;
    display: flex;
    align-items: center;
    position: relative;
    z-index: 100;
}
header a{
    color: #eee;
    margin-right: 40px;
} */


  /* carousel */
  .carousel {
    height: 100vh;
    /* margin-top: -50px; */
    width: 100vw;
    overflow: hidden;
    position: relative;
  }

  .carousel .list .item {
    width: 100%;
    height: 100%;
    position: absolute;
    inset: 0 0 0 0;
  }

  .carousel .list .item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .carousel .list .item .content {
    position: absolute;
    top: 20%;
    width: 1140px;
    max-width: 80%;
    left: 50%;
    transform: translateX(-50%);
    padding-right: 30%;
    box-sizing: border-box;
    color: #fff;
    text-shadow: 0 5px 10px #0004;
  }

  .carousel .list .item .author {
    font-weight: bold;
    /* letter-spacing: 10px; */
  }

  .carousel .list .item .nama_paket,
  .carousel .list .item .deskripsi {
    text-shadow: 0 0 5px black;
  }

  .carousel .list .item .nama_paket,
  .carousel .list .item .nama_program {
    font-size: 3em;
    font-weight: bold;
    line-height: 1.3em;
  }

  .carousel .list .item .nama_program {
    color: #f1683a;
  }

  .carousel .list .item .buttons {
    display: grid;
    grid-template-columns: repeat(2, 130px);
    grid-template-rows: 40px;
    gap: 5px;
    margin-top: 20px;
  }

  .carousel .list .item .buttons button {
    border: none;
    background-color: #eee;
    letter-spacing: 3px;
    font-family: Poppins;
    font-weight: 500;
  }

  .carousel .list .item .buttons button:nth-child(2) {
    background-color: transparent;
    border: 1px solid #fff;
    color: #eee;
  }

  /* thumbail */
  .thumbnail {
    position: absolute;
    bottom: 50px;
    left: 50%;
    width: max-content;
    z-index: 100;
    display: flex;
    gap: 20px;
  }

  .thumbnail .item {
    width: 150px;
    height: 220px;
    flex-shrink: 0;
    position: relative;
  }

  .thumbnail .item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 20px;
  }

  .thumbnail .item .content {
    color: #fff;
    position: absolute;
    bottom: 10px;
    left: 10px;
    right: 10px;
  }

  .thumbnail .item .content .nama_paket {
    font-weight: 500;
  }

  .thumbnail .item .content .description {
    font-weight: 300;
  }

  /* arrows */
  .arrows {
    position: absolute;
    top: 80%;
    right: 52%;
    z-index: 100;
    width: 300px;
    max-width: 30%;
    display: flex;
    gap: 10px;
    align-items: center;
  }

  .arrows button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #eee4;
    border: none;
    color: #fff;
    font-family: monospace;
    font-weight: bold;
    transition: .5s;
  }

  .arrows button:hover {
    background-color: #fff;
    color: #000;
  }

  /* animation */
  .carousel .list .item:nth-child(1) {
    z-index: 1;
  }

  /* animation text in first item */

  .carousel .list .item:nth-child(1) .content .author,
  .carousel .list .item:nth-child(1) .content .nama_paket,
  .carousel .list .item:nth-child(1) .content .nama_program,
  .carousel .list .item:nth-child(1) .content .deskripsi,
  .carousel .list .item:nth-child(1) .content .buttons {
    transform: translateY(50px);
    filter: blur(20px);
    opacity: 0;
    animation: showContent .5s 1s linear 1 forwards;
  }

  @keyframes showContent {
    to {
      transform: translateY(0px);
      filter: blur(0px);
      opacity: 1;
    }
  }

  .carousel .list .item:nth-child(1) .content .nama_paket {
    animation-delay: 1.2s !important;
  }

  .carousel .list .item:nth-child(1) .content .nama_program {
    animation-delay: 1.4s !important;
  }

  .carousel .list .item:nth-child(1) .content .deskripsi {
    animation-delay: 1.6s !important;
  }

  .carousel .list .item:nth-child(1) .content .buttons {
    animation-delay: 1.8s !important;
  }

  /* create animation when next click */
  .carousel.next .list .item:nth-child(1) img {
    width: 150px;
    height: 220px;
    position: absolute;
    bottom: 50px;
    left: 50%;
    border-radius: 30px;
    animation: showImage .5s linear 1 forwards;
  }

  @keyframes showImage {
    to {
      bottom: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border-radius: 0;
    }
  }

  .carousel.next .thumbnail .item:nth-last-child(1) {
    overflow: hidden;
    animation: showThumbnail .5s linear 1 forwards;
  }

  .carousel.prev .list .item img {
    z-index: 100;
  }

  @keyframes showThumbnail {
    from {
      width: 0;
      opacity: 0;
    }
  }

  .carousel.next .thumbnail {
    animation: effectNext .5s linear 1 forwards;
  }

  @keyframes effectNext {
    from {
      transform: translateX(150px);
    }
  }

  /* running time */

  .carousel .time {
    position: absolute;
    z-index: 1000;
    width: 0%;
    height: 3px;
    background-color: #f1683a;
    left: 0;
    top: 0;
  }

  .carousel.next .time,
  .carousel.prev .time {
    animation: runningTime 3s linear 1 forwards;
  }

  @keyframes runningTime {
    from {
      width: 100%
    }

    to {
      width: 0
    }
  }


  /* prev click */

  .carousel.prev .list .item:nth-child(2) {
    z-index: 2;
  }

  .carousel.prev .list .item:nth-child(2) img {
    animation: outFrame 0.5s linear 1 forwards;
    position: absolute;
    bottom: 0;
    left: 0;
  }

  @keyframes outFrame {
    to {
      width: 150px;
      height: 220px;
      bottom: 50px;
      left: 50%;
      border-radius: 20px;
    }
  }

  .carousel.prev .thumbnail .item:nth-child(1) {
    overflow: hidden;
    opacity: 0;
    animation: showThumbnail .5s linear 1 forwards;
  }

  .carousel.next .arrows button,
  .carousel.prev .arrows button {
    pointer-events: none;
  }

  .carousel.prev .list .item:nth-child(2) .content .author,
  .carousel.prev .list .item:nth-child(2) .content .nama_paket,
  .carousel.prev .list .item:nth-child(2) .content .nama_program,
  .carousel.prev .list .item:nth-child(2) .content .deskripsi,
  .carousel.prev .list .item:nth-child(2) .content .buttons {
    animation: contentOut 1.5s linear 1 forwards !important;
  }

  .shadow_black {
    text-shadow: 0 0 3px black;
  }

  .shadow_white {
    text-shadow: 0 0 3px white;
  }

  @keyframes contentOut {
    to {
      transform: translateY(-150px);
      filter: blur(20px);
      opacity: 0;
    }
  }

  @media screen and (max-width: 678px) {
    .carousel .list .item .content {
      padding-right: 0;
    }

    .carousel .list .item .content .nama_paket {
      font-size: 30px;
    }
  }

  @media screen and (max-width: 500px) {
    .author {
      font-size: 16px !important;
    }

    .nama_paket {
      font-size: 20px !important;
    }

    .nama_program {
      font-size: 18px !important;
    }

    .deskripsi {
      font-size: 14px !important;
    }

    .nama_paket_thumb {
      font-size: 14px !important;

    }
  }
</style>

<?php
$s = "SELECT 
a.nama as nama_paket, 
a.carousel_image,
a.deskripsi,
b.id as id_program, 
b.nama as nama_program 
FROM tb_paket a 
JOIN tb_program b ON a.id_program=b.id 
WHERE a.carousel_image IS NOT NULL 
AND a.status=1";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));

$car_item = '';
$car_thum = '';
if (!mysqli_num_rows($q)) {
  // do nothing | view nothing
} else {
  while ($paket = mysqli_fetch_assoc($q)) {
    // $id=$d['id'];
    $car_item .= "
      <div class='item'>
        <img src='assets/img/carousel-img/$paket[carousel_image]'>
        <div class='content'>
          <div class='author shadow_black'>Medical Checkup</div>
          <div class='nama_paket'>$paket[nama_paket]</div>
          <div class='nama_program shadow_white'>$paket[nama_program]</div>
          <div class='deskripsi'>$paket[deskripsi]</div>
          <div class='buttons'>
            <a class='btn btn-sm btn-success pt2' href='?program_detail&id_program=$paket[id_program]'>
              SEE MORE
            </a>
          </div>
        </div>
      </div>
    ";
    $car_thum .= "
      <div class='item'>
        <img src='assets/img/carousel-img/$paket[carousel_image]'>
        <div class='content'>
          <div class='nama_paket f14 shadow_black nama_paket_thumb'>
            $paket[nama_paket]
          </div>
          <div class='description f12 miring shadow_black'>
            $paket[nama_program]
          </div>
        </div>
      </div>
    ";
  }
}




?>
<div class="carousel">
  <div class="list"><?= $car_item ?></div>
  <div class="thumbnail"><?= $car_thum ?></div>

  <div class="arrows">
    <button id="prev">

      < </button>
        <button id="next">></button>
  </div>
  <!-- time running -->
  <div class="time"></div>
</div>

<!-- <script src="assets/js/carousel.js"></script> -->
<script>
  //step 1: get DOM
  let nextDom = document.getElementById('next');
  let prevDom = document.getElementById('prev');

  let carouselDom = document.querySelector('.carousel');
  let SliderDom = carouselDom.querySelector('.carousel .list');
  let thumbnailBorderDom = document.querySelector('.carousel .thumbnail');
  let thumbnailItemsDom = thumbnailBorderDom.querySelectorAll('.item');
  let timeDom = document.querySelector('.carousel .time');

  thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
  let timeRunning = 3000;
  let timeAutoNext = 7000;

  nextDom.onclick = function() {
    showSlider('next');
  }

  prevDom.onclick = function() {
    showSlider('prev');
  }
  let runTimeOut;
  let runNextAuto = setTimeout(() => {
    next.click();
  }, timeAutoNext)

  function showSlider(type) {
    let SliderItemsDom = SliderDom.querySelectorAll('.carousel .list .item');
    let thumbnailItemsDom = document.querySelectorAll('.carousel .thumbnail .item');

    if (type === 'next') {
      SliderDom.appendChild(SliderItemsDom[0]);
      thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
      carouselDom.classList.add('next');
    } else {
      SliderDom.prepend(SliderItemsDom[SliderItemsDom.length - 1]);
      thumbnailBorderDom.prepend(thumbnailItemsDom[thumbnailItemsDom.length - 1]);
      carouselDom.classList.add('prev');
    }
    clearTimeout(runTimeOut);
    runTimeOut = setTimeout(() => {
      carouselDom.classList.remove('next');
      carouselDom.classList.remove('prev');
    }, timeRunning);

    clearTimeout(runNextAuto);
    runNextAuto = setTimeout(() => {
      next.click();
    }, timeAutoNext)
  }
</script>