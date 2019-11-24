<?php
/**
 * Grandstream GXW4XXX Phone File
 *
 * @author JCattan
 * @license MPL / GPLv2 / LGPL
 * @package Provisioner
 */

	

class endpoint_grandstream_GXW4XXX_phone extends endpoint_grandstream_base {


	public $family_line = 'GXW4XXX';



	function reboot() {
		if(($this->engine == "asterisk") AND ($this->system == "unix")) {
			// exec($this->engine_location . " -rx 'sip notify polycom-check-cfg " . $this->settings['line'][0]['username'] . "'");
			exec($this->engine_location . " -rx 'sip notify reboot-snom " . $this->settings['line'][0]['username'] . "'");
		}
	}

	function prepare_for_generateconfig() {


		if ($this->options['update'] == 0) {
                                $this->options['config_server'] = $this->server[1]['ip'];
                                                        }
                                else { $this->options['config_server'] = "";
                                        }


		parent::prepare_for_generateconfig();
                // Grandstreams support lines 2-48, so let's add them if they're set
                for ($i = 1; $i < 48; $i++) {
                    $this->lines[$i]['options']['line_active'] = (isset($this->lines[$i]['secret']) ? '1' : '0');
                }

				if(isset($this->options['ext1'])) {
					foreach($this->options['ext1'] as $key => $data) {
						if ($this->options['ext1'][$key]['mode'] == '999') {
							
							$this->options['ext1'][$key]['account'] = '';
							$this->options['ext1'][$key]['name'] = '';
							$this->options['ext1'][$key]['uid'] = '';
							$this->options['ext1'][$key]['mode'] = '';
						}
						
						$this->options['ext1'][$key]['pnum'] = (strlen($key) == '1') ? '0'.$key : $key;
					}
				}
				
				if(isset($this->options['ext2'])) {
					foreach($this->options['ext2'] as $key => $data) {
						if ($this->options['ext2'][$key]['mode'] == '999') {
							
							$this->options['ext2'][$key]['account'] = '';
							$this->options['ext2'][$key]['name'] = '';
							$this->options['ext2'][$key]['uid'] = '';
							$this->options['ext2'][$key]['mode'] = '';
						}
						
						$this->options['ext2'][$key]['pnum'] = (strlen($key) == '1') ? '0'.$key : $key;
					}
				}
	}
	
}
