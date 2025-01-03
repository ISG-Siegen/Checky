/**
 * Angular pipe to transform `AnswerType` enums into user-friendly string representations.
 */
import { Pipe, PipeTransform } from '@angular/core';
import { AnswerType } from './api';

@Pipe({
  name: 'answerType'
})
export class AnswerTypePipe implements PipeTransform {

/**
   * Transforms an `AnswerType` value into a descriptive string.
   * @param value - The `AnswerType` value to transform.
   * @returns A human-readable string representation of the `AnswerType`.
   */

  transform(value: AnswerType): string {

    switch (value) {
      case AnswerType.FreeText:
        return 'Free text'
      case AnswerType.FreeTextAndJustification:
        return 'Free text & justification'
      case AnswerType.None:
        return 'None'
    }

    return 'Unknown AnswerType'; // Fallback for unknown enum values
  }
}
