<?php

namespace App\EventListener;

use App\Entity\Content;
use App\Utils\ArchiveZip;
use Vich\UploaderBundle\Event\Event;

class UploadListener
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function onVichUploaderPostUpload(Event $event)
    {
        $object = $event->getObject();

        if ( $object instanceof Content  )
        {
            if ( $object->getGameFile() !== null  )
            {

                $mimeType = $object->getGameFile()->getMimeType();

                $archive = new ArchiveZip;
        
        
                if ($mimeType == "application/zip")
                {
                    $filename       = $object->getGameFile()->getBasename('.zip');
                    $path           = $object->getGameFile()->getPathname();
                    $pathToExtract  = $object->getGameFile()->getpath() . '/extract';
        
                    $zip = new \ZipArchive;
        
                    $res = $zip->open($path);
        
                    if($res === true)
                    {
                        $zip->extractTo($pathToExtract);
                        $zip->close();
                        $object->setLink($pathToExtract . "/" . $filename . "/index.html");
            
                    }
        
                } 
            }
        }

    }

}