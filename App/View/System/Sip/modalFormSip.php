<div id="modalFormSip" class="modal fade" tabindex="-1" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Novo Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning" role="alert">
                    <strong>Atenção:</strong> Os operadores aqui cadastrados, não salva no arquivo de configuração do asterisk.<br />
                    Essa funcionalidade será adicionada em breve.
                </div>
                <form id="formSip" name="formSip" class="row g-3" autocomplete="off">
                    <div class="col-12 col-md-12">
                        <label for="id_sip" class="form-label required-label">Ramal</label>
                        <input id="id_sip" class="form-control required" name="id_sip" type="text" autocomplete="off">
                    </div>
                    <div class="col-12 col-md-12">
                        <label for="callerId" class="form-label required-label">Nome</label>
                        <input id="callerId" class="form-control required" name="callerId" type="email" >
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button onclick="CloseModal()" type="button" class="btn btn-outline-danger" >Cancelar</button>
                <button id="btnSalvarSip" type="button" class="btn btn-primary" >Salvar</button>
            </div>
        </div>
    </div>
</div>