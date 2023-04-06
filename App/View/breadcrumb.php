<div class="pagetitle">
    <div class="infoBar">
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
    </div>
    <div class="buttonBar pe-3">
        <?=$this->buttons; ?>
    </div>
</div><!-- End Page Title -->