<div id="modalFormUser" class="modal fade" tabindex="-1" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Novo Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formUser" name="formUser" class="row g-3" autocomplete="off">
                    <div class="col-12 col-md-12">
                        <label for="user_login" class="form-label required-label">Login</label>
                        <input id="id" class="form-control " name="id" type="hidden" >
                        <input id="user_login" class="form-control required" name="user_login" type="text" autocomplete="off">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="user_pass" class="form-label">Senha</label>
                        <input id="user_pass" class="form-control " name="user_pass" type="password" >
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="confirm_pass" class="form-label">Confirma senha</label>
                        <input id="confirm_pass" class="form-control " name="confirm_pass" type="password" >
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="user_nome" class="form-label required-label">Nome</label>
                        <input id="user_nome" class="form-control required" name="user_nome" type="text" >
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="user_lastname" class="form-label">Sobrenome</label>
                        <input id="user_lastname" class="form-control " name="user_lastname" type="text" >
                    </div>
                    <div class="col-12 col-md-12">
                        <label for="user_email" class="form-label required-label">Email</label>
                        <input id="user_email" class="form-control required" name="user_email" type="email" >
                    </div>
                    <div class="col-12 col-md-12">
                        <label for="user_email" class="form-label required-label">Email</label>
                        <select name="id_sip" id="id_sip" class="form-select">
                            <option value="">Nenhum ramal</option>
                            <?php
                                foreach($sip as $key => $v){
                                    echo "<option value='{$v['id_sip']}'>{$v['id_sip']} - {$v['callerId']}</option>";
                                }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button onclick="CloseModal()" type="button" class="btn btn-outline-danger" >Cancelar</button>
                <button id="btnSalvar" type="button" class="btn btn-primary" >Salvar</button>
            </div>
        </div>
    </div>
</div>