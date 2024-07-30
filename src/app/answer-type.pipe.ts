import { Pipe, PipeTransform } from '@angular/core';
import { AnswerType } from './api';

@Pipe({
  name: 'answerType'
})
export class AnswerTypePipe implements PipeTransform {

  transform(value: AnswerType): string {

    switch (value) {
      case AnswerType.FreeText:
        return 'Free text'
      case AnswerType.FreeTextAndJustification:
        return 'Free text & justification'
      case AnswerType.None:
        return 'None'
    }

    return 'Unknown AnswerType';
  }
}
