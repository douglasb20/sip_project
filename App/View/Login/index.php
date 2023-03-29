<?php $this->captureStart("css")?>
<style>
    body{
        background-image    : url('{{URL_IMAGES}}/bg.svg'  );
        background-size     : cover;
        background-repeat   : no-repeat;
        background-position : center;
        display             : block;
    }
    .loginBox{
        width: 1150px;
        height: 650px;
        overflow: hidden;
        flex-direction: row;
    }
    .areaForm{
        flex: 1.5;
    }
    .areaImage{
        background-image: url('{{URL_IMAGES}}/telefonia.jpg');
        background-size: 100% 100%;
        background-position: center;
        flex: 2;
    }
    .headerBox{
        width: 100%;
        display: flex;
        align-items: center;
        flex-direction: column;
        padding: 10px;
    }

    .headerBox img{
        width: 80px;
        height: 80px;
        border-radius: 15px;
    }
</style>

<?php $this->captureEnd("css")?>

<?php $this->captureStart("body")?>

<div class="card loginBox shadow mt-5 d-flex ">
    <div class="areaForm w-50 bg-info ">
        <div class="headerBox">
            <img src="{{URL_IMAGES}}/logo-ltc.jpg">
            <h4 class="mt-3">Login</h4>
        </div>
    </div>
    <div class="areaImage w-75 bg-primary ">

    </div>
</div>   





<?php $this->captureEnd("body")?>


<?php $this->captureStart("js")?>
<?php $this->captureEnd("js")?>