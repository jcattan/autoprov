<?PHP

/**
 * Autoprov Manager Installer
 *
 * @author JCattan
 * @license MPL / GPLv3/ LGPL
 * @package Autoprov Manager
 */
 
/**	pour signer le module si modif

mv /var/www/html/admin/modules/autoprov/_ap_phone_modules /tmp/
mv /var/www/html/admin/modules/autoprov/provisioning /tmp/
rm -f /var/www/html/admin/modules/autoprov/templates/freepbx/compiled/*
/usr/local/src/devtools/sign.php /var/www/html/admin/modules/autoprov 4FB0BF70
mv /tmp/_ap_phone_modules /var/www/html/admin/modules/autoprov/
mv /tmp/provisioning /var/www/html/admin/modules/autoprov/

*/

function epm_rmrf($dir) {
    if (file_exists($dir)) {
        $iterator = new RecursiveDirectoryIterator($dir);
        foreach (new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST) as $file) {
            if ($file->isDir()) {
                @rmdir($file->getPathname());
            } else {
                @unlink($file->getPathname());
            }
        }
        //Remove parent path as the last step
        @rmdir($dir);
    }
}

function find_exec($exec) {
    $usr_bin = glob("/usr/bin/" . $exec);
    $usr_sbin = glob("/usr/sbin/" . $exec);
    $sbin = glob("/sbin/" . $exec);
    $bin = glob("/bin/" . $exec);
    $etc = glob("/etc/" . $exec);
    if (isset($usr_bin[0])) {
        return("/usr/bin/" . $exec);
    } elseif (isset($usr_sbin[0])) {
        return("/usr/sbin/" . $exec);
    } elseif (isset($sbin[0])) {
        return("/sbin/" . $exec);
    } elseif (isset($bin[0])) {
        return("/bin/" . $exec);
    } elseif (isset($etc[0])) {
        return("/etc/" . $exec);
    } else {
        return($exec);
    }
}

global $db;

out("Endpoint Manager Installer");

define("PHONE_MODULES_PATH", $amp_conf['AMPWEBROOT'] . '/admin/modules/_ap_phone_modules/');
define("LOCAL_PATH", $amp_conf['AMPWEBROOT'] . '/admin/modules/autoprov/');


if (!file_exists(PHONE_MODULES_PATH)) {
    mkdir(PHONE_MODULES_PATH, 0764);
    out("Creating Phone Modules Directory");
}

if (!file_exists(PHONE_MODULES_PATH . "setup.php")) {
    copy(LOCAL_PATH . "install/setup.php", PHONE_MODULES_PATH . "setup.php");
    out("Moving Auto Provisioner Class");
}

if (!file_exists(PHONE_MODULES_PATH . "autoload.php")) {
    copy(LOCAL_PATH . "_ap_phone_modules/autoload.php", PHONE_MODULES_PATH . "autoload.php");
    out("Moving AutoLoad File");
}

if (!file_exists(PHONE_MODULES_PATH . "temp/")) {
    mkdir(PHONE_MODULES_PATH . "temp/", 0764);
    out("Creating temp folder");
}

$modinfo = module_getinfo('autoprov');
$epmxmlversion = $modinfo['autoprov']['version'];
$epmdbversion = !empty($modinfo['autoprov']['dbversion']) ? $modinfo['autoprov']['dbversion'] : null;

// si deja install
if (!empty($epmdbversion)) {

// version 15.0.0.1 installee
	if (version_compare_freepbx($epmdbversion,'15.0.0.1','<=')) {
out("MAJ 15.0.0.1 to 15.0.0.2");
    out("Copie des fichiers de provisioning");
system("/bin/cp -R /var/www/html/admin/modules/autoprov/_ap_phone_modules/endpoint/yealinkv80/t5x /var/www/html/admin/modules/_ap_phone_modules/endpoint/yealinkv80/");
system("/bin/cp -Rf /var/www/html/admin/modules/autoprov/_ap_phone_modules/endpoint/yealinkv80/brand_data.json /var/www/html/admin/modules/_ap_phone_modules/endpoint/yealinkv80/");    
	out("MAJ base de donnees");		
$sql = "INSERT INTO `autoprov_product_list` (`id`, `brand`, `long_name`, `short_name`, `cfg_dir`, `cfg_ver`, `hidden`, `firmware_vers`, `firmware_files`, `config_files`, `special_cfgs`) VALUES
('215', 21, 'Yealink V80 T5X Models: [T53W, T54W, T57W]', 'Yealink V80 T5X Models: ', 't5x', '', 0, '', '', 'y0000000000\$suffix.cfg,\$mac.cfg', '')";
$db->query($sql);
		
$sql = "INSERT INTO `autoprov_model_list` (`id`, `brand`, `model`, `max_lines`, `template_list`, `template_data`, `product_id`, `enabled`, `hidden`) VALUES
('2153', 21, 'T53W', 2, 'template_data.json,line_keys_5x.json,soft_keys.json,hard_keys.json', '', '215', 1, 0),
('2154', 21, 'T54W', 2, 'template_data.json,line_keys_5x.json,soft_keys.json,hard_keys.json', '', '215', 1, 0),
('2157', 21, 'T57W', 2, 'template_data.json,line_keys_5x.json,soft_keys.json,hard_keys.json', '', '215', 1, 0)";
$db->query($sql);

    }

// version 15.0.0.2 installee
	if (version_compare_freepbx($epmdbversion,'15.0.0.2','<=')) {
out("MAJ 15.0.0.2 to 15.0.0.3");
    out("Copie des fichiers de provisioning");
system("/bin/cp -Rf /var/www/html/admin/modules/autoprov/_ap_phone_modules/endpoint/yealinkv80/base.php /var/www/html/admin/modules/_ap_phone_modules/endpoint/yealinkv80/");
system("/bin/cp -Rf /var/www/html/admin/modules/autoprov/_ap_phone_modules/endpoint/yealinkv80/t4x/template_data.json /var/www/html/admin/modules/_ap_phone_modules/endpoint/yealinkv80/t4x/");
system("/bin/cp -Rf /var/www/html/admin/modules/autoprov/_ap_phone_modules/endpoint/yealinkv80/t5x/template_data.json /var/www/html/admin/modules/_ap_phone_modules/endpoint/yealinkv80/t5x/");
system("/bin/cp -Rf /var/www/html/admin/modules/autoprov/_ap_phone_modules/endpoint/yealinkv80/w52p/phone.php /var/www/html/admin/modules/_ap_phone_modules/endpoint/yealinkv80/w52p/");
system("/bin/cp -Rf /var/www/html/admin/modules/autoprov/_ap_phone_modules/endpoint/yealinkv80/w52p/family_data.json /var/www/html/admin/modules/_ap_phone_modules/endpoint/yealinkv80/w52p/");
system("/bin/cp -Rf /var/www/html/admin/modules/autoprov/_ap_phone_modules/endpoint/yealinkv80/brand_data.json /var/www/html/admin/modules/_ap_phone_modules/endpoint/yealinkv80/");
	out("MAJ base de donnees");
$sql = "UPDATE `asterisk`.`autoprov_product_list` SET `long_name` = 'Yealink V80 DECT Models: [W52P, W60P]' WHERE `autoprov_product_list`.`id` = '216'";
$db->query($sql);
$sql = "INSERT INTO `autoprov_model_list` (`id`, `brand`, `model`, `max_lines`, `template_list`, `template_data`, `product_id`, `enabled`, `hidden`) VALUES ('2162', '21', 'W60P', '8', 'template_data.json', '', '216', '1', '0')";
$db->query($sql);

 }	
 
// version 15.0.0.3 installee
	if (version_compare_freepbx($epmdbversion,'15.0.0.3','<=')) {
out("MAJ 15.0.0.3 to 15.0.0.4");
	}
	
// version 15.0.0.4 installee
	if (version_compare_freepbx($epmdbversion,'15.0.0.4','<=')) {
out("MAJ 15.0.0.4 to 15.0.0.5");
    out("Copie des fichiers de provisioning");
system("/bin/cp -Rf /var/www/html/admin/modules/autoprov/_ap_phone_modules/endpoint/yealinkv80/base.php /var/www/html/admin/modules/_ap_phone_modules/endpoint/yealinkv80/");
system("/bin/cp -Rf /var/www/html/admin/modules/autoprov/_ap_phone_modules/endpoint/yealinkv80/t3x /var/www/html/admin/modules/_ap_phone_modules/endpoint/yealinkv80/");
system("/bin/cp -Rf /var/www/html/admin/modules/autoprov/_ap_phone_modules/endpoint/yealinkv80/brand_data.json /var/www/html/admin/modules/_ap_phone_modules/endpoint/yealinkv80/");
	out("MAJ base de donnees");
$sql = "INSERT INTO `autoprov_product_list` (`id`, `brand`, `long_name`, `short_name`, `cfg_dir`, `cfg_ver`, `hidden`, `firmware_vers`, `firmware_files`, `config_files`, `special_cfgs`) VALUES ('212', 21, 'Yealink V80 T3X Models: [T30, T33, T38]', 'Yealink V80 T3X Models: ', 't3x', '', 0, '', '', 'y000000000$suffix.cfg,$mac.cfg,$mac.xml', '')";
$db->query($sql);
$sql = "INSERT INTO `autoprov_model_list` (`id`, `brand`, `model`, `max_lines`, `template_list`, `template_data`, `product_id`, `enabled`, `hidden`) VALUES
('2122', 21, 'T32', 3, 'template_data.json,line_keys_32.json,soft_keys.json,hard_keys.json,remote_phonebook.json,dialnow.json,ext38.json', '', '212', 1, 0),
('2123', 21, 'T33', 4, 'template_data.json,line_keys_33.json,soft_keys.json,hard_keys.json,remote_phonebook.json,dialnow.json', '', '212', 1, 0),
('2128', 21, 'T38', 6, 'template_data.json,line_keys_38.json,remote_phonebook.json,soft_keys.json,hard_keys.json,memory_keys.json,dialnow.json,ext38.json', '', '212', 1, 0)";
$db->query($sql);
	}


// a suivre 
// version 15.0.0.X installe
//	if (version_compare_freepbx($epmdbversion,'15.0.0.X','<=')) {
//out("MAJ 15.0.0.X to 15.0.0.Z");
// }
	
}


// si nouvelle install
if (empty($epmdbversion)) {

    out("Creating Brand List Table");
    $sql = "CREATE TABLE IF NOT EXISTS `autoprov_brand_list` (
                  `id` varchar(11) NOT NULL,
                  `name` varchar(255) NOT NULL,
                  `directory` varchar(255) NOT NULL,
                  `cfg_ver` varchar(255) NOT NULL,
                  `installed` int(1) NOT NULL DEFAULT '0',
                    `local` int(1) NOT NULL DEFAULT '0',
                  `hidden` int(1) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`id`)
                ) ENGINE=MyISAM";
    $db->query($sql);

    out("Creating Line List Table");

    $sql = "CREATE TABLE IF NOT EXISTS `autoprov_line_list` (
  `luid` int(11) NOT NULL AUTO_INCREMENT,
  `mac_id` int(11) NOT NULL,
  `line` smallint(2) NOT NULL,
  `ext` varchar(15) NOT NULL,
  `description` varchar(20) NOT NULL,
  `custom_cfg_data` longblob NOT NULL,
  `user_cfg_data` longblob NOT NULL,
  PRIMARY KEY (`luid`)
) ENGINE=MyISAM";
    $db->query($sql);

    out("Creating Global Variables Table");
    $sql = "CREATE TABLE IF NOT EXISTS `autoprov_global_vars` (
                  `idnum` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Index',
                  `var_name` varchar(25) NOT NULL COMMENT 'Variable Name',
                  `value` text NOT NULL COMMENT 'Data',
                  PRIMARY KEY (`idnum`),
                  UNIQUE KEY `var_name` (`var_name`)
                ) ENGINE=MyISAM";
    $db->query($sql);

    out("Locating NMAP + ARP + ASTERISK Executables");
    $nmap = find_exec("nmap");
    $arp = find_exec("arp");
    $asterisk = find_exec("asterisk");

    out("Inserting data into the global vars Table");
    $sql = "INSERT INTO `autoprov_global_vars` (`idnum`, `var_name`, `value`) VALUES
            (1, 'srvip', ''),
            (2, 'tz', 'europe/paris'),
            (3, 'gmtoff', ''),
            (4, 'gmthr', ''),
            (5, 'config_location', '/tftpboot/'),
            (6, 'update_server', 'http://127.0.0.1'),
            (7, 'version', '" . $epmxmlversion  . "'),
            (8, 'enable_ari', '0'),
            (9, 'debug', '0'),
            (10, 'arp_location', '" . $arp . "'),
            (11, 'nmap_location', '" . $nmap . "'),
            (12, 'asterisk_location', '" . $asterisk . "'),
            (13, 'language', ''),
            (14, 'check_updates', '0'),
            (15, 'disable_htaccess', ''),
            (16, 'endpoint_vers', '0'),
            (17, 'disable_help', '0'),
            (18, 'show_all_registrations', '0'),
            (19, 'ntp', ''),
            (20, 'server_type', 'http'),
            (21, 'allow_hdfiles', '0'),
            (22, 'tftp_check', '0'),
            (23, 'nmap_search', ''),
            (24, 'backup_check', '0'),
            (25, 'use_repo', '0')";
    $db->query($sql);

    out("Creating mac list Table");
    $sql = "CREATE TABLE IF NOT EXISTS `autoprov_mac_list` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mac` varchar(12) DEFAULT NULL,
  `model` varchar(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `global_custom_cfg_data` longblob NOT NULL,
  `global_user_cfg_data` longblob NOT NULL,
  `config_files_override` text NOT NULL,
  `global_settings_override` longblob,
    `specific_settings` longblob,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mac` (`mac`)
) ENGINE=MyISAM";
    $db->query($sql);

    out("Creating model List Table");
    $sql = "CREATE TABLE IF NOT EXISTS `autoprov_model_list` (
  `id` varchar(11) NOT NULL COMMENT 'Key ',
  `brand` int(11) NOT NULL COMMENT 'Brand',
  `model` varchar(25) NOT NULL COMMENT 'Model',
  `max_lines` smallint(2) NOT NULL,
  `template_list` text NOT NULL,
  `template_data` longblob NOT NULL,
  `product_id` varchar(11) NOT NULL,
  `enabled` int(1) NOT NULL DEFAULT '0',
  `hidden` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";
    $db->query($sql);

    out("Creating oui List Table");
    $sql = "CREATE TABLE IF NOT EXISTS `autoprov_oui_list` (
          `id` int(30) NOT NULL AUTO_INCREMENT,
          `oui` varchar(30) DEFAULT NULL,
          `brand` int(11) NOT NULL,
          `custom` int(1) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`),
          UNIQUE KEY `oui` (`oui`)
        ) ENGINE=MyISAM";
    $db->query($sql);

    out("Creating product List Table");
    $sql = "CREATE TABLE IF NOT EXISTS `autoprov_product_list` (
  `id` varchar(11) NOT NULL,
  `brand` int(11) NOT NULL,
  `long_name` varchar(255) NOT NULL,
  `short_name` varchar(255) NOT NULL,
  `cfg_dir` varchar(255) NOT NULL,
  `cfg_ver` varchar(255) NOT NULL,
  `hidden` int(1) NOT NULL DEFAULT '0',
  `firmware_vers` varchar(255) NOT NULL,
  `firmware_files` text NOT NULL,
  `config_files` text,
  `special_cfgs` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";
    $db->query($sql);

    out("Creating Template List Table");
    $sql = "CREATE TABLE IF NOT EXISTS `autoprov_template_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(11) NOT NULL,
  `model_id` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `global_custom_cfg_data` longblob,
  `config_files_override` text,
  `global_settings_override` longblob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM";
    $db->query($sql);

    out("Create Custom Configs Table");
    $sql = "CREATE TABLE IF NOT EXISTS `autoprov_custom_configs` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `original_name` varchar(255) NOT NULL,
          `product_id` varchar(11) NOT NULL,
          `data` longblob NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM";
    $db->query($sql);

    out('Creating symlink to web provisioner');
	if(!file_exists($amp_conf['AMPWEBROOT'] . "/provisioning")) {
		if (!symlink(LOCAL_PATH . "provisioning", $amp_conf['AMPWEBROOT'] . "/provisioning")) {
			//out("<strong>Your permissions are wrong on ".$amp_conf['AMPWEBROOT'].", web provisioning link not created!</strong>");
		}
	}
	
	
out("Mise ajour des tables de provisionning");
out("autoprov_brand_list");
$sql = "TRUNCATE TABLE `autoprov_brand_list`";
$db->query($sql);

$sql = "INSERT INTO `autoprov_brand_list` (`id`, `name`, `directory`, `cfg_ver`, `installed`, `local`, `hidden`) VALUES
('6', 'Snom', 'snom', '1456262516', 1, 1, 0),
('2', 'Grandstream', 'grandstream', '1511449597', 1, 1, 0),
('21', 'Yealink V80', 'yealinkv80', '1511444121', 1, 1, 0),
('20', 'Patton', 'patton', '1511441849', 1, 1, 0),
('1', 'Aastra', 'aastra', '1511425854', 1, 1, 0),
('5', 'RTX', 'RTX', '1511425854', 1, 1, 0),
('22', 'HTEK', 'htek', '1511425854', 1, 1, 0)";
$db->query($sql);

out("autoprov_product_list");
$sql = "TRUNCATE TABLE `autoprov_product_list`";
$db->query($sql);

$sql = "INSERT INTO `autoprov_product_list` (`id`, `brand`, `long_name`, `short_name`, `cfg_dir`, `cfg_ver`, `hidden`, `firmware_vers`, `firmware_files`, `config_files`, `special_cfgs`) VALUES
('63', 6, 'Snom 7xx Models [710, 715, 720, 725, 760]', 'Snom 7xx Models ', '7xx', '', 0, '', '', 'general_custom.xml,general.xml,snom\$model-\$mac.htm,snom\$model.htm,\$mac_firmware.xml', ''),
('12', 1, '[Aastra ]67xx', '67xx', 'aap9xxx6xxx', '', 0, '', '', '\$mac.cfg,aastra.cfg', ''),
('22', 2, 'GXP16XX[ / Grandstream]', 'GXP16XX', 'GXP16XX', '', 0, '', '', 'cfg\$mac', ''),
('23', 2, 'GXP21XX[ / Grandstream]', 'GXP21XX', 'GXP21XX', '', 0, '', '', 'cfg\$mac', ''),
('24', 2, 'GXW4XXX[ / Grandstream]', 'GXW4XXX', 'GXW4XXX', '1.0', 0, '', '', 'cfg\$mac', ''),
('25', 2, 'GXV3240[ / Grandstream]', 'GXV3240', 'GXV3240', '1.0', 0, '', '', 'cfg\$mac', ''),
('26', 2, 'GXV3275[ / Grandstream]', 'GXV3275', 'GXV3275', '1.0', 0, '', '', 'cfg\$mac', ''),
('51', 5, '[DECT ]RTX 8660', 'RTX 8660', '8660', '', 0, '', '', '\$mac.cfg', ''),
('201', 20, '[Patton FXS ]SN411X', 'SN411X', 'SN411X', '', 0, '', '', '\$mac.cfg', ''),
('202', 20, '[Patton FXS ]SN43XX', 'SN43XX', 'SN43XX', '', 0, '', '', '\$mac.cfg', ''),
('211', 21, 'Yealink V80 T2X Models: [T19, T20, T21, T22, T26, T28]', 'Yealink V80 T2X Models: ', 't2x', '', 0, '', '', 'y0000000000\$suffix.cfg,\$mac.cfg', ''),
('212', 21, 'Yealink V80 T3X Models: [T30, T33, T38]', 'Yealink V80 T3X Models: ', 't3x', '', 0, '', '', 'y000000000$suffix.cfg,$mac.cfg,$mac.xml', ''),
('214', 21, 'Yealink V80 T4X Models: [T41, T42, T46]', 'Yealink V80 T4X Models: ', 't4x', '', 0, '', '', 'y0000000000\$suffix.cfg,\$mac.cfg', ''),
('215', 21, 'Yealink V80 T5X Models: [T53W, T54W, T57W]', 'Yealink V80 T5X Models: ', 't5x', '', 0, '', '', 'y0000000000\$suffix.cfg,\$mac.cfg', ''),
('216', 21, 'Yealink V80 DECT Models: [W52P, W60P]', 'Yealink V80 DECT Models: ', 'w52p', '', 0, '', '', 'y0000000000\$suffix.cfg,\$mac.cfg', ''),
('222', 22, 'UC9XX[ / HTEK]', 'UC9XX', 'UC9XX', '', 1, '', '', 'cfg\$mac', ''),
('221', 22, 'Htek Enterprise HD series [926,924,862,842,860,840,806,804,803,802]', 'Htek Enterprise HD series ', 'uc8xx', '', 0, '', '', 'cfg\$mac', '')";
$db->query($sql);

out("autoprov_model_list");
$sql = "TRUNCATE TABLE `autoprov_model_list`";
$db->query($sql);

$sql = "INSERT INTO `autoprov_model_list` (`id`, `brand`, `model`, `max_lines`, `template_list`, `template_data`, `product_id`, `enabled`, `hidden`) VALUES
('631', 6, '710', 4, 'line_options.json,template_data.json,action_urls.json,function_keys.json,speed_dials.json,keys.json,idle_touch_keys.json', '', '63', 1, 0),
('632', 6, '715', 4, 'line_options.json,template_data.json,action_urls.json,function_keys.json,speed_dials.json,keys.json,idle_touch_keys.json', '', '63', 1, 0),
('633', 6, '720', 12, 'line_options.json,template_data.json,action_urls.json,function_keys.json,speed_dials.json,keys.json,idle_touch_keys.json', '', '63', 1, 0),
('634', 6, '725', 12, 'line_options.json,template_data.json,action_urls.json,function_keys.json,speed_dials.json,keys.json,idle_touch_keys.json', '', '63', 1, 0),
('635', 6, '760', 12, 'line_options.json,template_data.json,action_urls.json,function_keys.json,speed_dials.json,keys.json,idle_touch_keys.json', '', '63', 1, 0),
('636', 6, 'D785', 12, 'line_options.json,template_data.json,action_urls.json,function_keys.json,speed_dials.json,keys.json,idle_touch_keys.json', '', '63', 1, 0),
('637', 6, 'D745', 12, 'line_options.json,template_data.json,action_urls.json,function_keys.json,speed_dials.json,keys.json,idle_touch_keys.json', '', '63', 1, 0),
('2011', 20, 'SN4112', 2, 'template_data.json', '', '201', 1, 0),
('2012', 20, 'SN4114', 4, 'template_data.json', '', '201', 1, 0),
('2013', 20, 'SN4118', 8, 'template_data.json', '', '201', 1, 0),
('2021', 20, 'SN4312', 12, 'template_data.json', '', '202', 1, 0),
('2022', 20, 'SN4316', 16, 'template_data.json', '', '202', 1, 0),
('2023', 20, 'SN4324', 24, 'template_data.json', '', '202', 1, 0),
('2024', 20, 'SN4332', 32, 'template_data.json', '', '202', 1, 0),
('124', 1, '6730i', 3, 'template_data.json,prgkeys.json', '', '12', 1, 0),
('125', 1, '6731i', 6, 'template_data.json,prgkeys.json', '', '12', 1, 0),
('126', 1, '6739i', 9, 'template_data.json,softkeys.json,expmod1.json,expmod2.json,expmod3.json', '', '12', 1, 0),
('127', 1, '6751i', 1, 'template_data.json,prgkeys.json,softkeys.json,expmod1.json,expmod2.json,expmod3.json', '', '12', 1, 0),
('128', 1, '6753i', 3, 'template_data.json,prgkeys.json,softkeys.json,expmod1.json,expmod2.json,expmod3.json', '', '12', 1, 0),
('129', 1, '6755i', 4, 'template_data.json,prgkeys.json,softkeys.json,expmod1.json,expmod2.json,expmod3.json', '', '12', 1, 0),
('1210', 1, '6757i', 9, 'template_data.json,topkeys.json,softkeys.json,expmod1.json,expmod2.json,expmod3.json', '', '12', 1, 0),
('231', 2, 'GXP2170', 6, 'template_data.json,LAN.json,repertoire.json,VLAN.json', '', '23', 1, 0),
('221', 2, 'GXP1625', 2, 'template_data.json,LAN.json,repertoire.json,VLAN.json', '', '22', 1, 0),
('241', 2, 'GXW4216', 16, 'template_data.json', '', '24', 1, 0),
('242', 2, 'GXW4224', 24, 'template_data.json', '', '24', 1, 0),
('243', 2, 'GXW4232', 32, 'template_data.json', '', '24', 1, 0),
('244', 2, 'GXW4248', 48, 'template_data.json', '', '24', 1, 0),
('245', 2, 'GXW4004', 4, 'template_data.json', '', '24', 1, 0),
('246', 2, 'GXW4008', 8, 'template_data.json', '', '24', 1, 0),
('251', 2, 'GXV3240', 4, 'template_data.json,key_exten.json,LAN.json,repertoire.json,VLAN.json', '', '25', 1, 0),
('261', 2, 'GXV3275', 4, 'template_data.json,LAN.json,repertoire.json,VLAN.json', '', '26', 1, 0),
('511', 5, '8660', 150, 'template_data.json', '', '51', 1, 0),
('2115', 21, 'T19', 2, 'template_data.json,line_keys_20.json', '', '211', 1, 0),
('2111', 21, 'T20', 2, 'template_data.json,line_keys_20.json', '', '211', 1, 0),
('2116', 21, 'T21', 2, 'template_data.json,line_keys_20.json', '', '211', 1, 0),
('2112', 21, 'T22', 2, 'template_data.json,line_keys_22.json,soft_keys_22.json,hard_keys.json,ext38.json', '', '211', 1, 0),
('2113', 21, 'T26', 3, 'template_data.json,line_keys_22.json,soft_keys_22.json,hard_keys.json,memory_keys_22.json,ext38.json', '', '211', 1, 0),
('2114', 21, 'T28', 3, 'template_data.json,line_keys_28.json,soft_keys_22.json,hard_keys.json,memory_keys_22.json,ext38.json', '', '211', 1, 0),
('2122', 21, 'T32', 3, 'template_data.json,line_keys_32.json,soft_keys.json,hard_keys.json,remote_phonebook.json,dialnow.json,ext38.json', '', '212', 1, 0),
('2123', 21, 'T33', 4, 'template_data.json,line_keys_33.json,soft_keys.json,hard_keys.json,remote_phonebook.json,dialnow.json', '', '212', 1, 0),
('2128', 21, 'T38', 6, 'template_data.json,line_keys_38.json,remote_phonebook.json,soft_keys.json,hard_keys.json,memory_keys.json,dialnow.json,ext38.json', '', '212', 1, 0),
('2141', 21, 'T41P', 2, 'template_data.json,line_keys_15.json,soft_keys.json,hard_keys.json', '', '214', 1, 0),
('2142', 21, 'T42G', 2, 'template_data.json,line_keys_15.json,soft_keys.json,hard_keys.json', '', '214', 1, 0),
('2143', 21, 'T46G', 2, 'template_data.json,line_keys_46.json,soft_keys_46.json,hard_keys_46.json,exp.json', '', '214', 1, 0),
('2144', 21, 'T48G', 2, 'template_data.json,line_keys_46.json,soft_keys.json,hard_keys.json', '', '214', 1, 0),
('2145', 21, 'T41S', 2, 'template_data.json,line_keys_15.json,soft_keys.json,hard_keys.json', '', '214', 1, 0),
('2146', 21, 'T42S', 2, 'template_data.json,line_keys_15.json,soft_keys.json,hard_keys.json', '', '214', 1, 0),
('2147', 21, 'T46S', 2, 'template_data.json,line_keys_46.json,soft_keys_46.json,hard_keys_46.json,exp.json', '', '214', 1, 0),
('2148', 21, 'T48S', 2, 'template_data.json,line_keys_46.json,soft_keys.json,hard_keys.json', '', '214', 1, 0),
('2153', 21, 'T53W', 2, 'template_data.json,line_keys_5x.json,soft_keys.json,hard_keys.json', '', '215', 1, 0),
('2154', 21, 'T54W', 2, 'template_data.json,line_keys_5x.json,soft_keys.json,hard_keys.json', '', '215', 1, 0),
('2157', 21, 'T57W', 2, 'template_data.json,line_keys_5x.json,soft_keys.json,hard_keys.json', '', '215', 1, 0),
('2161', 21, 'W52P', 5, 'template_data.json', '', '216', 1, 0),
('2162', 21, 'W60P', 8, 'template_data.json', '', '216', 1, 0),
('2221', 22, 'UC912', 4, 'template_data.json,LAN.json,repertoire.json,VLAN.json', '', '222', 0, 1),
('2211', 22, 'UC862', 4, 'template_data_860.json,line_keys_860.json,hard_keys_860.json,soft_keys_860.json', '', '221', 1, 0),
('2212', 22, 'UC842', 3, 'template_data_840.json,line_keys_840.json,hard_keys_840.json,soft_keys_840.json', '', '221', 1, 0),
('2213', 22, 'UC860', 4, 'template_data_860.json,line_keys_860.json,hard_keys_860.json,soft_keys_860.json', '', '221', 1, 0),
('2214', 22, 'UC840', 3, 'template_data_840.json,line_keys_840.json,hard_keys_840.json,soft_keys_840.json', '', '221', 1, 0),
('2215', 22, 'UC806', 4, 'template_data_860.json,line_keys_860.json,hard_keys_860.json,soft_keys_860.json', '', '221', 1, 0),
('2216', 22, 'UC804', 3, 'template_data_840.json,line_keys_840.json,hard_keys_840.json,soft_keys_840.json', '', '221', 1, 0),
('2217', 22, 'UC803', 2, 'template_data_803.json,line_keys_803.json,hard_keys_803.json,soft_keys_803.json', '', '221', 1, 0),
('2218', 22, 'UC802', 2, 'template_data_803.json,line_keys_803.json,hard_keys_803.json,soft_keys_803.json', '', '221', 1, 0),
('2219', 22, 'UC926', 6, 'template_data_926.json,line_keys_926.json,hard_keys_926.json,soft_keys_926.json', '', '221', 1, 0),
('22110', 22, 'UC924', 4, 'template_data_926.json,line_keys_924.json,hard_keys_926.json,soft_keys_926.json', '', '221', 1, 0)";
$db->query($sql);

}

// a executer a chaque install

if (!file_exists(PHONE_MODULES_PATH . "endpoint")) {
    // copy(LOCAL_PATH . "_ap_phone_modules/endpoint", PHONE_MODULES_PATH . "endpoint");
	system("/bin/cp -R /var/www/html/admin/modules/autoprov/_ap_phone_modules/endpoint /var/www/html/admin/modules/_ap_phone_modules/");
	// system("/bin/cp -R " . LOCAL_PATH . "_ap_phone_modules/endpoint " . PHONE_MODULES_PATH .);
    out("Copie des fichiers de provisioning");
}


out("Mise a jour num version a " . $epmxmlversion);
$sql = "UPDATE autoprov_global_vars SET value = '" . $epmxmlversion . "' WHERE var_name = 'version'";
$db->query($sql);

$sql = "UPDATE autoprov_global_vars SET value = 'http://127.0.0.1/provisioner/' WHERE var_name = 'update_server'";
$db->query($sql);
