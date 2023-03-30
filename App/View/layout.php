<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title><?=(empty($this->titulo_pagina) ? 'LTCFibra' : "LTCFibra | " . $this->titulo_pagina)?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="shortcut icon" href="/_arquitetura/public/img/favicon.png"> -->
    <!-- CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/gh/hung1001/font-awesome-pro-v6@44659d9/css/all.min.css" rel="stylesheet" type="text/css" /> -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/vendor/DataTable/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/vendor/fontawesome/css/all.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/vendor/sweetalert2/sweetalert2.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/css/admin.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/css/layout.css">
    <?php echo $this->render['css']; ?>

    <!-- Javascript -->
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/jquery/jquery-3.6.3.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/jquery-loading-overlay/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/DataTable/datatables.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/vendor/sweetalert2/sweetalert2.js"></script>
</head>

<body class="">

    <?php echo $this->render['menu']; ?>
    <!-- Inicio - Body -->
    <?php if ($this->getShowMenu()){
    
    ?>
    <main id="main" class="main">
        <div class="pagetitle">
            <?php
                $bread = $this->getBreadcrumb();
            ?>
            <h1><?=end($bread)?></h1>
            <nav>
                <ol class="breadcrumb">
                    <?php foreach($bread as $key => $b){
                        $active = count($bread) - 1 === $key ? "active" : "";
                    ?>

                    <li class="breadcrumb-item <?=$active?>"><?=$b?></li>
                    
                    <?php }?>
                </ol>
            </nav>
        </div><!-- End Page Title -->
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
            include_once './footer.php'; 
        }
    ?>
    <!-- Fim - Footer -->
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/js/admin.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/js/script.js"></script>
</body>

<?php echo $this->render['js']; ?>

</html>