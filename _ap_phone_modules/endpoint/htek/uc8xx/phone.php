<?php

/**
 * Hanlong / Htek UC8xx Phone File
 *
 * @author Andrew Nagy
 * @edit by jcattan
 * @license MPL / GPLv2 / LGPL
 * @package Provisioner
 * 
 */
class endpoint_htek_uc8xx_phone extends endpoint_htek_base {

    public $family_line = 'uc8xx';

    function parse_lines_hook($line_data, $line_total) {
        $line_data['line_active'] = (isset($line_data['secret']) ? '1' : '0');
        return($line_data);
    }

    function prepare_for_generateconfig() {
        parent::prepare_for_generateconfig();

        if (isset($this->settings['dialplan'])) {
            $this->settings['dialplan'] = str_replace("+", "%2B", $this->settings['dialplan']);
        }

    }
}
