<?php
namespace FreePBX\modules\autoprov;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use FreePBX\modules\Backup as Base;

#[\AllowDynamicProperties]
class Backup Extends Base\BackupBase{
  public function runBackup($id,$transaction){
                $tables = $this->dumpTables();
                $configs = [
                        'tables' => $tables
                ];
        $dirs = [];
                $varwww = $this->FreePBX->Config->get('AMPWEBROOT');
                $iterator = new RecursiveDirectoryIterator($varwww.'/admin/modules/_ap_phone_modules',RecursiveDirectoryIterator::SKIP_DOTS);
                foreach (new RecursiveIteratorIterator($iterator) as $file) {
                                $dirs[] = $file->getPath();
                                $this->addFile($file->getBasename(),$file->getPath(),'',"_ap_phone_modules");
                }
                $this->addDirectories(array_unique($dirs));
				$this->addConfigs($configs);
        }
}
