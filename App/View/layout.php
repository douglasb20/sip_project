<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title><?=(empty($this->titulo_pagina) ? 'LTCFibra' : "LTCFibra | " . $this->titulo_pagina)?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/assets/images/logo-ltc.jpg">
    <!-- CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro-v6@44659d9/css/all.min.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/vendor/DataTable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/vendor/fontawesome/css/all.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/vendor/sweetalert2/sweetalert2.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/vendor/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/vendor/bootstrap-datetimepicker/4.15.35/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/vendor/select2/4.1.0/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/css/admin.css?<?=time()?>">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/css/layout.css?<?=time()?>">
    <?php echo $this->render['css']; ?>

    <!-- Javascript -->
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/jquery/jquery-3.6.3.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/jquery-loading-overlay/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/moment/moment-with-locales.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/bootstrap-datetimepicker/4.15.35/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/DataTable/datatables.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/jquery-mask-ui/jquery.mask.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/echarts/echarts.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/sweetalert2/sweetalert2.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/select2/4.1.0/js/select2.min.js"></script>
</head>

<body class="dark">

    <?php echo $this->render['menu']; ?>
    <!-- Inicio - Body -->
    <?php if ($this->getShowMenu()){
    
    ?>
    <main id="main" class="main">
        <?php
            include_once ROOT_PATH . '/App/View/breadcrumb.php'; 
        ?>
        <div class="<?= $this->getClassDivContainer() ?>">
            <?php echo $this->render['body']; ?>
        </div>
    </main>
    <?php 
    }else{

    ?>
    <div class="<?= $this->getClassDivContainer() ?>">
        <?php echo $this->render['body']; ?>
    </div>

    <?php
        }
    ?>
    <!-- Fim - Body -->

    <!-- Inicio - Footer -->
    <?php 
        if($this->getShowFooter()){
            include_once ROOT_PATH . '/App/View/footer.php'; 
        }
    ?>
    <!-- Fim - Footer -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="fa-regular fa-arrow-up fa-sm"></i></a>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/js/menu.js?<?=time()?>"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/js/admin.js?<?=time()?>"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/js/mascaras.js?<?=time()?>"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/js/script.js?<?=time()?>"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/js/datatables.js?<?=time()?>"></script>
</body>

<?php echo $this->render['js']; ?>

</html>
<?php
$content = ob_get_contents();
ob_end_clean();
echo $this->minify_html($content); 
?>