<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 21/12/17
 * Time: 06:09
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;

class MediaEvent extends Event
{
    private $media;
    private $file;

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param mixed $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }


}