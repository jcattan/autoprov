<?PHP
/**
 * Endpoint Manager Template Manager File
 *
 * @author Andrew Nagy
 * @license MPL / GPLv2 / LGPL
 * @package Provisioner
 */
$no_add = FALSE;
$default_display = FALSE;

$product_list = "SELECT * FROM autoprov_product_list WHERE id > 0";
$product_list = $endpoint->eda->sql($product_list,'getAll', DB_FETCHMODE_ASSOC);

$mac_list = "SELECT * FROM autoprov_mac_list";
$mac_list = $endpoint->eda->sql($mac_list, 'getAll', DB_FETCHMODE_ASSOC);

if((!$product_list) && (!$mac_list)) {
    $endpoint->message['general'] = _("Welcome to Endpoint Manager")."!<br />"._("You have no products (Modules) installed, click")." <a href=\"config.php?display=epm_config&type=$type\">"._("here")."</a> "._("to install some");
    $no_add = TRUE;
} elseif(!$product_list) {
    $endpoint->message['general'] = "Thanks for upgrading to version 2.0! Please head on over to <a href=\"config.php?display=epm_config&type=$type\">\"Brand Configurations/Setup\"</a> to setup and install phone configurations";
    $no_add = TRUE;
}

if(isset($_REQUEST['edit_template'])) {
    $endpoint->edit_template_display($_REQUEST['id'],$_REQUEST['custom']);
} elseif(isset($_REQUEST['button_save'])) {
    if(($_REQUEST['model_class'] == 0) OR (empty($_REQUEST['template_name'])) OR ($_REQUEST['model_clone'] == 0)) {
        $endpoint->error['general'] = "No Valid Model/Template/Name Selected!";
        $default_display = TRUE;
    } else {
        $sql = "INSERT INTO autoprov_template_list (product_id, name, model_id) VALUES ('".$_REQUEST['model_class']."','".$_REQUEST['template_name']."','".$_REQUEST['model_clone']."')";
        $endpoint->eda->sql($sql);
        $sql = "SELECT id FROM autoprov_template_list WHERE product_id = '".$_REQUEST['model_class']."' AND name = '".$_REQUEST['template_name']."'";
        $id = $endpoint->eda->sql($sql,'getOne');
        $endpoint->edit_template_display($id,0);
    }
} elseif(isset($_REQUEST['delete_template'])) {
    $sql = "DELETE FROM autoprov_template_list WHERE id = ". $_REQUEST['id'];
    $endpoint->eda->sql($sql);
    $sql = "UPDATE autoprov_mac_list SET template_id = 0 WHERE template_id = ".$_REQUEST['id'];
    $endpoint->eda->sql($sql);
    $default_display = TRUE;
    $endpoint->message['general'] = _('Deleted');
} elseif((isset($_REQUEST['button_save_template'])) AND (isset($_REQUEST['custom']))) {
    $endpoint->save_template($_REQUEST['id'],$_REQUEST['custom'],$_REQUEST);
    $default_display = TRUE;
    if(empty($endpoint->error)) {
        $endpoint->message['general'] = _('Saved');
    }
} else {
    $default_display = TRUE;
}

if($default_display) {
    $sql = 'SELECT autoprov_template_list.*, autoprov_product_list.short_name as model_class, autoprov_model_list.model as model_clone, autoprov_model_list.enabled FROM autoprov_template_list, autoprov_model_list, autoprov_product_list WHERE autoprov_model_list.hidden = 0 AND autoprov_template_list.model_id = autoprov_model_list.id AND autoprov_template_list.product_id = autoprov_product_list.id';
    $template_list = $endpoint->eda->sql($sql, 'getAll', DB_FETCHMODE_ASSOC);
    $i = 0;
    $row_out = array();
    foreach($template_list as $row) {
        $row_out[$i] = $row;
        $row_out[$i]['custom'] = 0;
        if(!$row['enabled']) {
            $row_out[$i]['model_clone'] = $row_out[$i]['model_clone']."<i>(Disabled)</i>";
        }
        $i++;
    }

    $sql = 'SELECT autoprov_mac_list.mac, autoprov_mac_list.id, autoprov_mac_list.model, autoprov_model_list.model as model_clone, autoprov_product_list.short_name as model_class FROM autoprov_mac_list, autoprov_model_list, autoprov_product_list WHERE  autoprov_product_list.id = autoprov_model_list.product_id AND autoprov_mac_list.global_custom_cfg_data IS NOT NULL AND autoprov_model_list.id = autoprov_mac_list.model AND autoprov_mac_list.template_id = 0';

    $template_list = $db->getAll($sql, array(), DB_FETCHMODE_ASSOC);
    foreach($template_list as $row) {
        $sql = 'SELECT  description , line FROM  autoprov_line_list WHERE  mac_id ='. $row['id'].' ORDER BY line ASC';
        $line_list = $db->getAll($sql, array(), DB_FETCHMODE_ASSOC);
        $description = "";
        $c = 0;
        foreach($line_list as $line_row) {
            if($c > 0) {
              $description .= ", ";
            }
            $description .= $line_row['description'];
            $c++;
        }
        $row_out[$i] = $row;
        $row_out[$i]['custom'] = 1;
        $row_out[$i]['name'] = $row['mac'] . "-(" .$description.")";
        $i++;
    }

    $sql = "SELECT DISTINCT autoprov_product_list.* FROM autoprov_product_list, autoprov_model_list WHERE autoprov_product_list.id = autoprov_model_list.product_id AND autoprov_model_list.hidden = 0 AND autoprov_model_list.enabled = 1 AND autoprov_product_list.hidden != 1 AND autoprov_product_list.cfg_dir !=  ''";

    $template_list = $db->getAll($sql, array(), DB_FETCHMODE_ASSOC);
    $i = 1;
    $class_row[0]['value'] = 0;
    $class_row[0]['text'] = "";
    foreach($template_list as $row) {
        $class_row[$i]['value'] = $row['id'];
        $class_row[$i]['text'] = $row['short_name'];
        $i++;
    }
    $endpoint->tpl->assign("amp_conf_serial", "1");
    $endpoint->tpl->assign("templates_list", $row_out);
    $endpoint->tpl->assign("class_list", $class_row);
    $endpoint->tpl->assign("no_add", $no_add);
    $endpoint->tpl->assign("debug", $debug);

    $endpoint->prepare_message_box();
    echo $endpoint->tpl->draw( 'template_manager' );
}
