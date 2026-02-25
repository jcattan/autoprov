<?PHP
/**
 * Endpoint Manager Devices Manager File
 *
 * BLEEEECKKKKKK, There I just puked all over this file. That's basically what it looks like in terms of code.
 * It's a hacked-together POS written by me (Andrew) and I really need to fix it ASAP!! ah!
 *
 * @author Andrew Nagy
 * @license MPL / GPLv2 / LGPL
 * @package Provisioner
 *
 */

//Set opened variables
$message = NULL;
$error_message = NULL;
$no_add = FALSE;
$final = NULL;
$button = NULL;
$searched = NULL;
$edit = NULL;
$mode = NULL;

$family_list = $endpoint->eda->all_products();
$full_device_list = $endpoint->eda->all_devices();
$ava_exts = $endpoint->display_registration_list();

if((empty($family_list)) && (empty($full_device_list))) {
    $endpoint->message['page:devices_manager'] = _("Welcome to Endpoint Manager")."!<br />"._("You have no products (Modules) installed, click")." <a href=\"config.php?display=epm_config&type=$type\">"._("here")."</a> "._("to install some");
    $no_add = TRUE;
    $endpoint->global_cfg['new'] = 1;
} elseif($endpoint->global_cfg['srvip'] == "") {
    $endpoint->error['page:devices_manager'] = "Your Global Variables are not set! Please head on over to <a href=\"config.php?type=$type&display=epm_advanced\">\"Advanced Settings\"</a> to setup your configuration";
    $no_add = TRUE;
} elseif(empty($ava_exts)) {
    //$message = "You have no more devices or extensions avalible to configure!";
    //$no_add = TRUE;
}

if((isset($_REQUEST['sub_type'])) AND ((!$no_add) OR (($_REQUEST['sub_type'] == "edit")))) {
    $sub_type = $_REQUEST['sub_type'];
    if(isset($_REQUEST['sub_type_sub'])) {
        $sub_type_sub = $_REQUEST['sub_type_sub'];
    } else {
        $sub_type_sub = "";
    }
} else {
    $sub_type = "";
}

switch ($sub_type) {
    //Edit Mode
    case "edit":
        $mode = "EDIT";
        switch ($sub_type_sub) {
            case "add_line_x":
                $_REQUEST['id'] = $_REQUEST['edit_id'];
                $mac_id = $endpoint->add_line($_REQUEST['id']);
                break;
            case "button_edit":
                if(empty($_REQUEST['edit_id'])) {
                    $endpoint->error['page:devices_manager'] = _("No Device Selected to Edit!")."!";
                } else {
                    $template_editor = TRUE;
                    $sql = "UPDATE  autoprov_mac_list SET  model =  '".$_REQUEST['model_list']."' WHERE  id =".$_REQUEST['edit_id'];
                    $endpoint->eda->sql($sql);
                    if ($_REQUEST['template_list'] == 0) {
                        $endpoint->edit_template_display($_REQUEST['edit_id'],1);
                    } else {
                        $endpoint->edit_template_display($_REQUEST['template_list'],0);
                    }
                }
                break;
            case "button_save":

                $sql = 'SELECT * FROM autoprov_line_list WHERE mac_id = '. $_REQUEST['edit_id'];

                $lines_list = $endpoint->eda->sql($sql,'getAll',DB_FETCHMODE_ASSOC);

                if (is_array($lines_list)) {
                    foreach($lines_list as $row) {
                        if (is_array($row) && isset($row['luid'])) {
                            $sql = "SELECT description FROM devices WHERE id = ".$endpoint->eda->escapeSimple($_REQUEST['ext_list_'.$row['luid']]);
                            $name = $endpoint->eda->sql($sql,'getOne');

                            $sql = "UPDATE autoprov_line_list SET line = '".$endpoint->eda->escapeSimple($_REQUEST['line_list_'.$row['luid']])."', ext = '".$endpoint->eda->escapeSimple($_REQUEST['ext_list_'.$row['luid']])."', description = '".$endpoint->eda->escapeSimple($name)."' WHERE luid =  ". $endpoint->eda->escapeSimple($row['luid']);
                            $endpoint->eda->sql($sql);
                        }
                    }
                }

                $sql = "UPDATE autoprov_mac_list SET template_id = '".$_REQUEST['template_list']."', model = '".$_REQUEST['model_list']."' WHERE id =  ". $_REQUEST['edit_id'];
                $endpoint->eda->sql($sql);


                $row = $endpoint->get_phone_info($_REQUEST['edit_id']);
                $endpoint->prepare_configs($row);

                $endpoint->message['edit_save'] = _("Saved")."!";
                $mode = NULL;
                break;
            case "delete":
                $sql = 'SELECT mac_id FROM autoprov_line_list WHERE luid = '.$_REQUEST['edit_id'] ;
                $mac_id = $endpoint->eda->sql($sql,'getOne');
                $row = $endpoint->get_phone_info($mac_id);

                $endpoint->delete_line($_REQUEST['edit_id'],FALSE);
                $_REQUEST['edit_id'] = $mac_id;
                break;
        }
        $edit_row=$endpoint->get_phone_info($_REQUEST['edit_id']);
        $edit_row['id'] = $_REQUEST['edit_id'];
        break;
    case "add" :
        $mac = $_REQUEST['mac'] ?? null;
        $model_list = $_REQUEST['model_list'] ?? null;
        $ext_list = $_REQUEST['ext_list'] ?? null;
        $template_list = $_REQUEST['template_list'] ?? null;
        $line_list = $_REQUEST['line_list'] ?? null;
        $mac_id = $endpoint->add_device($mac, $model_list, $ext_list, $template_list, $line_list);
        if($mac_id) {
            $phone_info = $endpoint->get_phone_info($mac_id);
            $endpoint->prepare_configs($phone_info);
        }
        break;
    case "edit_template" :
        if(empty($_REQUEST['edit_id'])) {
            $endpoint->error['page:devices_manager'] = _("No Device Selected to Edit!")."!";
        } else {
            $template_editor = TRUE;
            $sql = "UPDATE  autoprov_mac_list SET  model =  '".$_REQUEST['model_list']."' WHERE  id =".$_REQUEST['edit_id'];
            $endpoint->eda->sql($sql);
            if ($_REQUEST['template_list'] == 0) {
                $endpoint->edit_template_display($_REQUEST['edit_id'],1);
            } else {
                $endpoint->edit_template_display($_REQUEST['template_list'],0);
            }
        }
        break;
    case "delete_selected_phones":
        if(isset($_REQUEST['selected'])) {
            foreach($_REQUEST['selected'] as $key => $data) {
                $endpoint->delete_device($_REQUEST['selected'][$key]);
            }
        } else {
            $endpoint->error['page:devices_manager'] = _("No Phones Selected")."!";
        }
        break;
    case "delete_device":
        $endpoint->delete_device($_REQUEST['edit_id']);
        break;
    case "delete_line" :
        $endpoint->delete_line($_REQUEST['edit_id']);
        break;
    case "rebuild_selected_phones":
        if(isset($_REQUEST['selected'])) {
            foreach($_REQUEST['selected'] as $key => $data) {
                $phone_info = $endpoint->get_phone_info($_REQUEST['selected'][$key]);
                if(isset($_REQUEST['reboot'])) {
                    $endpoint->prepare_configs($phone_info);
                    $rebooted_msg = "& Rebooted";
                } else {
                    $endpoint->prepare_configs($phone_info,FALSE);
                    $rebooted_msg = "For";
                }
            }
            $endpoint->message['page:devices_manager'] = "Rebuilt Configs ".$rebooted_msg." Selected Phones";
        } else {
            $endpoint->message['page:devices_manager'] = _("No Phones Selected")."!";
        }
        break;
    case "rebuild_configs_for_all_phones" :
        $sql = "SELECT autoprov_mac_list.id FROM autoprov_mac_list, autoprov_brand_list, autoprov_product_list, autoprov_model_list WHERE autoprov_brand_list.id = autoprov_product_list.brand AND autoprov_product_list.id = autoprov_model_list.product_id AND autoprov_mac_list.model = autoprov_model_list.id ORDER BY autoprov_product_list.cfg_dir ASC";
        $mac_list = $endpoint->eda->sql($sql,'getAll',DB_FETCHMODE_ASSOC);
        foreach($mac_list as $data) {
            $phone_info = $endpoint->get_phone_info($data['id']);
            foreach($phone_info['line'] as $line) {
                $sql = "UPDATE autoprov_line_list SET description = '".$endpoint->eda->escapeSimple($line['description'])."' WHERE luid = ".$line['luid'];
                $endpoint->eda->sql($sql);
            }
            if(isset($_REQUEST['reboot'])) {
                $endpoint->prepare_configs($phone_info);
                    $rebooted_msg = "& Rebooted";
                } else {
                    $endpoint->prepare_configs($phone_info,FALSE);
                    $rebooted_msg = "For";
                }
            }
            $endpoint->message['page:devices_manager'] = "Rebuilt Configs ".$rebooted_msg." All Phones";
        break;
    case "reboot_brand" :
        $rb_brand = $_REQUEST['rb_brand'] ?? '';
        if($rb_brand != "") {
            $sql = 'SELECT autoprov_mac_list.id FROM autoprov_mac_list , autoprov_model_list , autoprov_brand_list , autoprov_product_list WHERE autoprov_brand_list.id = autoprov_model_list.brand AND autoprov_model_list.id = autoprov_mac_list.model AND autoprov_model_list.product_id = autoprov_product_list.id AND autoprov_brand_list.id = '.$rb_brand.' ORDER BY autoprov_product_list.cfg_dir ASC';
            $data = $endpoint->eda->sql($sql,'getAll',DB_FETCHMODE_ASSOC);
            if(!empty($data) && is_array($data)) {
                foreach($data as $row) {
                    if(!is_array($row) || !isset($row['id'])) {
                        continue;
                    }
                    if(!class_exists('ProvisionerConfig')) {
                        require(PHONE_MODULES_PATH.'setup.php');
                    }
                    $phone_info = $endpoint->get_phone_info($row['id']);

                    $class = "endpoint_" . $phone_info['directory'] . "_" . $phone_info['cfg_dir'] . '_phone';
					$base_class = "endpoint_" . $phone_info['directory']. '_base';
					$master_class = "endpoint_base";
					/**Fix for FreePBX Distro
					* I seriously want to figure out why ONLY the FreePBX Distro can't do autoloads.
					**/
					if(!class_exists($master_class)) {
						ProvisionerConfig::endpointsAutoload($master_class);
					}
					if(!class_exists($base_class)) {
						ProvisionerConfig::endpointsAutoload($base_class);
					}
					if(!class_exists($class)) {
						ProvisionerConfig::endpointsAutoload($class);
					}
					//end quick fix

                    $provisioner_lib = new $class();

                    $provisioner_lib->root_dir = PHONE_MODULES_PATH;

                    $provisioner_lib->engine = 'asterisk';
                    $provisioner_lib->engine_location = !empty($endpoint->global_cfg['asterisk_location']) ? $endpoint->global_cfg['asterisk_location'] : 'asterisk';
                    $provisioner_lib->system = 'unix';

                    //have to because of versions less than php5.3
                    $provisioner_lib->brand_name = $phone_info['directory'];
                    $provisioner_lib->family_line = $phone_info['cfg_dir'];

                    // Set line settings if available (need at least one line for reboot)
                    if (isset($phone_info['line']) && is_array($phone_info['line']) && !empty($phone_info['line'])) {
                        // Get first available line
                        $first_line = reset($phone_info['line']);
                        if (isset($first_line['ext']) && isset($first_line['tech'])) {
                            $provisioner_lib->settings['line'][0] = array(
                                'username' => $first_line['ext'],
                                'authname' => $first_line['ext'],
                                'tech' => $first_line['tech']
                            );
                        }
                    }
                    $provisioner_lib->reboot();
                    unset($provisioner_lib);
                }
                $endpoint->message['page:devices_manager'] = "Rebooted all ". $phone_info['name'] . " phones";
            } else {
                $endpoint->error['page:devices_manager'] = _("No Phones to Reboot");
            }
        } else {
            $endpoint->error['page:devices_manager'] = _("No Brand Selected for Reboot");
        }
        break;
    case "go" :
        $netmask = $_REQUEST['netmask'] ?? '';
        $sql = "UPDATE autoprov_global_vars SET value = '".$netmask."' WHERE var_name = 'nmap_search'";
        $endpoint->eda->sql($sql);
        $endpoint->global_cfg['nmap_search'] = $netmask;
        if ((isset($_REQUEST['nmap'])) AND ($_REQUEST['nmap'] == 1)) {
            $temp = $endpoint->discover_new($netmask);
        } else {
            $temp = $endpoint->discover_new($netmask, FALSE);
        }

        foreach($temp as $key => $data) {
            if ((!$data['endpoint_managed']) AND ($data['brand'])) {
                $final[$key] = $data;
                $final[$key]['id'] = $key;
                $sqln = "SELECT * FROM autoprov_model_list WHERE enabled = 1 AND brand =".$endpoint->eda->escapeSimple($data['brand_id']);
                $model_list = $endpoint->eda->sql($sqln,'getAll',DB_FETCHMODE_ASSOC);
                $j = 0;
                if (is_array($model_list)) {
                    foreach($model_list as $row) {
                        if (is_array($row)) {
                            $final[$key]['list'][$j] = $row;
                            $j++;
                        }
                    }
                }
            }
        }

        if(!$final) {
            $final = NULL;
            $endpoint->message['page:devices_manager'] = _("No Devices Found");
        }
        $searched = 1;
        break;
    case "add_selected_phones" :
        if(isset($_REQUEST['add'])) {
            foreach($_REQUEST['add'] as $num) {
                $mac = $_REQUEST['mac_'.$num] ?? null;
                $model_list = $_REQUEST['model_list_'.$num] ?? null;
                $ext_list = $_REQUEST['ext_list_'.$num] ?? null;
                $mac_id = $endpoint->add_device($mac, $model_list, $ext_list);
                if($mac_id) {
                    $phone_info = $endpoint->get_phone_info($mac_id);
                    if(isset($_REQUEST['reboot_sel'])) {
                        $endpoint->prepare_configs($phone_info,TRUE);
                    } else {
                        $endpoint->prepare_configs($phone_info,FALSE);
                    }
                }
            }
        }
        break;
    case "change_brand" :
        if(isset($_REQUEST['selected'])) {
            $brand_list_selected = $_REQUEST['brand_list_selected'] ?? 0;
            $model_list_selected = $_REQUEST['model_list_selected'] ?? 0;
            if(($brand_list_selected > 0) AND ($model_list_selected > 0)) {
                foreach($_REQUEST['selected'] as $key => $data) {
                    $sql = "UPDATE autoprov_mac_list SET global_custom_cfg_data = '', template_id = 0, global_user_cfg_data = '', config_files_override = '', model = '".$model_list_selected."' WHERE id =  ". $_REQUEST['selected'][$key];
                    $endpoint->eda->sql($sql);

                    $phone_info = $endpoint->get_phone_info($_REQUEST['selected'][$key]);
                    $endpoint->prepare_configs($phone_info);
                    $rebooted = "";
                    if(isset($_REQUEST['reboot_change'])) {
                        $endpoint->prepare_configs($phone_info);
                        $rebooted = " & Rebooted";
                    } else {
                        $endpoint->prepare_configs($phone_info,FALSE);
                    }
                    $endpoint->message['page:devices_manager'] = _("Saved").$rebooted."!";
                }
            } else {
                $endpoint->error['page:devices_manager'] = _("Please select a Brand and/or Model");
            }
        } else {
            $endpoint->error['page:devices_manager'] = _("No Phones Selected!");
        }
        break;
    case "rebuild_reboot" :
        $product_select = $_REQUEST['product_select'] ?? '';
        $template_selector = $_REQUEST['template_selector'] ?? '';
        if($product_select == "") {
            $message = _("Please select a product");
        } elseif($template_selector == "") {
            $message = _("Please select a template");
        } else {
            $sql = "SELECT autoprov_mac_list.id FROM autoprov_mac_list, autoprov_brand_list, autoprov_product_list, autoprov_model_list WHERE autoprov_brand_list.id = autoprov_product_list.brand AND autoprov_product_list.id = autoprov_model_list.product_id AND autoprov_mac_list.model = autoprov_model_list.id AND autoprov_product_list.id = '".$endpoint->eda->escapeSimple($product_select)."'";
            $data = $endpoint->eda->sql($sql,'getAll',DB_FETCHMODE_ASSOC);
            if (is_array($data)) {
                foreach($data as $row) {
                    if (!is_array($row) || !isset($row['id'])) {
                        continue;
                    }
                    $sql = "UPDATE autoprov_mac_list SET template_id = '".$endpoint->eda->escapeSimple($template_selector)."' WHERE id =  ". $endpoint->eda->escapeSimple($row['id']);
                    $endpoint->eda->sql($sql);
                    $phone_info = $endpoint->get_phone_info($row['id']);
                    if(isset($_REQUEST['reboot'])) {
                        $endpoint->prepare_configs($phone_info);
                        $rebooted_msg = "& Rebooted Phones";
                    } else {
                        $endpoint->prepare_configs($phone_info,FALSE);
                        $rebooted_msg = "";
                    }
                    foreach($phone_info['line'] as $line) {
                        $sql = "UPDATE autoprov_line_list SET description = '".$endpoint->eda->escapeSimple($line['description'])."' WHERE luid = ".$endpoint->eda->escapeSimple($line['luid']);
                        $endpoint->eda->sql($sql);
                    }
                }
            }
            $endpoint->message['page:devices_manager'] = "Rebuilt Configs " . $rebooted_msg;
        }
        break;
    case "mrebuild_reboot" :
        $model_select = $_REQUEST['model_select'] ?? '';
        $model_template_selector = $_REQUEST['model_template_selector'] ?? '';
        if($model_select == "") {
            $message = _("Please select a model");
        } elseif($model_template_selector == "") {
            $message = _("Please select a template");
        } else {
            $sql = "SELECT autoprov_mac_list.id FROM autoprov_mac_list, autoprov_brand_list, autoprov_product_list, autoprov_model_list WHERE autoprov_brand_list.id = autoprov_product_list.brand AND autoprov_product_list.id = autoprov_model_list.product_id AND autoprov_mac_list.model = autoprov_model_list.id AND autoprov_model_list.id = '".$endpoint->eda->escapeSimple($model_select)."'";
            $data = $endpoint->eda->sql($sql,'getAll',DB_FETCHMODE_ASSOC);
            if (is_array($data)) {
                foreach($data as $row) {
                    if (!is_array($row) || !isset($row['id'])) {
                        continue;
                    }
                    $sql = "UPDATE autoprov_mac_list SET template_id = '".$endpoint->eda->escapeSimple($model_template_selector)."' WHERE id =  ". $endpoint->eda->escapeSimple($row['id']);
                    $endpoint->eda->sql($sql);
                    $phone_info = $endpoint->get_phone_info($row['id']);
                    if(isset($_REQUEST['reboot'])) {
                        $endpoint->prepare_configs($phone_info);
                        $rebooted_msg = "& Rebooted Phones";
                    } else {
                        $endpoint->prepare_configs($phone_info,FALSE);
                        $rebooted_msg = "";
                    }
                    foreach($phone_info['line'] as $line) {
                        $sql = "UPDATE autoprov_line_list SET description = '".$endpoint->eda->escapeSimple($line['description'])."' WHERE luid = ".$endpoint->eda->escapeSimple($line['luid']);
                        $endpoint->eda->sql($sql);
                    }
                }
            }
            $endpoint->message['page:devices_manager'] = "Rebuilt Configs " . $rebooted_msg;
        }
        break;
}

//Refresh the list after processing
$devices_list = $endpoint->eda->all_devices();;

$i = 0;
$list = array();

$device_statuses = shell_exec($endpoint->global_cfg['asterisk_location']." -rx 'sip show peers'");

$device_statuses = explode("\n", $device_statuses);
$devices_status = array();
foreach($device_statuses as $key => $data) {
    preg_match('/(\d*)\/[\d]*/i', $data, $extout);
    preg_match('/\b(?:\d{1,3}\.){3}\d{1,3}\b/i', $data, $ipaddress);
    if(!empty($extout[1])) {
        if(preg_match('/OK \(.*\)/i', $data)) {
           $devices_status[$extout[1]]['status'] = TRUE;
           $devices_status[$extout[1]]['ip'] = isset($ipaddress[0]) ? $ipaddress[0] : '';
        } else {
            $devices_status[$extout[1]]['status'] = FALSE;
        }
    }
}

// Do the same for the pjsip peers
$device_statuses = shell_exec($endpoint->global_cfg['asterisk_location']." -rx 'pjsip show contacts'");
$device_statuses = explode("\n", $device_statuses);
foreach($device_statuses as $key => $data) {
    preg_match('/Contact:\s+(\d+)\/sip:.*/i', $data, $extout);
    preg_match('/\b(?:\d{1,3}\.){3}\d{1,3}\b/i', $data, $ipaddress);
    if(!empty($extout[1])) {
        if(preg_match('/Avail\s+\d+/i', $data)) {
           $devices_status[$extout[1]]['status'] = TRUE;
           $devices_status[$extout[1]]['ip'] = isset($ipaddress[0]) ? $ipaddress[0] : '';
        } else {
            $devices_status[$extout[1]]['status'] = FALSE;
        }
    }
}

foreach($devices_list as $devices_row) {
    $line_list = $endpoint->eda->get_lines_from_device($devices_row['id']);
    $list[$i] = $devices_row;
    $z = 0;
    if (($devices_row['template_id'] == 0) && (isset($devices_row['global_custom_cfg_data'])) ) {
        $list[$i]['template_name'] = "Custom-".$devices_row['mac'];
    } elseif((!isset($devices_row['custom_cfg_data'])) && ($devices_row['template_id'] == 0)) {
        $list[$i]['template_name'] = "N/A";
    } else {
        $sql = "SELECT name FROM autoprov_template_list WHERE id =".$devices_row['template_id'];
        $template_name = $endpoint->eda->sql($sql,'getOne');
        $list[$i]['template_name'] = $template_name;
    }
    if (!$devices_row['enabled']) {
        $list[$i]['model'] = $devices_row['model']."<i>(Disabled)</i>";
    }
    $list[$i]['master_id'] = $i;
    if (is_array($line_list)) {
        foreach($line_list as $line_row) {
            if (is_array($line_row) && isset($line_row['ext']) && isset($line_row['line']) && isset($line_row['description']) && isset($line_row['luid'])) {
                $list[$i]['line'][$z]['ext'] = $line_row['ext'];
                $list[$i]['line'][$z]['line'] = $line_row['line'];
                $list[$i]['line'][$z]['description'] = $line_row['description'];
                $list[$i]['line'][$z]['luid'] = $line_row['luid'];
                $list[$i]['line'][$z]['master_id'] = $i;
                $z++;
            }
        }
    }
    $ext = $list[$i]['line'][0]['ext'];

    $list[$i]['status']['status'] = isset($devices_status[$ext]['status']) ?$devices_status[$ext]['status'] : FALSE;
    $list[$i]['status']['ip'] = isset($devices_status[$ext]['ip']) ? $devices_status[$ext]['ip'] : FALSE;
    $list[$i]['status']['port'] = '';
    $i++;
}

$unknown_list = $endpoint->eda->all_unknown_devices();

if (is_array($unknown_list)) {
    foreach($unknown_list as $row) {	#Displays unknown phones in the database with edit and delete buttons
        if (!is_array($row) || !isset($row['mac'])) {
            continue;
        }
        $list[$i] = $row;

        $brand_info = $endpoint->get_brand_from_mac($row['mac']);

        $list[$i]['name'] = isset($brand_info['name']) ? $brand_info['name'] : '';
        $list[$i]['template_name'] = "N/A";
        $list[$i]['model'] = _("Unknown");
        $i++;
    }
}

$amp_send['AMPDBUSER'] = $amp_conf['AMPDBUSER'];
$amp_send['AMPDBPASS'] = $amp_conf['AMPDBPASS'];
$amp_send['AMPDBNAME'] = $amp_conf['AMPDBNAME'];

$sql = "SELECT DISTINCT autoprov_product_list.* FROM autoprov_product_list, autoprov_model_list WHERE autoprov_product_list.id = autoprov_model_list.product_id AND autoprov_model_list.hidden = 0 AND autoprov_model_list.enabled = 1 AND autoprov_product_list.hidden != 1 AND autoprov_product_list.cfg_dir !=  ''";

$template_list = $endpoint->eda->sql($sql, 'getAll', DB_FETCHMODE_ASSOC);
$i = 1;
$product_list = array();
$product_list[0]['value'] = 0;
$product_list[0]['text'] = "";
if (is_array($template_list)) {
    foreach($template_list as $row) {
        if (is_array($row) && isset($row['id']) && isset($row['short_name'])) {
            $product_list[$i]['value'] = $row['id'];
            $product_list[$i]['text'] = $row['short_name'];
            $i++;
        }
    }
}

$sql = "SELECT DISTINCT autoprov_model_list.* FROM autoprov_product_list, autoprov_model_list WHERE autoprov_product_list.id = autoprov_model_list.product_id AND autoprov_model_list.hidden = 0 AND autoprov_model_list.enabled = 1 AND autoprov_product_list.hidden != 1 AND autoprov_product_list.cfg_dir !=  ''";
$template_list = $endpoint->eda->sql($sql, 'getAll', DB_FETCHMODE_ASSOC);
$i = 1;
$model_list = array();
$model_list[0]['value'] = 0;
$model_list[0]['text'] = "";
if (is_array($template_list)) {
    foreach($template_list as $row) {
        if (is_array($row) && isset($row['id']) && isset($row['model'])) {
            $model_list[$i]['value'] = $row['id'];
            $model_list[$i]['text'] = $row['model'];
            $i++;
        }
    }
}

//initialize a Rain TPL object
if (isset($template_editor)) {

} else {
    $endpoint->tpl->assign("list", $list);
    $endpoint->tpl->assign("error", "");
    $serv_address = !empty($endpoint->global_cfg['nmap_search']) ? $endpoint->global_cfg['nmap_search'] : $_SERVER["SERVER_ADDR"].'/24';
    $endpoint->tpl->assign("netmask", $serv_address);
    $endpoint->tpl->assign("web_var", "?type=$type");
    $ma = $endpoint->models_available();
    if($ma != FALSE) {
        $endpoint->tpl->assign("models_ava", $ma);
    }
    $endpoint->tpl->assign("product_list", $product_list);
    $endpoint->tpl->assign("model_list", $model_list);
    $endpoint->tpl->assign("display_ext", $endpoint->display_registration_list());
    $endpoint->tpl->assign("brand_ava", $endpoint->brands_available());
    $endpoint->tpl->assign("unmanaged", $final);
    $endpoint->tpl->assign("button", $button);
    $endpoint->tpl->assign("searched", $searched);
    $endpoint->tpl->assign("edit", $edit);
    $endpoint->tpl->assign("amp_conf_serial", base64_encode(serialize($amp_send)));
    $endpoint->tpl->assign("no_add", $no_add);
    $endpoint->tpl->assign("mode", $mode);

    $edit_row['id'] = isset($edit_row['id']) ? $edit_row['id'] : '0';
    $endpoint->tpl->assign("edit_id", $edit_row['id']);

    if(isset($final)) {
        $_SESSION['dev_cache'] = base64_encode(serialize($final));
    }

    if (isset($mode) && ($mode == "EDIT")) {
        $ma = $endpoint->models_available($edit_row['model_id'],$edit_row['brand_id']);
        if($ma != FALSE) {
            $endpoint->tpl->assign("mac", $edit_row['mac']);
            $endpoint->tpl->assign("name", $edit_row['name']);
            $b=0;
            foreach($edit_row['line'] as $data) {
                $edit_row['line'][$data['line']]['reg_list'] = $endpoint->display_registration_list($data['luid']);
                $edit_row['line'][$data['line']]['line_list'] = $endpoint->linesAvailable($data['luid']);
                $b++;
            }
            if($b == 1) {
                $endpoint->tpl->assign("disabled_delete_line", 1);
            }
            $endpoint->tpl->assign("line_list_edit", $edit_row['line']);

            $endpoint->tpl->assign("brand_id", $edit_row['brand_id']);
            $endpoint->tpl->assign("models_ava", $ma);

            $endpoint->tpl->assign("display_templates", $endpoint->display_templates($edit_row['product_id'],$edit_row['template_id']));

        } else {
            $message = _("You have disabled/removed all models that correspond to this brand. Please enable them in 'Brand Configurations/Setup' before trying to edit this phone");
            $endpoint->tpl->assign("mode", NULL);
        }
    }

    //Prepare Message Box
    $endpoint->prepare_message_box();

    //draw the template
    echo $endpoint->tpl->draw( 'devices_manager' );
}
