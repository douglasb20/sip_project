<!DOCTYPE html>
<html lang="pt-br">

<head>
    <title><?=(empty($this->titulo_pagina) ? 'AmarisFramework' : $this->titulo_pagina)?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="shortcut icon" href="/_arquitetura/public/img/favicon.png"> -->
    <!-- CSS -->
    <link rel="stylesheet" id="bootstrap" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/<?=$_ENV['BASE_URL']?>assets/css/layout.css">
    <?php echo $this->render['css']; ?>

    <!-- Javascript -->
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/js/jquery/jquery-3.6.3.min.js"></script>
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>

<body class="">

    <!-- Inicio - Body -->
    <div class="<?= $this->getClassDivContainer() ?>">
        <?php echo $this->render['body']; ?>
    </div>
    <!-- Fim - Body -->

    <!-- Inicio - Footer -->
    <?php 
        if($this->getShowFooter()){
            include __DIR__ . './footer.php'; 
        }
    ?>
    <!-- Fim - Footer -->
    <script type="text/javascript" src="/<?=$_ENV['BASE_URL']?>assets/js/script.js"></script>
</body>

<?php echo $this->render['js']; ?>

</html>