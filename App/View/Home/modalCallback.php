<div id="callbackReport" class="modal fade" tabindex="-1" >
    <div class="modal-dialog modal-xl">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Lista de clientes aguardando retorno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="col-12">
                    <div class="row ">
                        <table id="tableCallback" class="table table-sm table-striped table-bordered w-100"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalRetornado" class="modal fade" tabindex="1" data-bs-parent="#callbackReport">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Status do retorno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="col-12 d-flex flex-column align-items-center">
                    <input id="idCallback" type="hidden" />
                    <label for="selectStatusCallback" class="form-label">Selecione o status do retorno</label>
                    <select id="selectStatusCallback"></select>
                    <button class="btn btn-primary mt-3" type="button" onclick="confirmaSalvarStatus()">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>