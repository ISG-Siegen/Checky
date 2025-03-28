// Component for editing or creating a question, with form inputs for text and answer type.

import { Component, EventEmitter, Input, Output } from '@angular/core';
import { AnswerType } from '../api';
import { LocalQuestion } from '../generator/generator.component';
import { Subject } from 'rxjs';
import { AnswerTypePipe } from '../answer-type.pipe';

@Component({
  selector: 'app-question-editor',
  templateUrl: './question-editor.component.html',
  styleUrl: './question-editor.component.scss'
})
export class QuestionEditorComponent {

  // Header text for the dialog (e.g., "Edit Question" or "New Question").
  @Input()
  header = '';

  // Visibility state of the dialog.
  @Input({ required: true })
  visible = false;

  // Event emitter for visibility changes to notify parent components.
  @Output()
  visibleChange = new EventEmitter<boolean>();

  // Input fields for the question text and answer type.
  questionText = '';
  answerTypes = [AnswerType.FreeText, AnswerType.FreeTextAndJustification, AnswerType.None].map(type => { return { label: this.AnswerTypePipe.transform(type), value: type } })
  selectedAnswerType: AnswerType | null = null;

  // Optional input for editing an existing question.
  @Input()
  question: LocalQuestion | null = null;

  // Event emitter for saving the question, notifying the parent component.
  @Output()
  onSave = new EventEmitter<LocalQuestion>();

  // Subject to manage the current edit state and provide an observable.
  currentEditSubject = new Subject<LocalQuestion>();

  constructor(private AnswerTypePipe: AnswerTypePipe) { }

  // Opens the editor with a specific question for editing.
  editQuestion(question: LocalQuestion) {
    this.question = question;
    this.questionText = question.question;
    this.selectedAnswerType = question.answerType;
    this.visible = true;
    this.currentEditSubject = new Subject();
    return this.currentEditSubject.asObservable();
  }

  // Clears the form inputs, resetting text and answer type.
  clearForm() {
    this.questionText = '';
    this.selectedAnswerType = null;
    this.question = null;
  }

  // Saves the current question and emits events for parent components.
  save(event: MouseEvent) {
    // Validation: Ensure both the question text and answer type are provided.
    if (!this.questionText) {
      return; // Exit if the question text is empty.
    }

    if (!this.selectedAnswerType) {
      return; // Exit if no answer type is selected.
    }

    // If no question exists, create a new one; otherwise, update the existing question.
    if (!this.question) {
      this.question = new LocalQuestion(this.questionText, this.selectedAnswerType);
    } else {
      this.question.question = this.questionText;
      this.question.answerType = this.selectedAnswerType;
    }

    // Emit the saved question to the parent component.
    this.onSave.emit(this.question);

    // Notify subscribers of the current edit state.
    this.currentEditSubject.next(this.question);

    // Close the dialog and clear the form inputs.
    this.visible = false;
    this.visibleChange.emit(false);
    this.clearForm();
  }

}
