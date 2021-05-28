<div class="modal fade" id="CloneDlgPoste" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h4 class="modal-title"><?php echo _("Clone Poste") ?></h4>
                        </div>
                        <div class="modal-body">

                                <div class="element-container">
                                        <div class="row">
                                                <div class="col-md-12">
															<div class="row">
                                                                <div class="form-group">
                                                                        <div class="col-md-4">
																			<label class="control-label" for="ClonePosteSource"><?php echo _("Poste source")?></label>
                                                                                <i class="fa fa-question-circle fpbx-help-icon" data-for="ClonePosteSource"></i>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                                <select class="form-control selectpicker show-tick" data-style="" data-live-search-placeholder="Search" data-live-search="true" name="ClonePosteSource" id="ClonePosteSource">
                                                                                        <option value=""><?php echo _("Poste Source:")?></option>
                                                                                        <?php
                                                                                        $sql1 = "SELECT DISTINCT autoprov_mac_list.id, autoprov_mac_list.mac, autoprov_mac_list.model, autoprov_line_list.ext, autoprov_line_list.description FROM autoprov_mac_list, autoprov_line_list WHERE autoprov_mac_list.id = autoprov_line_list.mac_id AND autoprov_mac_list.template_id = '0'";
                                                                                        $poste_s_list = sql($sql1, 'getAll', DB_FETCHMODE_ASSOC);
                                                                                        foreach($poste_s_list as $row) {
                                                                                                echo '<option value="'.$row['id'].'">'.$row['mac'].' - '.$row['ext'].' - '.$row['description'].'</option>';
                                                                                        }
                                                                                        unset ($poste_s_list);
                                                                                        unset ($sql1);
                                                                                        ?>
                                                                        </select>
                                                                        </div>
                                                                </div>
															</div>
												</div>
										</div>
										<div class="row">
												<div class="col-md-12">
                                                        <span class="help-block fpbx-help-block" id="ClonePosteSource-help"><?php echo _("Choisir le poste Source.")?></span>
                                                </div>
                                        </div>
                                </div>
                                <div class="element-container">
                                        <div class="row">
                                                <div class="col-md-12">
                                                        <div class="row">
                                                                <div class="form-group">
                                                                        <div class="col-md-4">
                                                                                <label class="control-label" for="ClonePosteCible"><?php echo _("Poste cible")?></label>
                                                                                <i class="fa fa-question-circle fpbx-help-icon" data-for="ClonePosteCible"></i>
                                                                        </div>
  
  									<div class="col-md-8">
										<select class="form-control selectpicker show-tick" data-style="" data-live-search-placeholder="Search" data-live-search="true" name="ClonePosteCible" id="ClonePosteCible"></select>
									</div>
  
  
  
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="row">
                                                <div class="col-md-12">
                                                        <span class="help-block fpbx-help-block" id="ClonePosteCible-help"><?php echo _("Choisir le poste cible.")?></span>
                                                </div>
                                        </div>
                                </div>

                                
                        </div>
                        <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class='fa fa-times'></i> <?php echo _("Cancel")?></button>
                                <button type="button" class="btn btn-primary" name="button_save" id="CloneDlgPoste_bt_new"><i class='fa fa-check'></i> <?php echo _("Clone")?></button>
                        </div>
                </div>
        </div>
</div>

