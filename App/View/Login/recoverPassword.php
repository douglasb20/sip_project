<?php $this->captureStart("css") ?>
<style>
    body{
        background-color: var(--bs-gray-200);
        overflow: hidden;
    }
    .loginForm{
        background-color : var(--bs-white);
        width            : 450px;
        height           : 500px;
        display          : flex;
        flex-direction   : column;
        border-radius: 8px
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
        display         : flex;
        padding         : 10px;
        align-items     : center;
        width           : 80%;
        align-self      : center;
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

<?php $this->captureEnd("css") ?>

<?php $this->captureStart("body") ?>

<?php
if(!$status){
    echo "  <div class='alert alert-danger w-75' role='alert'>
                {$msg}
            </div>";
}else{

?>

<div class="loginForm shadow">
    <div class="headerBox">
        <img src="{{URL_IMAGES}}/logo-ltc.jpg">
        <h2  class="mt-3 title">Criar senha</h2>
        <h5 class="subtitle">Crie sua nova senha abaixo</h5>
    </div>
    <div class="caixaInputs">
        <form id="recoverRealForm" autocomplete="off">
            <div class="wrapInput">
                <input type="password" name="password" id="password" placeholder=" " autocomplete="off"/>
                <label class="labelInput" >Nova senha</label>
                <i class="fa-regular fa-lock iconInput fa-lg"></i>
            </div>
            <div class="wrapInput">
                <input type="password" name="confirm_password" id="confirm_password" placeholder=" " autocomplete="off"/>
                <label class="labelInput" >Confirma senha</label>
                <i class="fa-regular fa-lock iconInput fa-lg"></i>
            </div>
        </form>
    </div>
    <div class="caixaAcoes justify-content-center">
        <button type="button" id="btnEnviar" class="btn btn-primary">Enviar</button>
    </div>
</div>

<div class='alert alert-success w-75 d-none' role='alert'>
    {{$msg}}
</div>
<?php
}

?>

<?php 

$this->captureEnd("body") ;
$this->captureStart("js") 

?>
<script>

$("#btnEnviar").click(function(){
    let required = required_elements($("#recoverRealForm input"));

    if(required.valid){
        ChangePassword();
    }else{
        alerta("Campos login e senha não podem ficar em branco.", "Erro validação", "error");
    }
});

const ChangePassword = () => {
    let formInput = $("#recoverRealForm").serializeObject();

    if(formInput.confirm_password !== formInput.password){
        alerta("Senhas não coincidem.", "Erro validação", "error");
        return;
    }

    $.ajax({
        url    : "{{route()->link('request-recover')}}{{$dados['id']}}",
        method : "POST",
        data   : formInput
    }).done(function(data){
        $(".loginForm").addClass("d-none");
        let clique = "A sua senha foi redefinida com sucesso!<br/>"
            clique += "Agora você pode acessar a sua conta utilizando a nova senha criada.";
        
        $(".alert").html(clique).removeClass("d-none");
    })
}

</script>
<?php $this->captureEnd("js") ?>