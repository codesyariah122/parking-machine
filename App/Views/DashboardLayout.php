<!DOCTYPE html>
<html class="bg-[#2A2A32] dark:bg-[#2A2A32]">
<head>
  <title><?=$title?></title>
  <!-- Masukkan tag meta, CSS, dan JavaScript yang diperlukan -->
  <script src="<?=$vendor['tailwind']?>"></script>
  <script type="text/javascript">
     tailwind.config = {
       theme: {
         container: {
           center: true,
           padding: '2rem',
        },
     }
  }
</script>
<link href="<?=$vendor['flowbite']['css']?>"  rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Poiret+One&family=Poppins:wght@900&family=Quicksand:wght@500&display=swap" rel="stylesheet" />

<?php foreach($links as $link): ?>
  <link rel="stylesheet" type="text/css" href="<?=$link?>" />
<?php endforeach;?>

<script src="<?=$vendor['sweetalert']?>"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="<?=$vendor['flowbite']['js']?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

<style type="text/css">
  body.swal2-shown > [aria-hidden="true"] {
    transition: 0.1s filter;
    filter: blur(10px);
 }
</style>

<link rel="shortcut icon" type="image/x-icon" href="<?=$favicon?>">

</head>

<body class="bg-[#2A2A32] dark:bg-[#2A2A32]">
   <header>
     <?php if($navbar): require_once $navbar; endif;?>
   </header>

   <?php require_once $sidebar;?>

   <div class="p-4 sm:ml-64 py-[120px] bg-[#2A2A32]">
      <div class="h-screen bg-[#2A2A32] dark:bg-[#2A2A32] text-white">

       <?php if($contents['loadingOverlay']): require_once $contents['loadingOverlay']; endif;  ?>

       <?=$content; ?>

    </div>
   </div>

<footer>
 <?php require_once $footer_content?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<?php foreach($scripts as $script):?>
 <script type="text/javascript" src="<?=$script?>"></script>
<?php endforeach;?>
</body>
</html>
