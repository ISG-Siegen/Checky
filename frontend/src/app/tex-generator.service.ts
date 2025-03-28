// Service for generating TeX documents based on a checklist and user answers.

import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { AnswerType } from './api';
import { LocalQuestion } from './generator/generator.component';

@Injectable({
  providedIn: 'root', // Makes the service available throughout the app.
})
export class TexGeneratorService {
  checklist_tex: string | null = null; // Holds the main TeX template for the checklist.
  answers_tex: Map<AnswerType, string> = new Map(); // Maps answer types to their corresponding TeX templates.

  constructor(http: HttpClient) {
    // Loads the main checklist TeX template from the assets folder.
    http.get('/assets/checklist.tex', { responseType: 'text' }).subscribe((res) => {
      this.checklist_tex = res;
    });

    // Loads answer-specific TeX templates for each answer type.
    Object.values(AnswerType).forEach((answerType) => {
      http.get(`/assets/answer_${answerType}.tex`, { responseType: 'text' }).subscribe((res) => {
        this.answers_tex.set(answerType, res);
      });
    });
  }
  // TODO: Maybe print some error to user when one of these cases are met (should never actually happen)
  // Generates a complete TeX document by inserting questions into the main checklist template.
  buildTex(checklistName: string, questions: LocalQuestion[]) {
    let questionsTex = ''; // Holds the TeX content for all questions.

    for (const question of questions) {
      let tmp = this.answers_tex.get(question.answerType);

      if (!tmp) {
        // Logs and returns an error if the template for an answer type is missing.
        let err = `Could not find tex file for given answer type: ${question.answerType}`;
        console.error(err);
        return err;
      }

      if (!tmp.includes('%%%question%%%')) {
        // Logs and returns an error if the template is malformed.
        let err = `Malformed tex file for answer type: ${question.answerType}`;
        console.error(err);
        return err;
      }

      // Replaces placeholder in the template with the formatted question text.
      const questionWithNewLine = question.question.replaceAll('<br>', '\n\t\\item[]');
      tmp = tmp.replace('%%%question%%%', questionWithNewLine);
      questionsTex += tmp;
    }

    if (!this.checklist_tex?.includes('%%%items%%%')) {
      // Logs and returns an error if the main TeX template is malformed.
      let err = 'Malformed main tex file';
      console.error(err);
      return err;
    }

    let tex_out = this.checklist_tex.replace('%%%checklistName%%%', checklistName)

    // Inserts the generated questions into the main checklist template.
    return tex_out.replace('%%%items%%%', questionsTex);
  }
}
