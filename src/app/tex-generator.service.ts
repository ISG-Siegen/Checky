import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { AnswerType } from './api';
import { LocalQuestion } from './generator/generator.component';

@Injectable({
  providedIn: 'root'
})
export class TexGeneratorService {

  checklist_tex: string | null = null
  answers_tex: Map<AnswerType, string> = new Map()

  constructor(http: HttpClient) {
    http.get('/assets/checklist.tex', { responseType: 'text' })
      .subscribe(res => {
        this.checklist_tex = res
      })

    Object.values(AnswerType).forEach(answerType => {
      http.get(`/assets/answer_${answerType}.tex`, { responseType: 'text' })
        .subscribe(res => {
          this.answers_tex.set(answerType, res)
        })
    })
  }

  // TODO: Maybe print some error to user when one of these cases are met (should never actually happen)
  buildTex(questions: LocalQuestion[]) {
    let questionsTex = ''

    for (const question of questions) {
      let tmp = this.answers_tex.get(question.answerType)

      if (!tmp) {
        let err = `Could not find tex file for given answer type: ${question.answerType}`
        console.error(err);
        return err
      }

      if (!tmp.includes('%%%question%%%')) {
        let err = `Malformed tex file for answer type: ${question.answerType}`
        console.error(err);
        return err
      }

      const questionWithNewLine = question.question.replaceAll('<br>', '\n\t\\item[]')
      tmp = tmp.replace('%%%question%%%', questionWithNewLine)
      questionsTex += tmp
    }

    if (!this.checklist_tex?.includes('%%%items%%%')) {
      let err = 'Malformed main tex file'
      console.error(err);
      return err
    }

    return this.checklist_tex.replace('%%%items%%%', questionsTex)
  }
}
