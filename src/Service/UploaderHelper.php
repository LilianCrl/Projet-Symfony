<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class UploaderHelper
{

    private string $uploadsPath;

    public function __construct(string $uploadsPath){

        $this->uploadsPath = $uploadsPath;
    }
    public  function  uploadParticipantImage(UploadedFile $uploadedFile): string{
        $destination = $this->uploadsPath.'/images_participants';
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
        $uploadedFile->move($destination, $newFilename);
        return $newFilename;
    }
}