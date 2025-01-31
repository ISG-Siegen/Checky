<?php

namespace App\Dto;

// Data Transfer Object (DTO) for handling checklist save/update requests.

use App\Entity\SavedQuestion; 
use Symfony\Component\Serializer\Attribute\Groups; 
use Symfony\Component\Uid\Uuid; 

class SaveChecklistRequest {

    #[Groups('save:updateRequest')]
    public ?string $uuid; // Optional UUID of the checklist. Used to identify an existing checklist for updates.

    #[Groups('save:updateRequest')]
    public string $name; // Name of the checklist to be saved or updated.

    #[Groups('save:updateRequest')]
    /**
     * @var SaveQuestionRequest[] // Array of question requests to be saved in the checklist.
     */
    public array $questionRequests;
}

class SaveQuestionRequest {

    #[Groups('save:updateRequest')]
    public SavedQuestion $question; // The question entity to be saved or updated.

    #[Groups('save:updateRequest')]
    public ?Uuid $originalQuestion; // Optional UUID of the original question this question is based on.
}
