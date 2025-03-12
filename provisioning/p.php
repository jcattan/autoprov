<?PHP

function getMethod() {
    $method = $_SERVER['REQUEST_METHOD'];
    $override = isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) ? $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] : (isset($_GET['method']) ? $_GET['method'] : '');
    if ($method == 'POST' && strtoupper($override) == 'PUT') {
        $method = 'PUT';
    } elseif ($method == 'POST' && strtoupper($override) == 'DELETE') {
        $method = 'DELETE';
    }
    return $method;
}

$bootstrap_settings['freepbx_auth'] = false;
if (!@include_once(getenv('FREEPBX_CONF') ? getenv('FREEPBX_CONF') : '/etc/freepbx.conf')) {
    include_once('/etc/asterisk/freepbx.conf');
}

$epm = FreePBX::create()->Autoprov;

define('PROVISIONER_BASE', $amp_conf['AMPWEBROOT'].'/admin/modules/_ap_phone_modules/');
$server_type = FreePBX::Autoprov()->configmod->get("server_type");

// Check if it's allowed in FreePBX through Endpoint Manager first
if ((!isset($server_type)) OR ($server_type != 'http')) {
    header('HTTP/1.1 403 Forbidden', true, 403);
    echo "<h1>"._("Error 403 Forbidden")."</h1>";
    echo _("Access denied!");
    die();
}

$provis_ip = FreePBX::Autoprov()->configmod->get("srvip");

if(((getMethod() == 'PUT') OR (getMethod() == 'POST'))) {
    // Write log files or other files to drive. not sussed out yet completely.
    header('HTTP/1.1 200 OK', true, 200);
    die();
}

if(getMethod() == "GET") {
    $filename = basename($_SERVER["REQUEST_URI"]);
    $web_path = 'http://'.$_SERVER["SERVER_NAME"].dirname($_SERVER["PHP_SELF"]).'/';

    if (preg_match('/7.4.3a/', $_SERVER['HTTP_USER_AGENT'])) {
        $str = '<flat-profile><Upgrade_Enable group="Provisioning/Firmware_Upgrade">Yes</Upgrade_Enable>';
        $str .= '<Upgrade_Rule group="Provisioning/Firmware_Upgrade">http://'.$provis_ip.'/current.bin</Upgrade_Rule></flat-profile>';
        echo $str;
        exit;
    }

    $filename = str_replace('p.php/','', $filename);
    $strip = str_replace('spa', '', $filename);

    require_once (PROVISIONER_BASE.'endpoint/base.php');
    $provisionerGlobals = new Provisioner_Globals();
    $data = $provisionerGlobals->dynamic_global_files($filename, FreePBX::Autoprov()->configmod->get("config_location"), $web_path);
    if($data !== FALSE) {
        echo $data;
    } else {
        header("HTTP/1.0 404 Not Found", true, 404);
        echo "<h1>"._("Error 404 Not Found")."</h1>";
        echo _("File not Found!");
        die();
    }
    exit;
} else {
    header('HTTP/1.1 403 Forbidden', true, 403);
    echo "<h1>"._("Error 403 Forbidden")."</h1>";
    echo _("Access denied!");
    die();
}