<div id="modalPermissionsUser" class="modal fade" tabindex="-1" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title">Permissões</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formPermissionsUser" name="formPermissionsUser" class="row g-3" autocomplete="off">
                    <input type="hidden" id="id_user" name="id_user">
                    <div class="accordion" id="accordionExample">
                        <?php 
                            foreach($permissions as $key => $perm){
                        ?>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#<?=str_replace(" ", "_", $key)."Collapse"?>" aria-expanded="true" aria-controls="<?=str_replace(" ", "_", $key)."Collapse"?>">
                                        <?=$key?>
                                    </button>
                                </h2>
                                <div id="<?=str_replace(" ", "_", $key)."Collapse"?>" class="accordion-collapse collapse show" aria-labelledby="headingOne" >
                                    <div class="accordion-body">
                                        <ul class="permissions-list p-0">
                                        <?php
                                            foreach($perm as $key => $p){
                                        ?>
                                            <li class="user-select-none"><label><input class="form-check-input" name="permissions" type="checkbox" value="<?=$p['id']?>"> <?=$p['permission_label'] . ($p['type'] === "view" ? " - (View)": "")?></label></li>
                                        <?php
                                            }
                                        ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button onclick="CloseModal()" type="button" class="btn btn-outline-danger" >Cancelar</button>
                <button id="btnSalvarPermission" type="button" class="btn btn-primary" >Salvar</button>
            </div>
        </div>
    </div>
</div>