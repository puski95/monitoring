<?php

declare(strict_types=1);

namespace App\UI\Media;

use Nette;
use Nette\Utils\FileSystem;
use Nette\IOException;



final class MediaPresenter extends Nette\Application\UI\Presenter
{

    protected function startup()
    {
        parent::startup();
        
        parent::protected();
    }

    public function renderDefault() {
        $this->error("403", 403);
    }

    public function renderAvatar($id)
    {
        try {
            $filePath = __DIR__ . "/../../Media/Avatar/" . $id;
            $fileType = mime_content_type($filePath);

            if ($fileType === 'image/png') {
                $img = imagecreatefrompng($filePath);
                imagealphablending($img, false);
                imagesavealpha($img, true);
                header('Content-Type: image/png');
                imagepng($img);
                imagedestroy($img);
            } else {
                $img = \Nette\Utils\Image::fromFile($filePath);
                $img->send();
                $this->terminate();
            }
            
        } catch (\Exception $e) {
            $this->error('Chyba při zpracování obrázku');
        }
        
        $this->terminate();
    }

    public function renderImage($id)
    {
        try {
            $filePath = __DIR__ . "/../../Media/" . $id;
            $fileType = mime_content_type($filePath);

            if ($fileType === 'image/png') {
                $img = imagecreatefrompng($filePath);
                imagealphablending($img, false);
                imagesavealpha($img, true);
                header('Content-Type: image/png');
                imagepng($img);
                imagedestroy($img);
            } else {
                $img = \Nette\Utils\Image::fromFile($filePath);
                $img->send();
                $this->terminate();
            }
            
        } catch (\Exception $e) {
            $this->error('Chyba při zpracování obrázku');
        }
        
        $this->terminate();
    }

}
