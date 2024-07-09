import { Component, ViewChild } from '@angular/core';
import { AnswerType, Question, QuestionsService } from '../api';
import { AutoComplete, AutoCompleteCompleteEvent, AutoCompleteSelectEvent } from 'primeng/autocomplete';
import { v4 as uuidv4 } from 'uuid';
import { TexGeneratorService } from '../tex-generator.service';
import { MessageService } from 'primeng/api';

@Component({
  selector: 'app-generator',
  templateUrl: './generator.component.html',
  styleUrl: './generator.component.scss',
  providers: [MessageService]
})
export class GeneratorComponent {

  suggestions: Question[] = []

  @ViewChild('autocomplete')
  autocomplete!: AutoComplete

  questions: LocalQuestion[] = []
  addQuestionVisible = false
  newQuestion: string | null = null
  answerTypes = [AnswerType.FreeText, AnswerType.FreeTextAndJustification, AnswerType.None]
  newAnswerType: AnswerType | null = null

  texGenerationLoading = false
  texOutput = ''
  outputDialogVisible = false

  recommendedQuestions: Question[] = []
  activeRecommendation: number | null = null
  recommendationFadeout = false

  constructor(private questionsService: QuestionsService, private texService: TexGeneratorService, private msgService: MessageService) {
    // this.addQuestion(new LocalQuestion('First question', AnswerType.FreeText))
    // this.addQuestion(new LocalQuestion('Second question', AnswerType.FreeTextAndJustification))
    // this.addQuestion(new LocalQuestion('Third question', AnswerType.None))
    // this.addQuestion(new LocalQuestion('Fourth question', AnswerType.FreeTextAndJustification))
  }

  search(event: AutoCompleteCompleteEvent) {
    //TODO: Error handling
    this.questionsService.getAppQuestionSearch(event.query)
      .subscribe(res => {
        this.suggestions = res
      })
  }

  select(event: AutoCompleteSelectEvent) {
    let question: Question = event.value
    this.addQuestion(new LocalQuestion(question.question, question.answerType, question.id ?? uuidv4()))
    this.autocomplete.clear()
  }

  remove(index: number) {
    this.questions.splice(index, 1)
  }

  move(index: number, up: boolean) {
    let newPos = index + (up ? -1 : 1)
    let tmp = this.questions[newPos]
    this.questions[newPos] = this.questions[index]
    this.questions[index] = tmp
  }

  addQuestionClick() {

    //TODO: Add invalid flags when one of these cases is hit
    if (!this.newQuestion) {
      return
    }

    if (!this.newAnswerType) {
      return
    }

    this.addQuestion(new LocalQuestion(this.newQuestion, this.newAnswerType))
    this.addQuestionVisible = false
    this.clearNewQuestionForm()
  }

  addQuestion(question: LocalQuestion) {
    this.questions.push(question)

    let except = this.questions.map(q => q.id)

    //TODO: Error handling
    this.questionsService.getAppQuestionRandom(except)
      .subscribe(res => {
        this.activeRecommendation = null
        this.recommendationFadeout = false
        this.recommendedQuestions = res
      })

  }

  clearNewQuestionForm() {
    this.newQuestion = null
    this.newAnswerType = null
  }

  generateTex() {
    this.texGenerationLoading = true
    this.texOutput = this.texService.buildTex(this.questions)
    this.outputDialogVisible = true
    this.texGenerationLoading = false
  }

  copyToClipboard() {
    navigator.clipboard.writeText(this.texOutput)
      .then(() => {
        this.msgService.add({
          severity: 'success',
          summary: 'Copied!',
          detail: 'The tex code was copied to your clipboard!'
        })
      })
      .catch(() => {
        this.msgService.add({
          severity: 'error',
          summary: 'Error!',
          detail: 'Could not write to your clipboard! Please copy manually or download.'
        })
      })
  }

  download() {
    let blob = new Blob([this.texOutput])
    let a = document.createElement('a')
    a.style.display = 'none'
    a.download = 'checklist.tex'
    a.href = URL.createObjectURL(blob)

    // For some browser we need to append it to the body
    document.body.appendChild(a)

    a.click()
    console.log(a.href);


    // Cleanup
    document.body.removeChild(a)
    URL.revokeObjectURL(a.href)
  }

  suggestionAdd(event: MouseEvent, question: Question, index: number) {
    event.stopPropagation()
    this.activeRecommendation = null
    this.recommendedQuestions.splice(index, 1)
    this.recommendationFadeout = true

    this.addQuestion(new LocalQuestion(question.question, question.answerType, question.id ?? uuidv4()))
  }
}

export class LocalQuestion {
  constructor(public question: string, public answerType: AnswerType, public id = uuidv4()) { }
}
