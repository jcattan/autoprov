<?php
/**
 * Description of freepbx
 *
 * @package Provisioner
 */

namespace FreePBX\modules;

class epm_data_abstraction {
    public $config;
    public $configmod;
	public $global_cfg; // Déclaration explicite de la propriété global_cfg

    function __construct($config, $configmod) {
        $this->config = $config;
        $this->configmod = $configmod;
    }
    
    function all_products() {
        $temp = sql("SELECT * FROM autoprov_product_list WHERE id > 0",'getAll',DB_FETCHMODE_ASSOC);
        return($temp);
    }

    function all_devices() {
        $sql = 'SELECT autoprov_mac_list.id , autoprov_mac_list.mac , autoprov_model_list.model, autoprov_model_list.enabled , autoprov_brand_list.name, autoprov_mac_list.global_custom_cfg_data, autoprov_mac_list.config_override, autoprov_mac_list.template_id FROM autoprov_mac_list, autoprov_model_list, autoprov_brand_list WHERE autoprov_mac_list.model = autoprov_model_list.id AND autoprov_model_list.brand = autoprov_brand_list.id ORDER BY autoprov_mac_list.mac ASC';
        $temp = sql($sql,'getAll',DB_FETCHMODE_ASSOC);
        return($temp);
    }

    function escapeSimple($query) {
        global $db;
        return $db->escapeSimple($query);
    }

    function all_models() {
        $sql="SELECT autoprov_model_list.* FROM autoprov_model_list, autoprov_product_list WHERE autoprov_model_list.product_id = autoprov_product_list.id AND autoprov_model_list.enabled = 1 AND autoprov_product_list.hidden = 0 ORDER BY autoprov_model_list.model";
        $result1 = sql($sql, 'getAll',DB_FETCHMODE_ASSOC);
        return($result1);
    }

    function all_active_brands() {
        $sql="SELECT DISTINCT autoprov_brand_list.name, autoprov_brand_list.id FROM  autoprov_brand_list,autoprov_model_list WHERE autoprov_model_list.brand = autoprov_brand_list.id AND autoprov_model_list.enabled = 1 ORDER BY autoprov_brand_list.name";
        $data = sql($sql,'getAll', DB_FETCHMODE_ASSOC);
        return($data);
    }

    function all_models_by_product($product_id) {
        $sql="SELECT * FROM autoprov_model_list WHERE product_id = ".$product_id;
        $result1 = sql($sql, 'getAll',DB_FETCHMODE_ASSOC);
        return($result1);
    }

    function all_models_by_brand($brand_id) {
        $sql="SELECT autoprov_model_list.* FROM autoprov_model_list, autoprov_product_list WHERE autoprov_model_list.product_id = autoprov_product_list.id AND autoprov_model_list.enabled = 1 AND autoprov_product_list.hidden = 0 AND autoprov_model_list.brand = ".$brand_id." ORDER BY autoprov_model_list.model";
        $result1 = sql($sql, 'getAll',DB_FETCHMODE_ASSOC);
        return($result1);
    }

    function all_unknown_devices() {
        $sql = 'SELECT * FROM  autoprov_mac_list WHERE model = 0';
        $unknown_list = sql($sql,'getAll',DB_FETCHMODE_ASSOC);
        return($unknown_list);
    }

    function all_unused_registrations() {
        if($this->configmod->get('show_all_registrations')) {
            $not_added="SELECT devices.id, devices.description FROM devices WHERE tech in ('sip','pjsip') ORDER BY devices.id";
        } else {
            $not_added="SELECT devices.id, devices.description FROM devices WHERE tech in('sip','pjsip') AND devices.id not in (SELECT devices.id FROM devices, autoprov_line_list WHERE tech in ('sip','pjsip') AND autoprov_line_list.ext = devices.id)";
        }
        $result = sql($not_added,'getAll', DB_FETCHMODE_ASSOC);
        return($result);
    }

    function all_used_registrations() {
        $not_added="SELECT devices.id, devices.description FROM devices WHERE tech in ('sip','pjsip') AND devices.id in (SELECT devices.id FROM devices, autoprov_line_list WHERE tech in ('sip','pjsip') AND autoprov_line_list.ext = devices.id)";
        $result = sql($not_added,'getAll', DB_FETCHMODE_ASSOC);
        return($result);
    }

    function get_lines_from_device($device_id) {
        $sql = 'SELECT * FROM autoprov_line_list WHERE mac_id = '.$device_id. ' ORDER BY  autoprov_line_list.line ASC';
        $line_list = sql($sql,'getAll',DB_FETCHMODE_ASSOC);
        return($line_list);
    }

    function get_line_information($line_id) {
        $sql = 'SELECT * FROM autoprov_line_list WHERE luid = '.$line_id;
        $line_list = sql($sql,'getRow',DB_FETCHMODE_ASSOC);
        return($line_list);
    }
}
?>