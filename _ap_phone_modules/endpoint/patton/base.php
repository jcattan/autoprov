<?PHP
/****
 * Patton FXS Base File
 *
 * @author jcattan
 * @license MPL / GPLv2 / LGPL
 * @package Provisioner
 */
abstract class endpoint_patton_base extends endpoint_base {
	
	public $brand_name = 'patton';
	function reboot() {
		if(($this->engine == "asterisk") AND ($this->system == "unix")) {
			// exec($this->engine_location." -rx 'sip notify polycom-check-cfg ".$this->lines[1]['ext']."'");
			exec($this->engine_location . " -rx 'sip notify polycom-check-cfg " . $this->settings['line'][0]['username'] . "'");
		}
	}
	function prepare_for_generateconfig() {
//patton likes lower case letters in its mac address
		$this->mac = strtolower($this->mac);
		parent::prepare_for_generateconfig();
		for ($i = 1; $i < 50; $i++) {
                    $this->lines[$i]['line_active'] = (isset($this->lines[$i]['secret']) ? '1' : '0');
               			}
}
    function parse_lines_hook($line_data, $line_total) {
        $line_data['line_active'] = (isset($line_data['secret']) ? '1' : '0');
        return($line_data);
    }
}
?>