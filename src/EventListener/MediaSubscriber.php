<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 21/12/17
 * Time: 06:10
 */

namespace App\EventListener;


use App\Entity\Media;
use App\AppEvent;
use App\Event\MediaEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class MediaSubscriber implements EventSubscriberInterface
{
    private $em;

    /**
     * MediaSubscriber constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public static function getSubscribedEvents()
    {
        return [
            AppEvent::MEDIA_ADD =>array('add',0),
            AppEvent::MEDIA_EDIT =>array('persist',0),
            AppEvent::MEDIA_DELETE => array('remove',0),
        ];
    }

    public function add(MediaEvent $mediaEvent){
        /**
         * @var $media Media
         */
        $media = $mediaEvent->getMedia();
        /**
         * @var $file UploadedFile
         */
        $file = $mediaEvent->getFile();
        /**
         * @var $title string
         */
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $media->setPath('media' . "/" . $fileName);
        $media->setType($file->getClientMimeType());
        $file->move(
            'media/',
            $fileName
        );
        $media->setTitle($file->getClientOriginalName());
        $this->em->persist($media);
        $this->em->flush();
    }

    public function persist(){

    }

    public function delete(){

    }
}