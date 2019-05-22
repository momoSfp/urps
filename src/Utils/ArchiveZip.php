<?php

namespace App\Utils;

class ArchiveZip
{
    private $targetDir;

    public function __construct(string $targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function unzipFile($originallName)
    {
        $path        = $this->targetDir . '/public/uploads/games/';
        $pathExtract = $this->targetDir . '/public/uploads/games/extract/';

        $zip = new \ZipArchive;
        
        sleep(1);
        
        $res = $zip->open($path . $originallName);
        
        if($res === true)
        {
            $zip->extractTo($pathExtract);
            $zip->close();

            return true;
        }
        else
        {
            return false;
        }

    }
}