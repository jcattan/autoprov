<?php if(!defined('IN_RAINTPL')){exit('Hacker attempt');}?><?php
	$tpl = new RainTPL( RainTPL::$tpl_dir . dirname("global_header"));
	$tpl->assign( $var );
		$tpl->draw(basename("global_header"));
?>
<div id="fond_blanc">
<h2><?php echo _('Device List');?></h2>
<hr>
<?php
	if( isset($var["show_error_box"]) ){
?>
<?php
		$tpl = new RainTPL( RainTPL::$tpl_dir . dirname("message_box"));
		$tpl->assign( $var );
				$tpl->draw(basename("message_box"));
?>
<?php
	}
?>
<script type="text/javascript" charset="utf-8">
   $(function(){
        $("select#brand_edit").change(function(){
            $.ajaxSetup({ cache: false });
            $.getJSON("config.php?type=tool&quietmode=1&handler=file&module=autoprov&file=ajax_select.html.php&atype=model",{id: $(this).val()}, function(j){
                var options = '';
                for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                }
                $("#model_new").html(options);
                $('#model_new option:first').attr('selected', 'selected');
                $("#template_list").html('<option></option>');
                $('#template_list option:first').attr('selected', 'selected');
            })
        })
    })
    $(function(){
        $("select#product_select").change(function(){
            $.ajaxSetup({ cache: false });
            $.getJSON("config.php?type=tool&quietmode=1&handler=file&module=autoprov&file=ajax_select.html.php&atype=template",{id: $(this).val()}, function(j){
                var options = '';
                for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                }
                $("#template_selector").html(options);
                $('#template_selector option:first').attr('selected', 'selected');
            })
        })
    })
    $(function(){
        $("select#model_select").change(function(){
            $.ajaxSetup({ cache: false });
            $.getJSON("config.php?type=tool&quietmode=1&handler=file&module=autoprov&file=ajax_select.html.php&atype=mtemplate",{id: $(this).val()}, function(j){
                var options = '';
                for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                }
                $("#model_template_selector").html(options);
                $('#model_template_selector option:first').attr('selected', 'selected');
            })
        })
    })
    $(function(){
        $("select#model_new").change(function(){
            $.ajaxSetup({ cache: false });
            $.getJSON("config.php?type=tool&quietmode=1&handler=file&module=autoprov&file=ajax_select.html.php&atype=template2",{id: $(this).val()}, function(j){
                var options = '';
                for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                }
                $("#template_list").html(options);
                $('#template_list option:first').attr('selected', 'selected');
            }),
            $.ajaxSetup({ cache: false });
            $.getJSON("config.php?type=tool&quietmode=1&handler=file&module=autoprov&file=ajax_select.html.php&atype=lines",{id: $(this).val()}, function(j){
                var options = '';
                for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                }
                $("#line_list").html(options);
                $('#line_list option:first').attr('selected', 'selected');
            })
        })
    })

    $(function(){
        $("select#brand_list_selected").change(function(){
            $.ajaxSetup({ cache: false });
            $.getJSON("config.php?type=tool&quietmode=1&handler=file&module=autoprov&file=ajax_select.html.php&atype=model",{id: $(this).val()}, function(j){
                var options = '';
                for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';
                }
                $("#model_list_selected").html(options);
                $('#model_list_selected option:first').attr('selected', 'selected');
            })
        })
    })
    function toggleDisplayAll(mode) {
        $(".toggle_all").each(function (i) {
            var rowClass = 'rowGroup'+$(this).attr('id');
            if(mode == 'expand') {
                $("#img2"+rowClass).removeClass().addClass('info icon-chevron-up');
                $("#img3"+rowClass).removeClass().addClass('info icon-chevron-up');
                $('.'+rowClass).show();
                $('#expander').hide();
                $('#collapser').show();
            } else {
                $("#img2"+rowClass).removeClass().addClass('info icon-chevron-down');
                $("#img3"+rowClass).removeClass().addClass('info icon-chevron-down');
                $('.'+rowClass).hide();
                $('#expander').show();
                $('#collapser').hide();
            }
        });
    }

    function toggleDisplay(tbl, rowClass) {
        if($("#img2"+rowClass).hasClass("icon-chevron-down")) {
            $("#img2"+rowClass).removeClass().addClass('info icon-chevron-up');
            $("#img3"+rowClass).removeClass().addClass('info icon-chevron-up');
            $('.'+rowClass).show();
        } else {
            $("#img2"+rowClass).removeClass().addClass('info icon-chevron-down');
            $("#img3"+rowClass).removeClass().addClass('info icon-chevron-down');
            $('.'+rowClass).hide();
        }
    }
    function addTableRow(jQtable){
        jQtable.each(function(){
            var $table = $(this);
            // Number of td's in the last table row
            var n = $('tr:last td', this).length;
            var tds = '<tr>';
            for(var i = 0; i < n; i++){
                tds += '<td>&nbsp;</td>';
            }
            tds += '</tr>';
            if($('tbody', this).length > 0){
                $('tbody', this).append(tds);
            }else {
                $(this).append(tds);
            }
        });
    }
    function add_device() {
        $('#adding').append('<input type="hidden" name="sub_type" value="add"/>');
        /*
        $('#adding').ajaxSubmit(function(responseText, statusText, xhr, $form) { 
            //ffalert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.'); 
            $(this).resetForm();
            $('#devList tr:last').after('<tr><td>stuff</td></tr>');
            return false;
        });
        */
        //return false //to prevent normal browser submit and page navigation 
        document.adding.submit();
    }
    function find_devices() {
        $('#spinner').toggle();
        $('#go').append('<input type="hidden" name="sub_type" value="go"/>');
        document.go.submit();
    }
    function managed_options(type) {
        $('#managed').append('<input type="hidden" name="sub_type" value="'+ type +'"/>');
        document.managed.submit();
    }
    function add_searched_devices() {
        $('#unmanaged').append('<input type="hidden" name="sub_type" value="add_selected_phones"/>');
        //$('#devList tr:last').after('<tr><td>stuff</td></tr>');
        document.unmanaged.submit();
    }
    function submit_global(type) {
        $('#globalmanaged').append('<input type="hidden" name="sub_type" value="'+ type +'"/>');
        document.globalmanaged.submit();
    }
    function submit_global2(type) {
        $('#globalmanaged2').append('<input type="hidden" name="sub_type" value="'+ type +'"/>');
        document.globalmanaged2.submit();
    }
    function submit_global3(type) {
        $('#globalmanaged3').append('<input type="hidden" name="sub_type" value="'+ type +'"/>');
        document.globalmanaged3.submit();
    }
    function submit_wtype(type,id) {
        $('#adding').append('<input type="hidden" name="edit_id" value="'+ id +'"/><input type="hidden" name="sub_type" value="'+ type +'"/>');
        document.adding.submit();
    }
    function edit_device(type,id,sub) {
        $('#adding').append('<input type="hidden" name="edit_id" value="'+ id +'"/><input type="hidden" name="sub_type" value="'+ type +'"/><input type="hidden" name="sub_type_sub" value="'+ sub +'"/>');
        document.adding.submit();
    }
    function delete_device(id) {
        if (confirm('Are you sure you want to delete this device?')) {
            submit_wtype('delete_device',id);
        }
    }
    function popitup(url, name, id) {
        if(id != '0') {
            newwindow=window.open(url + '&model_list=' + document.getElementById('model_new').value + '&template_list=' + document.getElementById('template_list').value + '&rand=' + new Date().getTime(),'name2','height=1000,width=950,scrollbars=yes,location=no');
            if (window.focus) {newwindow.focus()}
            return false;
        }
    }

    function submit_stype(type,id) {
        newwindow=window.open('config.php?display=epm_config&quietmode=1&handler=file&file=popup.html.php&module=autoprov&pop_type=edit_specifics&edit_id=' + id + '&rand=' + new Date().getTime(),'name2','height=700,width=750,scrollbars=yes,location=no');
        if (window.focus) {newwindow.focus()}
        return false;
    }
    function togglePhones(action) {
        $('#devList .device').prop('checked', action);
    }
</script>

<h3><?php
	if( $var["mode"] != 'EDIT' ){
?><?php echo _('Add')?><?php
	}
	else{
?><?php echo _('Edit')?><?php
	}
?> <?php echo _('Device')?></h3>
<form name="adding" id="adding" action='config.php?type=tool&amp;display=epm_devices' method='POST' />
<table align='center' width='97%'>
  <thead>
    <tr>
        <th width="7%""></th>
        <th width="13%" align='center'><?php echo _('MAC Address')?></th>
        <th width="13%" align='center'><?php echo _('Brand')?></th>
        <th width="10%" align='center'><?php echo _('Model of Phone')?></th>
        <th width="10%" align='center'><?php echo _('Line')?></th>
        <th width="19%" align='center'><?php echo _('Extension Number')?></th>
        <th width="15%" align='center'><?php echo _('Template')?></th>

        <th width="6%"></th>
        <th width="7%"></th>
    </tr>
  </thead>
  <tbody>
	<?php
	if( $var["no_add"] == FALSE ){
?>
    <tr>
    <td align='center' width='2%'>&nbsp;</td>

    <td align='center'>
	<?php
		if( $var["mode"] == 'EDIT' ){
?>
	<?php echo $var["mac"];?>
	<?php
		}
		else{
?>
        <input name='mac' type='text' tabindex='1' size="17" maxlength="17">
	<?php
		}
?></td>
    <td align='center'>  <label>
	<?php
		if( $var["mode"] == 'EDIT' ){
?>
            <?php echo $var["name"];?>
	<?php
		}
		else{
?>
            <select name="brand_list" id="brand_edit">
                <?php
			if( isset( $var["brand_ava"] ) && is_array( $var["brand_ava"] ) ){
				$counter1 = 0;
				foreach( $var["brand_ava"] as $key1 => $value1 ){ 
?>
                <option value="<?php echo $value1["value"];?>" <?php
				if( isset($value1["selected"]) ){
?>selected<?php
				}
?>><?php echo $value1["text"];?></option>
                <?php
					$counter1++;
				}
			}
?>
            </select></label>
	<?php
		}
?>
    </td>
    <td align='center'>
        <label>
	<?php
		if( $var["mode"] == 'EDIT' ){
?>
            <input name="display" type="hidden" value="epm_devices">
            <select name="model_list" id="model_new">
                <?php
			if( isset( $var["models_ava"] ) && is_array( $var["models_ava"] ) ){
				$counter1 = 0;
				foreach( $var["models_ava"] as $key1 => $value1 ){ 
?>
                <option value="<?php echo $value1["value"];?>" <?php
				if( !empty($value1["selected"]) ){
?>selected<?php
				}
?>><?php echo $value1["text"];?></option>
                <?php
					$counter1++;
				}
			}
?>
            </select>
	<?php
		}
		else{
?>
            <select name="model_list" id="model_new"><option></option></select>
	<?php
		}
?>
        </label></td>
<?php
		if( $var["mode"] == 'EDIT' ){
?>
    <td align='center'></td>
    <td align='center'></td>
<?php
		}
		else{
?>
    <td align='center'>
        <label>
            <select name="line_list" id="line_list" >
                <option></option>
            </select>
        </label></td>
    <td align='center'>
        <select name="ext_list" id="select">
            <?php
			if( isset( $var["display_ext"] ) && is_array( $var["display_ext"] ) ){
				$counter1 = 0;
				foreach( $var["display_ext"] as $key1 => $value1 ){ 
?>
            <option value="<?php echo $value1["value"];?>"><?php echo $value1["text"];?></option>
            <?php
					$counter1++;
				}
			}
?>
        </select>
        </label>
    </td>
<?php
		}
?>
    <td align='center'>  <div id="demo"><select name="template_list" id="template_list">
                <?php
		if( isset( $var["display_templates"] ) && is_array( $var["display_templates"] ) ){
			$counter1 = 0;
			foreach( $var["display_templates"] as $key1 => $value1 ){ 
?>
                <option value="<?php echo $value1["value"];?>" <?php
			if( isset($value1["selected"]) ){
?>selected<?php
			}
?>><?php echo $value1["text"];?></option>
                <?php
				$counter1++;
			}
		}
?>
            </select>
            <a href="#" onclick="return popitup('config.php?display=epm_config&amp;quietmode=1&amp;handler=file&amp;file=popup.html.php&amp;module=autoprov&amp;pop_type=edit_template&amp;edit_id=<?php echo $var["edit_id"];?>', 'Template Editor', '<?php echo $var["edit_id"];?>')"><i class='icon-pencil'></i></a></div>
        </label></td>
    <td align='center'>
	<?php
		if( $var["mode"] == 'EDIT' ){
?>
        <button type='submit' name='button_save' onclick="edit_device('edit',<?php echo $var["edit_id"];?>,'button_save');"><i class='icon-save blue'></i> <?php echo _('Save')?></button>
	<?php
		}
		else{
?>
        <button type='button' name='button_add' onclick="add_device();"><i class='icon-plus success'></i>&nbsp;<?php echo _('Add')?></button>
	<?php
		}
?>
    </td>

    <td align='center'><?php
		if( $var["mode"] != 'EDIT' ){
?><button type='reset'><i class='icon-rotate-left red'></i> <?php echo _('Reset')?><?php
		}
?></td>
</tr>
<?php
		if( isset( $var["line_list_edit"] ) && is_array( $var["line_list_edit"] ) ){
			$counter1 = 0;
			foreach( $var["line_list_edit"] as $key1 => $value1 ){ 
?>
<tr>
    <td align='center' width='2%'>&nbsp;</td>

    <td align='center'></td>
    <td align='center'></td>
    <td align='center'></td>
    <td align='center'>
        <label>
            <select name="line_list_<?php echo $value1["luid"];?>" id="line_list" >
                <?php
			if( isset( $value1["line_list"] ) && is_array( $value1["line_list"] ) ){
				$counter2 = 0;
				foreach( $value1["line_list"] as $key2 => $value2 ){ 
?>
                <option value="<?php echo $value2["value"];?>" <?php
				if( isset($value2["selected"]) ){
?>selected<?php
				}
?>><?php echo $value2["text"];?></option>
                <?php
					$counter2++;
				}
			}
?>
            </select>
        </label></td>
    <td align='center'>
        <select name="ext_list_<?php echo $value1["luid"];?>" id="select">
            <?php
			if( isset( $value1["reg_list"] ) && is_array( $value1["reg_list"] ) ){
				$counter2 = 0;
				foreach( $value1["reg_list"] as $key2 => $value2 ){ 
?>
            <option value="<?php echo $value2["value"];?>" <?php
				if( isset($value2["selected"]) ){
?>selected<?php
				}
?>><?php echo $value2["text"];?></option>
            <?php
					$counter2++;
				}
			}
?>
        </select>
        </label>
    </td>
    <td align='center'></td>
    <td align='center'><?php
			if( !isset($var["disabled_delete_line"]) ){
?><div id="demo"><a href="#" onclick="edit_device('edit',<?php echo $value1["luid"];?>,'delete');"><i class="red icon-remove" title="Delete Line from Device to the Left"></i></div><?php
			}
?></a></td>
    <td align='center'></td>
</tr>
<?php
				$counter1++;
			}
		}
?>
<?php
		if( $var["mode"] == 'EDIT' ){
?>
<tr>
    <td align='center'>&nbsp;</td>
    <td align='center'></td>
    <td align='center'></td>
    <td align='center'></td>
    <td align='center'>&nbsp;</td>
    <td align='center'>&nbsp;</td>
    <td align='center'></td>
    <td align='center'><div id="demo"><a href="#" onclick="edit_device('edit',<?php echo $var["edit_id"];?>,'add_line_x');"><i class="green icon-plus" title="Add a Line to the device currently being edited"></i></a></div></td>
    <td align='center'></td>
</tr> 
<?php
		}
?>
<?php
	}
?>
  </tbody>
</table>
</form>

<?php
	if( $var["searched"] == 1 ){
?>
<table width='90%' align='center'>
    <tr>
        <td align='center'>&nbsp;</td>
        <td align='center'>&nbsp;</td>
        <td align='center'>&nbsp;</td>
        <td colspan="3" align='center'><h3><?php echo _('Unmanaged Extensions')?></h3></td>
        <td align='center'>&nbsp;</td>
        <td align='center'>&nbsp;</td>
        <td align='center'>&nbsp;</td>
    </tr>
	<?php
		if( is_array($var["unmanaged"]) ){
?>
    <form id="unmanaged" action='' method='POST'>
		<?php
			if( isset( $var["unmanaged"] ) && is_array( $var["unmanaged"] ) ){
				$counter1 = 0;
				foreach( $var["unmanaged"] as $key1 => $value1 ){ 
?>
        <input name="mac_<?php echo $value1["id"];?>" type="hidden" value="<?php echo $value1["mac_strip"];?>">
        <input name="brand_<?php echo $value1["id"];?>" type="hidden" value="<?php echo $value1["brand_id"];?>">
        <tr id="<?php echo $value1["mac_strip"];?>">
            <td align='center' width='20'><input type="checkbox" name="add[]" value="<?php echo $value1["id"];?>"></td>
            <td align='center' width='148'><?php echo $value1["mac_strip"];?><br />(<?php echo $value1["ip"];?>)</td>
            <td width="188" align='center'><?php echo $value1["brand"];?></td>
            <td width="216" align='center'>

                <select name="model_list_<?php echo $value1["id"];?>">

	    <?php
				if( isset( $value1["list"] ) && is_array( $value1["list"] ) ){
					$counter2 = 0;
					foreach( $value1["list"] as $key2 => $value2 ){ 
?>

                    <option value="<?php echo $value2["id"];?>"><?php echo $value2["model"];?></option>

	      <?php
						$counter2++;
					}
				}
?>

                </select></td>
            <td width="141" align='center'>

            </td>

            <td width="276" align='center'>
                <select name="ext_list_<?php echo $value1["id"];?>" id="ext">

	    <?php
				if( isset( $var["display_ext"] ) && is_array( $var["display_ext"] ) ){
					$counter2 = 0;
					foreach( $var["display_ext"] as $key2 => $value2 ){ 
?>

                    <option value="<?php echo $value2["value"];?>"><?php echo $value2["text"];?></option>

	      <?php
						$counter2++;
					}
				}
?>

                </select></td>
            <td align='center' width='220'>&nbsp;</td>
            <td align='center' width='154'></td>
            <td align='center' width='73'>&nbsp;</td>
        </tr>
		<?php
					$counter1++;
				}
			}
?>
        <tr>
        <table width="90%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><center><input type="submit" name="button_add_selected_phones" onclick="add_searched_devices();" value="<?php echo _('Add Selected Phones')?>"><br /><input type="checkbox" name="reboot_sel">Reboot Phones</center></td>
            </tr>
        </table>
        </tr>
    </form>
	<?php
		}
?>
</table>
<?php
	}
?>

<form id="managed" action='config.php?type=tool&amp;display=epm_devices' method='POST'>
<h3><?php echo _('Current Managed Extensions')?></h3>

<button type="button" id="selecter"   style="zoom: 0.8" onclick="togglePhones(true)"  ><i class="info icon-check"       id="toggle_all_phones_on"  title="Click to Select All Phones"  ></i> Select All</button>
<button type="button" id="deselecter" style="zoom: 0.8" onclick="togglePhones(false)" ><i class="info icon-check-empty" id="toggle_all_phones_off" title="Click to Deselect All Phones"></i> Deselect All</button>
<button type="button" id="expander"   style="zoom: 0.8" onclick="toggleDisplayAll('expand')"  ><i class="info icon-chevron-down" id="toggle_all_img" title="Click to Expand All Line Information"  ></i> Expand All</button>
<button type="button" id="collapser"  style="zoom: 0.8" onclick="toggleDisplayAll('collapse')"><i class="info icon-chevron-up"   id="toggle_all_img" title="Click to Collapse All Line Information"></i> Collapse All</button>
<table width='97%' align='center' id='devList'>
    <thead>
    <tr class="headerRow">
        <th width="7%""></th>
        <th width="13%" align='center'><?php echo _('MAC Address')?></th>
        <th width="13%" align='center'><?php echo _('Brand')?></th>
        <th width="10%" align='center'><?php echo _('Model of Phone')?></th>
        <th width="10%" align='center'><?php echo _('Line')?></th>
        <th width="19%" align='center'><?php echo _('Extension Number')?></th>
        <th width="15%" align='center'><?php echo _('Template')?></th>
        <th width="6%"><?php echo _('Edit')?></th>
        <th width="7%"><?php echo _('Delete')?></th>
    </tr>
    </thead>
    <tbody>
	<?php
	if( isset( $var["list"] ) && is_array( $var["list"] ) ){
		$counter1 = 0;
		foreach( $var["list"] as $key1 => $value1 ){ 
?>
        <tr class="headerRow">
            <td align='center' width="7%"><i class="icon-off icon-large <?php
		if( $value1["status"]["status"] === TRUE ){
?>green<?php
		}
		else{
?>red<?php
		}
?>" alt="<?php echo $value1["status"]["ip"];?>:<?php echo $value1["status"]["port"];?>"></i><input type="checkbox" class="device" name="selected[]" value="<?php echo $value1["id"];?>"></td>
            <td align='center' width='13%'><?php echo $value1["mac"];?></td>
            <td width="13%" align='center'><?php echo $value1["name"];?></td>
            <td width="10%" align='center'><?php echo $value1["model"];?></td>
            <td width="10%" align='center'><div id="demo"><a><i class="info icon-chevron-down" id="img2rowGroup<?php echo $value1["master_id"];?>" onclick="toggleDisplay(document.getElementById('devList'),'rowGroup<?php echo $value1["master_id"];?>')" title="Click to Expand Line Information"></i></a></div></td>
            <td width="19%" align='center'><div id="demo"><a><i class="info icon-chevron-down" id="img3rowGroup<?php echo $value1["master_id"];?>" onclick="toggleDisplay(document.getElementById('devList'),'rowGroup<?php echo $value1["master_id"];?>')" title="Click to Expand Line Information"></i></a></div></td>
            <td align='center' width='15%'><a href="#" onclick="submit_stype('edit',<?php echo $value1["id"];?>);"><?php echo $value1["template_name"];?></a></td>
            <td align='center' width='6%'><div id="demo"><a href="#" onclick="submit_wtype('edit',<?php echo $value1["id"];?>);"><i class='blue icon-pencil' alt='<?php echo _('Edit')?>' title="Edit phone"></i></a></div></td>
            <td align='center' width='7%'><div id="demo"><a href="#" onclick="delete_device(<?php echo $value1["id"];?>);"><i class='red icon-trash' alt='<?php echo _('Delete')?>' title="Delete phone"></i></a></div></td>
        </tr>
        <?php
		if( isset( $value1["line"] ) && is_array( $value1["line"] ) ){
			$counter2 = 0;
			foreach( $value1["line"] as $key2 => $value2 ){ 
?>
        <tr class="rowGroup<?php echo $value2["master_id"];?> toggle_all" id="<?php echo $value2["master_id"];?>" style="display:none;">
            <td align='center' width='7%' ></td>
            <td align='center' width='13%'></td>
            <td width="13%" align='center'></td>
            <td width="10%" align='center'></td>
            <td width="10%" align='center'><?php echo $value2["line"];?></td>
            <td width="19%" align='center'><?php echo $value2["ext"];?> - <?php echo $value2["description"];?></td>
            <td align='center' width='15%'></td>
            <td align='center' width='6%'></td>
            <td align='center' width='7%'><div id="demo"><a href="#" onclick="submit_wtype('delete_line',<?php echo $value2["luid"];?>);"><i class="red icon-remove" alt='<?php echo _('Delete')?>' title='Delete Line'></i></a></div></td>
        </tr>
        <?php
				$counter2++;
			}
		}
?>
	<?php
			$counter1++;
		}
	}
?>
    </tbody>
</table>

<h4><?php echo _('Selected Phone(s) Options')?></h4>
<p><button style="width: 100px" type="submit" style="vertical-align:middle" name="button_delete_selected_phones" onclick="managed_options('delete_selected_phones');"><i class="icon-trash red"></i> <?php echo _('Delete')?></button> <?php echo ('Delete Selected Phones')?></p>
<p><button style="width: 100px" type="submit" name="button_rebuild_selected" onclick="managed_options('rebuild_selected_phones');"><i class="icon-refresh green"></i> <?php echo _('Rebuild')?></button> <?php echo _('Rebuild Configs for Selected Phones')?> (<label><input type="checkbox" name="reboot"><font size="-1">Reboot Phones</font></label>)</p>
<p><button style="width: 100px" style="vertical-align:middle" type="submit" name="button_update_phones" onclick="managed_options('change_brand');"><i class='blue icon-random'></i> <?php echo _('Update')?></button>
<?php echo _('Change Selected Phones to')?>&nbsp;
<select name="brand_list_selected" id="brand_list_selected"><option><?php echo _('Brand')?></option><?php
	if( isset( $var["brand_ava"] ) && is_array( $var["brand_ava"] ) ){
		$counter1 = 0;
		foreach( $var["brand_ava"] as $key1 => $value1 ){ 
?><option value="<?php echo $value1["value"];?>" <?php
		if( isset($value1["selected"]) ){
?>selected<?php
		}
?>><?php echo $value1["text"];?></option><?php
			$counter1++;
		}
	}
?></select> <select name="model_list_selected" id="model_list_selected"><option><?php echo _('Model')?></option></select> (<label><input type="checkbox" name="reboot_change"><font size="-1">Reboot Phones</font></label>)</p>
</form>

<h4><?php echo _('Global Phone Options')?></h4>
<?php
	if( $var["no_add"] == FALSE ){
?>
<p>
<form id='go' action='config.php?type=tool&amp;display=epm_devices' method='POST'>
  <button style="width: 100px" type="Submit" name="button_go" id="button_go" onclick="find_devices();"><i class='icon-search blue'></i> <?php echo _('Search')?></button>
  <?php echo _('Search for new devices in netmask')?>
  <input name="netmask" type="text" value="<?php echo $var["netmask"];?>">
  (<label><input name="nmap" type="checkbox" value="1" checked><font size="-1"><?php echo _('Use NMAP')?></font></label>)
</form>
</p>
<?php
	}
?>

<p><form action='' name='globalmanaged' id='globalmanaged' method='POST'>
<button style="width: 100px" type='Submit' name='button_rebuild_configs_for_all_phones' onclick="submit_global('rebuild_configs_for_all_phones');"><i class='icon-refresh green'></i> <?php echo _('Rebuild')?></button> <?php echo _('Rebuild Configs for All Phones')?>&nbsp;(<label><input type="checkbox" name="reboot"><font size="-1">Reboot Phones</font></label>)
</form></p>

<p><form action='' name='globalmanaged2' id='globalmanaged2' method='POST'>
<button style="width: 100px" type='Submit' name='button_reboot_this_brand' onclick="submit_global2('reboot_brand');"><i class='icon-off red'></i> <?php echo _('Reboot')?></button> <?php echo _('Reboot This Brand')?> <select name="rb_brand"><?php
	if( isset( $var["brand_ava"] ) && is_array( $var["brand_ava"] ) ){
		$counter1 = 0;
		foreach( $var["brand_ava"] as $key1 => $value1 ){ 
?><option value="<?php echo $value1["value"];?>"><?php echo $value1["text"];?></option><?php
			$counter1++;
		}
	}
?></select>
</form></p>

<p><form action='' name='globalmanaged3' id='globalmanaged3' method='POST'>
<button style="width: 100px" type="submit" name="button_rebuild_reboot" onclick="submit_global3('rebuild_reboot');"><i class="icon-random blue"></i> <?php echo _('Configure')?></button>
<?php echo _('Reconfigure all')?> (products) <select name="product_select" id="product_select"><?php
	if( isset( $var["product_list"] ) && is_array( $var["product_list"] ) ){
		$counter1 = 0;
		foreach( $var["product_list"] as $key1 => $value1 ){ 
?><option value="<?php echo $value1["value"];?>"><?php echo $value1["text"];?></option><?php
			$counter1++;
		}
	}
?></select> <?php echo _('with')?>
<label><select name="template_selector" id="template_selector"><option></option></select></label>
(<label><input type="checkbox" name="reboot"><font size='-1'>Reboot Phones</font></label>)
</form></p>

<p><form action='' name='globalmanaged4' id='globalmanaged4' method='POST'>
<button style="width: 100px" type="submit" name="button_rebuild_reboot" onclick="submit_global3('mrebuild_reboot');"><i class="icon-random blue"></i> <?php echo _('Configure')?></button>
<?php echo _('Reconfigure all')?> (models) <select name="model_select" id="model_select"><?php
	if( isset( $var["model_list"] ) && is_array( $var["model_list"] ) ){
		$counter1 = 0;
		foreach( $var["model_list"] as $key1 => $value1 ){ 
?><option value="<?php echo $value1["value"];?>"><?php echo $value1["text"];?></option><?php
			$counter1++;
		}
	}
?></select> <?php echo _('with')?>
<label><select name="model_template_selector" id="model_template_selector"><option></option></select></label>
(<label><input type="checkbox" name="reboot"><font size='-1'>Reboot Phones</font></label>)
</form></p>

</form>
</div>

<?php
	if( !isset($var["disable_help"]) ){
?>
<script>
    $("#demo img[title]").tooltip();
</script>
<?php
	}
?>
<script>
    $("#collapser").hide();
</script>
<?php
	$tpl = new RainTPL( RainTPL::$tpl_dir . dirname("global_footer"));
	$tpl->assign( $var );
		$tpl->draw(basename("global_footer"));
?>
