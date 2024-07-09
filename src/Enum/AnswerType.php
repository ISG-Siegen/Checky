<?php


namespace App\Enum;

enum AnswerType: string {
    case NONE = 'None';
    case FREE_TEXT_AND_JUSTIFICATION = 'FreeTextAndJustification';
    case FREE_TEXT = 'FreeText';
}