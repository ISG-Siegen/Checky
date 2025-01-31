<?php


namespace App\Enum;

// Enum representing the types of answers for questions.
enum AnswerType: string {
    case NONE = 'None';
    case FREE_TEXT_AND_JUSTIFICATION = 'FreeTextAndJustification';
    case FREE_TEXT = 'FreeText';
}