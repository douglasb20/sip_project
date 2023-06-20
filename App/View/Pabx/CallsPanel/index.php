<?php $this->captureStart("css") ?>
<style>
.text-title{
    font-size   : 18px;
    font-weight : 500;
    color       : #060816;
    font-family : "Poppins", sans-serif;
}
.extensionsSip{
    height: 120px;
    cursor: default;
}

.extensionsSip.wait{
    background-color: #D6E4FF;
    border: 1px #ADC8FF solid;
    color: #1939B7 ;
}   
.extensionsSip.own{
    background-color: #FAD6FF;
    border: 1px #F0ADFF solid;
    color: #6A19B7;
}
.extensionsSip.calling{
    background-color: #FEFED2;
    border: 1px #F8F51F solid;
    color: #777405;
}
.extensionsSip.offline{
    background-color: #FFC2AC;
    border: 1px #FF9982 solid;
    color: #B71831;
}

.extensionsSip.onCall{
    background-color: #B1EAFE;
    border: 1px #8BD8FD solid;
    color: #1F62B3
}
.extensionsSip.ringing{
    background-color: #FFE9B2;
    border: 1px #FFDA8B solid;
    color: #B76E1F
}
.colunas{
    width: 220px;
    max-width: 250px;
    margin: 5px;
    border-radius: 5px;
}
.baloes{
    display: flex;
    flex-direction: row;
    justify-content: center;
    flex-wrap: wrap;
}


</style>

<?php $this->captureEnd("css") ?>

<?php $this->captureStart("body") ?>

<div class="col-12 ">
    <div class="card ">
        <div class="card-header d-flex flex-row justify-content-between align-items-center">
            <span class="text-title">Painel de Ligações</span>
            <span class="alert-server text-danger"><span class='badge text-bg-danger rounded-circle'>&nbsp</span></span>
        </div>
        <div class="card-body mt-4 ">
            <div class="baloes">
            <?php
                foreach($sip as $s){
                    $own = false;
                    if(GetSessao("ramal") === $s['id_sip']){
                        $own = true;
                    }
            ?>
                <div class="colunas extensionsSip px-3 pt-2 pb-2 sip-<?=$s['id_sip']?> <?=($own ? 'own' : 'wait')?> offline" >
                    <div class="d-flex flex-column align-items-center justify-content-start">
                        <span class="text-xl">
                            <?=$s['id_sip']?>
                        <?php
                            if($own){
                                echo "<i class='fa-regular fa-badge-check'></i>";
                            }
                        ?>
                        </span>
                        <small><?=$s['callerId']?></small>
                        <div class="text-sm">
                            <span class="state">Off-Line</span>
                            <span class="callDuration"></span>
                        </div>
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
<script>

let wsHost       = "{{$_ENV['URL_WEBSOCKET']}}";

$(function(){
    ConnectToWS(wsHost)
})

</script>

<?php $this->captureEnd("js") ?>