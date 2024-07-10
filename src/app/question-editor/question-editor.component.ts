import { Component, EventEmitter, input, Input, Output } from '@angular/core';
import { AnswerType } from '../api';
import { LocalQuestion } from '../generator/generator.component';
import { BehaviorSubject, Subject } from 'rxjs';

@Component({
  selector: 'app-question-editor',
  templateUrl: './question-editor.component.html',
  styleUrl: './question-editor.component.scss'
})
export class QuestionEditorComponent {

  @Input()
  header = ''

  @Input({ required: true })
  visible = false
  @Output()
  visibleChange = new EventEmitter<boolean>()

  questionText = ''
  answerTypes = [AnswerType.FreeText, AnswerType.FreeTextAndJustification, AnswerType.None]
  selectedAnswerType: AnswerType | null = null

  @Input()
  question: LocalQuestion | null = null

  @Output()
  onSave = new EventEmitter<LocalQuestion>()

  currentEditSubject = new Subject<LocalQuestion>()

  editQuestion(question: LocalQuestion) {
    this.question = question
    this.questionText = question.question
    this.selectedAnswerType = question.answerType
    this.visible = true
    this.currentEditSubject = new Subject()
    return this.currentEditSubject.asObservable()
  }

  clearForm() {
    this.questionText = ''
    this.selectedAnswerType = null
  }

  save(event: MouseEvent) {
    //TODO: Add invalid flags when one of these cases is hit
    if (!this.questionText) {
      return
    }

    if (!this.selectedAnswerType) {
      return
    }

    if (!this.question) {
      this.question = new LocalQuestion(this.questionText, this.selectedAnswerType)
    } else {
      this.question.question = this.questionText
      this.question.answerType = this.selectedAnswerType
    }
    this.onSave.emit(this.question)
    this.currentEditSubject.next(this.question)
    this.visible = false
    this.visibleChange.emit(false)
    this.clearForm()
  }

}
