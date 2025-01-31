<?php

namespace App\Dto;

use App\Entity\SavedQuestion;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

class SaveChecklistRequest {

    #[Groups('save:updateRequest')]
    public ?string $uuid;

    #[Groups('save:updateRequest')]
    public string $name;
    
    #[Groups('save:updateRequest')]
    /**
     * @var SaveQuestionRequest[]
     */
    public array $questionRequests;
}

class SaveQuestionRequest {

    #[Groups('save:updateRequest')]
    public SavedQuestion $question;
    
    #[Groups('save:updateRequest')]
    public ?Uuid $originalQuestion;
}