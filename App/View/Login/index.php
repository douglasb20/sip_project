<?php $this->captureStart("css")?>
<style>
    body{
        background-image    : url('{{URL_IMAGES}}/bg.svg'  );
        background-size     : cover;
        background-repeat   : no-repeat;
        background-position : center;
        overflow: hidden;
    }
    .formsBox{
        width               : 1050px;
        height              : 550px;
        overflow            : hidden;
        flex-direction      : row;
        justify-content: space-between;
        position: relative;
        font-family: "Poppins" !important
    }
    .loginForm,
    .forgotForm{
        width: 40%;
        display: flex;
        flex-direction: column;
    }
    .areaImage{
        background-image    : url('{{URL_IMAGES}}/telefonia.jpg');
        background-size     : 100% 100%;
        background-position : center;
        width: 60%;
        height: 100%;
        right: 0;
        position: absolute;
        transition: all .3s;
    }
    .headerBox{
        width               : 100%;
        display             : flex;
        align-items         : center;
        flex-direction      : column;
        padding             : 10px;
        font-family: "Poppins" !important
    }
    .headerBox .title,
    .headerBox .subtitle{
        font-family: "Poppins" !important;
        user-select: none;
    }

    .headerBox img{
        width               : 80px;
        height              : 80px;
        border-radius       : 15px;
    }
    .caixaInputs, form{
        width: 100%;
        padding: 10px;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .wrapInput{
        width            : 80%;
        margin           : 10px 0px;
        display          : flex;
        position         : relative;
        align-items      : center;
        background-color : #fff;
        border-radius    : 5px;
    }
    .wrapInput input{
        outline: none;
        width: 100%;
        padding : 15px 0;
        padding-right : 25px;
        padding-left : 10px;
        background-color: transparent;
        border: 1.5px solid #999;
        height: inherit !important;
        border-radius: 5px;
        caret-color: rgb(var(--bs-primary-rgb));
    }
    .labelInput, .iconInput{
        position: absolute;
        pointer-events: none;
        transition: all 0.2s
    }
    .labelInput{
        left: 8px;
        background-color: #fff;
        color: #999;
        padding: 0px 5px;
    }
    .iconInput{
        right: 10px
    }
    .wrapInput input:focus:not(.invalid){
        border-color: rgb(var(--bs-primary-rgb)) !important;
    }
    .wrapInput input:focus:not(.invalid) ~ .iconInput{
        color: rgb(var(--bs-primary-rgb))
    }
    .wrapInput input:focus ~ .labelInput,
    .wrapInput input:not(:placeholder-shown) ~ .labelInput,
    .wrapInput input:valid:not(:placeholder-shown) ~ .labelInput{
        transform: translateY(-27px);
        font-size: 0.75rem;
        color: rgb(var(--bs-primary-rgb))
    }
    .caixaAcoes{
        display: flex;
        justify-content: space-between;
        padding: 10px;
        align-items: center;
        width: 80%;
        align-self: center;
    }
    .link:hover{
        text-decoration: underline;
        text-decoration-color: rgb(var(--bs-primary-rgb));
    }
    small{
        align-self:flex-start
    }

    @media screen and (width < 576px){
        .loginForm,
        .forgotForm{
            width: 100%;
            position: absolute;
            flex-direction: column;
            transition: all .3s;
            right: 0;
        }
        .forgotForm{
            right: -100%;
        }
        .areaImage{
            display: none;
        }
        .formsBox{
            width: 90%;
            display: block
        }
        
    }

</style>

<?php $this->captureEnd("css")?>

<?php $this->captureStart("body");

include_once "modalSelectRamal.php";

?>

<div class="card formsBox shadow d-flex ">
    <div class="loginForm ">
        <div class="headerBox">
            <img src="{{URL_IMAGES}}/logo-ltc.jpg">
            <h2  class="mt-3 title">Login</h2>
            <h5 class="subtitle">Faça o login para entrar no sistema</h5>
        </div>
        <div class="caixaInputs">
            <form id="loginRealForm" autocomplete="off">
                <div class="wrapInput">
                    <input type="text" name="username" id="username" placeholder=" " autocomplete="off"/>
                    <label class="labelInput" >Usuário</label>
                    <i class="fa-regular fa-user iconInput fa-lg"></i>
                </div>
                <div class="wrapInput">
                    <input type="password" name="password" id="password" placeholder=" " autocomplete="off"/>
                    <label class="labelInput" >Senha</label>
                    <i class="fa-regular fa-lock iconInput fa-lg"></i>
                </div>
            </form>
        </div>
        <div class="caixaAcoes">
            <a id="forgotPassword" class="text-primary link" href="#">Esqueci minha senha</a>
            <button type="button" id="btnEntrar" class="btn btn-primary">Entrar</button>
        </div>
    </div>
    <div class="forgotForm ">
        <div class="headerBox">
            <img src="{{URL_IMAGES}}/logo-ltc.jpg">
            <h2 class="mt-3 title">Esqueci minha senha</h2>
            <h5 class="subtitle">Insira um email válido</h5>
        </div>
        <div class="caixaInputs">
            <div class="wrapInput">
                <input type="text" placeholder=" "/>
                <label class="labelInput" >Email</label>
                <i class="fa-regular fa-envelope iconInput fa-lg"></i>
            </div>
        </div>
        <div class="caixaAcoes">
            <a id="backLogin" class="text-primary link" href="#">Fazer login</a>
            <button class="btn btn-primary">Enviar</button>
        </div>
    </div>
    <div class="areaImage bg-primary ">

    </div>
</div>   



<?php $this->captureEnd("body")?>


<?php $this->captureStart("js")?>
<script>

let modalSelectRamal = new bootstrap.Modal("#modalSelectRamal", modalOption);

$("#username, #password").keypress(function(e){
    if(e.keyCode === 13){
        $("#btnEntrar").click();
    }
})

$("#btnEntrar").click(function(){
    let required = required_elements($("#loginRealForm input"));

    if(required.valid){
        let formInput = $("#loginRealForm").serializeObject();
        $.ajax({
            url : "{{URL_ROOT}}{{trim(route()->link('login-auth'),'/')}}",
            method: "POST",
            data: formInput            
        }).done(function(data){
            // StartLoading();
            $("#ramais").select2({
                data,
                dropdownParent: $('#modalSelectRamal'),
                width: "50%",
                closeOnSelect: true,
                language: 'pt-BR'
            });
            modalSelectRamal.show();
            // window.location.href = "{{route()->link('home')}}"
        })
    }else{
        alerta("Campos login e senha não podem ficar em branco.");
    }
    
})

$("#forgotPassword").click(function(){
    if(window.innerWidth < 576){
        $(".loginForm").css("right", "100%");
        $(".forgotForm").css("right", "0%");
    }else{
        $(".areaImage").css("right","40%")
    }
})

$("#backLogin").click(function(){
    if(window.innerWidth < 576){
        $(".loginForm").css("right", "0%");
        $(".forgotForm").css("right", "-100%");
    }else{
        $(".areaImage").css("right","0")
    }
    
})

const ValidateAuth = () => {
    $.ajax({
        url : "{{URL_ROOT}}{{trim(route()->link('validate-auth'),'/')}}",
        method: "POST",
        data: {ramal: $("#ramais").val()}            
    }).done(function(data){
        modalSelectRamal.hide()
        StartLoading();
        window.location.href = "{{route()->link('home')}}"
    })
}


</script>
<?php $this->captureEnd("js")?>