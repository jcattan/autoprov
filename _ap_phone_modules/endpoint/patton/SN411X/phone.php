<?php
/**
 * SN411X Modules Phone File
 *
 * @author jcattan
 * @license MPL / GPLv2 / LGPL
 * @package freepbx
 */
class endpoint_patton_SN411X_phone extends endpoint_patton_base {

    public $family_line = 'SN411X';
	
    function parse_lines_hook($line_data, $line_total) {
        
        $line = $line_data['line'];
        $line_data['fxsnum'] = $line - 1;

        return($line_data);
    }
}
?>