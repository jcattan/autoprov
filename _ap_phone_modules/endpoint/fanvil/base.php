<?PHP

/**
 * Fanvil Base File
 *
 * @author Jeremie CATTAN
 * @license MPL / GPLv2 / LGPL
 * @package autoprov
 */
abstract class endpoint_fanvil_base extends endpoint_base {

    public $brand_name = 'fanvil';
    protected $use_system_dst = FALSE;

    function reboot() {
        if (($this->engine == "asterisk") AND ($this->system == "unix")) {
             exec($this->engine_location . " -rx 'pjsip send notify reboot-yealink endpoint " . $this->settings['line'][0]['username'] . "'");
        }
    }

    function prepare_for_generateconfig() {
        parent::prepare_for_generateconfig();
        preg_match('/.*(-|\+)(\d*):(\d*)/i', $this->timezone['timezone'], $matches);
        switch ($matches[3]) {
            case '30':
                $point = '.5';
                break;
            default:
                $point = '';
                break;
        }
        $this->timezone['timezone'] = $matches[1] . $matches[2] . $point;
    }

}
