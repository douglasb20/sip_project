<div id="modalFiltrosSip" class="modal fade" tabindex="-1" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Filtros</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formFiltro" class="row g-3">
                    <div class="col-12 col-md-12">
                        <div class="mb-3">
                            <label for="sip_status" class="form-label">Status</label>
                            <select id="sip_status" name="sip_status" class="form-select selectWithAll" multiple>
                                <option value="-1" >Todas</option>
                                <option value="1" selected>Ativo</option>
                                <option value="2">Inativo</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btnFiltrar" type="button" class="btn btn-primary" >Filtrar</button>
            </div>
        </div>
    </div>
</div>