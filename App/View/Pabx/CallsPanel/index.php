<?php $this->captureStart("css") ?>
<style>
.text-title{
    font-size   : 18px;
    font-weight : 500;
    color       : #060816;
    font-family : "Poppins", sans-serif;
}
.extensionsSip{
    height: 100px;
}
.extensionsSip:hover{
    background-color: #53dcf8 !important ;
    cursor: default;
}
.col-3{
    width: calc(25% - 10px);
    margin: 5px;
    border-radius: 5px;
    
}
</style>

<?php $this->captureEnd("css") ?>

<?php $this->captureStart("body") ?>

<div class="col-12 ">
    <div class="card ">
        <div class="card-header d-flex flex-row justify-content-between align-items-center">
            <span class="text-title">Painel de Ligações</span>
            <!-- <button id="btnFiltros" class="btn btn-outline-info " >Filtros</button> -->
        </div>
        <div class="card-body mt-4">
            <div class="row">
            <?php
                foreach($sip as $s){

            ?>
                <div class="col-3 extensionsSip bg-info px-3 pt-1 pb-2  ">
                    <div class="d-flex flex-column align-items-start justify-content-start">
                        <span ><?=$s['id_sip']?></span>
                        <small><?=$s['callerId']?></small>
                    </div>
                </div>
            <?php
                }
            ?>
            </div>
        </div>
    </div>
</div>

<?php $this->captureEnd("body") ?>

<?php $this->captureStart("js") ?>
<?php $this->captureEnd("js") ?>