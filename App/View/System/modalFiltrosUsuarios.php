<div id="modalFiltros" class="modal fade" tabindex="-1" >
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
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select selectWithAll" multiple>
                                <option value="-1" selected>Todas</option>
                                <option value="ANSWERED">Atentidas</option>
                                <option value="NO ANSWER">Perdidas</option>
                                <option value="BUSY">Ocupadas</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">

                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="data_de" class="form-label">Data de</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="addon-wrapping"><i class="fa-regular fa-calendar"></i></span>
                                    <input id="data_de" class="form-control dateNoEmpty" name="data_de" type="text" value="<?=date('d/m/Y')?>" >
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="data_ate" class="form-label">Data até</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="addon-wrapping"><i class="fa-regular fa-calendar"></i></span>
                                    <input id="data_ate" class="form-control dateNoEmpty" name="data_ate" type="text" value="<?=date('d/m/Y')?>" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">

                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="origem" class="form-label">Origem</label>
                                <select id="origem" name="origem" class="form-select selectWithAll" multiple>
                                    <option value="-1" selected>Todos</option>
                                    <?php
                                        foreach($devices as $key => $d){
                                            
                                    ?>
                                        <option value="<?=$d['id']?>"><?=$d['text']?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="destino" class="form-label">Destino</label>
                                <select id="destino" name="destino" class="form-select selectWithAll" multiple>
                                    <option value="-1" selected>Todos</option>
                                    <?php
                                        foreach($devices as $key => $d){
                                        
                                    ?>
                                        <option value="<?=$d['id']?>"><?=$d['text']?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
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