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
        $path        = $this->targetDir . '/public/games/';
        $pathExtract = $this->targetDir . '/public/games/extract/';

        $zip = new \ZipArchive;
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