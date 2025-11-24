<?php
/**
 * Description of freepbx
 *
 * @author jcattan
 */

#[\AllowDynamicProperties]
class epm_data_abstraction {

    public $db;
    public $amp_conf;
    public $global_cfg;

    function __construct() {
        //Pull in shit from FreePBX! (it's all about the globals baaaaabbbyyyyyyyy)
        global $amp_conf, $db;

        //The only time dbh_asterisk is set is when we are inside the ARI Recording interface
        if(isset($_SESSION['dbh_asterisk'])) {
            $this->db = $_SESSION['dbh_asterisk'];
            //We don't want to reference the recordings directory when in the recording interface.
            $_SERVER["SCRIPT_FILENAME"] = str_replace("recordings/", "admin/", $_SERVER["SCRIPT_FILENAME"]);
        } else {
            $this->db = $db;
        }
    }

    function sql($sql, $type="query", $fetchmode=null) {
        global $db;
        try {
            $results = null;
            switch($type) {
                case 'getOne':
                    $results = $db->getOne($sql);
                    break;
                case 'getRow':
                    $results = $db->getRow($sql, $fetchmode ? $fetchmode : DB_FETCHMODE_ASSOC);
                    break;
                case 'getAll':
                    $results = $db->getAll($sql, $fetchmode ? $fetchmode : DB_FETCHMODE_ASSOC);
                    break;
                case 'getAssoc':
                    $results = $db->getAssoc($sql);
                    break;
                default:
                    $results = $db->query($sql);
                    break;
            }
            if (DB::isError($results)) {
                throw new Exception($results->getMessage() . " [SQL: $sql]");
										  
            }
            return $results;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    function sql_error($results) {
        echo "<h1>FATAL SQL ERROR! ::::: ";
        echo $results->getDebugInfo() . "SQL - <br /> $sql";
        echo "</h1>";
        echo "<pre>";
        var_dump(debug_backtrace());
        die();
    }

    function get_stored_globals() {
        //Get all global variables
        $temp = $this->sql("SELECT var_name, value FROM autoprov_global_vars",'getAssoc');
        $this->global_cfg = $temp;
        return($temp);
    }

    function all_products() {
        $temp = $this->sql("SELECT * FROM autoprov_product_list WHERE id > 0",'getAll',DB_FETCHMODE_ASSOC);
        return($temp);
    }

    function all_devices() {
        $sql = 'SELECT autoprov_mac_list.id , autoprov_mac_list.mac , autoprov_model_list.model, autoprov_model_list.enabled , autoprov_brand_list.name, autoprov_mac_list.global_custom_cfg_data, autoprov_mac_list.template_id FROM autoprov_mac_list , autoprov_model_list , autoprov_brand_list WHERE ( autoprov_model_list.id = autoprov_mac_list.model ) AND ( autoprov_model_list.brand = autoprov_brand_list.id )';
        $temp = $this->sql($sql,'getAll',DB_FETCHMODE_ASSOC);
        return($temp);
    }

    function escapeSimple($query) {
        global $db;
        if (is_numeric($query)) {
            return $query;
        }
        return $db->escapeSimple($query);
    }

    function all_models() {
        $sql="SELECT autoprov_model_list.* FROM autoprov_model_list, autoprov_product_list WHERE autoprov_model_list.product_id = autoprov_product_list.id AND autoprov_model_list.enabled = 1 AND autoprov_product_list.hidden = 0";
        $result1 = $this->sql($sql, 'getAll',DB_FETCHMODE_ASSOC);
        return($result1);
    }

    function all_active_brands() {
        $sql="SELECT DISTINCT autoprov_brand_list.name, autoprov_brand_list.id FROM  autoprov_brand_list,autoprov_model_list WHERE autoprov_model_list.brand = autoprov_brand_list.id AND autoprov_model_list.enabled = 1 AND autoprov_model_list.hidden = 0 AND autoprov_brand_list.installed = 1 AND autoprov_brand_list.hidden = 0";
        $data = $this->sql($sql,'getAll', DB_FETCHMODE_ASSOC);
        return($data);
    }

    function all_models_by_product($product_id) {
        $sql="SELECT * FROM autoprov_model_list WHERE product_id = ".$product_id;
        $result1 = $this->sql($sql, 'getAll',DB_FETCHMODE_ASSOC);
        return($result1);
    }

    function all_models_by_brand($brand_id) {
        $sql="SELECT autoprov_model_list.* FROM autoprov_model_list, autoprov_product_list WHERE autoprov_model_list.product_id = autoprov_product_list.id AND autoprov_model_list.enabled = 1 AND autoprov_product_list.hidden = 0 AND autoprov_model_list.brand = " . $brand_id;
        $result1 = $this->sql($sql, 'getAll',DB_FETCHMODE_ASSOC);
        return($result1);
    }

    function all_unknown_devices() {
        $sql = 'SELECT * FROM  autoprov_mac_list WHERE model = 0';
        $unknown_list = $this->sql($sql,'getAll',DB_FETCHMODE_ASSOC);
        return($unknown_list);
    }

    function all_unused_registrations() {
        if($this->global_cfg['show_all_registrations']) {
            $not_added="SELECT devices.id, devices.description FROM devices WHERE tech in ('sip','pjsip') ORDER BY devices.id";
        } else {
            $not_added="SELECT devices.id, devices.description FROM devices WHERE tech in('sip','pjsip') AND devices.id not in (SELECT devices.id FROM devices, autoprov_line_list WHERE tech in ('sip','pjsip') AND devices.id = autoprov_line_list.ext ) ORDER BY devices.id";
        }
        $result = $this->sql($not_added,'getAll', DB_FETCHMODE_ASSOC);
        return($result);
    }

    function all_used_registrations() {
        $not_added="SELECT devices.id, devices.description FROM devices WHERE tech in ('sip','pjsip') AND devices.id in (SELECT devices.id FROM devices, autoprov_line_list WHERE tech in ('sip','pjsip') AND devices.id = autoprov_line_list.ext ) ORDER BY devices.id";
        $result = $this->sql($not_added,'getAll', DB_FETCHMODE_ASSOC);
        return($result);
    }

    function get_lines_from_device($device_id) {
        $sql = 'SELECT * FROM autoprov_line_list WHERE mac_id = '.$device_id. ' ORDER BY  autoprov_line_list.line ASC';
        $line_list = $this->sql($sql,'getAll',DB_FETCHMODE_ASSOC);
        return($line_list);
    }

    function get_line_information($line_id) {
        $sql = 'SELECT * FROM autoprov_line_list WHERE luid = '.$line_id;
        $line_list = $this->sql($sql,'getRow',DB_FETCHMODE_ASSOC);
        return($line_list);
    }
}
?>
