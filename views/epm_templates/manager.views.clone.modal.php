<div class="modal fade" id="CloneDlgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                                <h4 class="modal-title"><?php echo _("Clone Template") ?></h4>
                        </div>
                        <div class="modal-body">

                                <div class="element-container">
                                        <div class="row">
                                                <div class="col-md-12">
                                                        <div class="row">
                                                                <div class="form-group">
                                                                        <div class="col-md-4">
                                                                                <label class="control-label" for="CloneTemplateName"><?php echo _("Template Name")?></label>
                                                                                <i class="fa fa-question-circle fpbx-help-icon" data-for="CloneTemplateName"></i>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                                <input type="text" class="form-control" id="CloneTemplateName" name="NewTemplateName" value="" placeholder="<?php echo _("New Name Template....")?>">
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="row">
                                                <div class="col-md-12">
                                                        <span class="help-block fpbx-help-block" id="CloneTemplateName-help"><?php echo _("Nom du nouveau template")?></span>
                                                </div>
                                        </div>
                                </div>

                                <div class="element-container">
                                        <div class="row">
                                                <div class="col-md-12">
                                                        <div class="row">
                                                                <div class="form-group">
                                                                        <div class="col-md-4">
                                                                                <label class="control-label" for="CloneProductSelect"><?php echo _("Product Select")?></label>
                                                                                <i class="fa fa-question-circle fpbx-help-icon" data-for="CloneProductSelect"></i>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                                <select class="form-control selectpicker show-tick" data-style="" data-live-search-placeholder="Search" data-live-search="true" name="CloneProductSelect" id="CloneProductSelect">
                                                                                        <option value=""><?php echo _("Select Product:")?></option>
                                                                                        <?php
                                                                                        $sql = "SELECT * FROM autoprov_template_list";
                                                                                        $template_list = sql($sql, 'getAll', DB_FETCHMODE_ASSOC);
                                                                                        foreach($template_list as $row) {
                                                                                                echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                                                                                        }
                                                                                        unset ($template_list);
                                                                                        unset ($sql);
                                                                                        ?>
                                                                        </select>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                        <div class="row">
                                                <div class="col-md-12">
                                                        <span class="help-block fpbx-help-block" id="CloneProductSelect-help"><?php echo _("Choisir le template a cloner.")?></span>
                                                </div>
                                        </div>
                                </div>

                                
                        </div>
                        <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class='fa fa-times'></i> <?php echo _("Cancel")?></button>
                                <button type="button" class="btn btn-primary" name="button_save" id="CloneDlgModal_bt_new"><i class='fa fa-check'></i> <?php echo _("Clone")?></button>
                        </div>
                </div>
        </div>
</div>

