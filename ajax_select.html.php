<?php
/**
 * Autoprov FreePBX AJAX Select File
 *
 * @author JCattan
 * @license MPL / GPLv3/ LGPL
 * @package Autoprov Manager
 */
 
if (!@include_once(getenv('FREEPBX_CONF') ? getenv('FREEPBX_CONF') : '/etc/freepbx.conf')) {
    include_once('/etc/asterisk/freepbx.conf');
}
require_once('includes/ajax.inc');