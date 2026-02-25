<?php
namespace FreePBX\modules\autoprov;
use FreePBX\modules\Backup as Base;

#[\AllowDynamicProperties]
class Restore Extends Base\RestoreBase{
    public function runRestore()
    {
        $configs = $this->getConfigs();
        if( ! empty($configs['tables']) && is_array($configs['tables']) )
        {
            $this->importTables($configs['tables']);
        }
		 $files = $this->getFiles();
		 foreach ($files as $file) {
                        $filename = $file->getPathTo().'/'.$file->getFilename();
                        $source = $this->tmpdir.'/files'.$file->getPathTo().'/'.$file->getFilename();
                        $dest = $filename;
                        if(file_exists($source)){
                                @mkdir($file->getPathTo(),0755,true);
                                copy($source, $dest);
                        }
		 }

    }

}
