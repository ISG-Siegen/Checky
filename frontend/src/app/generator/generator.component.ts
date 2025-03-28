import { Component, SecurityContext, ViewChild } from '@angular/core';
import { AnswerType, Question, QuestionsService, SaveChecklistRequest, SaveQuestionRequest, SaveService } from '../api';
import { AutoComplete, AutoCompleteCompleteEvent, AutoCompleteSelectEvent } from 'primeng/autocomplete';
import { v4 as uuidv4 } from 'uuid';
import { TexGeneratorService } from '../tex-generator.service';
import { MessageService } from 'primeng/api';
import { QuestionEditorComponent } from '../question-editor/question-editor.component';
import { Location } from '@angular/common';
import { ActivatedRoute } from '@angular/router';
import { catchError, EMPTY, finalize, tap } from 'rxjs';
import { HttpErrorResponse } from '@angular/common/http';
import { DomSanitizer } from '@angular/platform-browser';

@Component({
  selector: 'app-generator',
  templateUrl: './generator.component.html',
  styleUrl: './generator.component.scss',
  providers: [MessageService]
})
export class GeneratorComponent {

  // Suggestion list for autocomplete
  suggestions: Question[] = []

  @ViewChild('autocomplete')
  autocomplete!: AutoComplete

  // Question management
  questions: LocalQuestion[] = []
  addQuestionVisible = false
  newQuestion: string | null = null
  answerTypes = [AnswerType.FreeText, AnswerType.FreeTextAndJustification, AnswerType.None]
  newAnswerType: AnswerType | null = null

  // LaTeX generation
  texGenerationLoading = false
  texOutput = ''
  outputDialogVisible = false

  // Normal recommendations
  recommendedQuestions: Question[] = []
  fetchRecommendationsLoading = false
  activeRecommendation: number | null = null

  // ChatGPT recommendations
  recommendedGPTQuestions: LocalQuestion[] = []
  fetchGPTRecommendationsLoading = false

  // Checklist saving
  saveChecklistLoading = false
  saveDialogVisible = false
  currentChecklistName = ''
  currentChecklistDescription = 'Carefully read each question and replace the default \\checklistTODO\\space with your answer. If a question asks for justification, make sure to include a brief explanation in your response.'
  currentChecklistUuid: string | null = null
  currentEditLink = ''
  loadingSavedChecklist = false
  errorMsg = ''

  rateLimitNextFree = ''

  @ViewChild('questionEditor')
  questionEditor!: QuestionEditorComponent

  constructor(
    private questionsService: QuestionsService,
    private texService: TexGeneratorService,
    private msgService: MessageService,
    private saveService: SaveService,
    private location: Location,
    private activatedRoute: ActivatedRoute,
    private sanitizer: DomSanitizer
  ) {
    // this.addQuestion(new LocalQuestion('First question', AnswerType.FreeText))
    // this.addQuestion(new LocalQuestion('Second question', AnswerType.FreeTextAndJustification))
    // this.addQuestion(new LocalQuestion('Third question', AnswerType.None))
    // this.addQuestion(new LocalQuestion('Fourth question', AnswerType.FreeTextAndJustification))

    let loadUuid = this.activatedRoute.snapshot.paramMap.get('uuid')
    if (loadUuid) {
      // Loads the checklist by UUID, if available
      //TODO: Error handling
      this.loadingSavedChecklist = true
      saveService.getByUuid(loadUuid)
        .pipe(
          catchError((err: HttpErrorResponse) => {
            console.error(err);
            if (err.status == 404) {
              this.errorMsg = 'Checklist not found!'
            } else {
              this.errorMsg = 'An error occurred while loading this checklist! Please try again later.'
            }
            this.loadingSavedChecklist = false
            return EMPTY
          })
        )
        .subscribe(res => {

          if (res) {
            this.currentChecklistName = res.name
            this.currentChecklistDescription = res.description
            this.currentChecklistUuid = res.id ?? uuidv4()

            for (const [index, q] of res.questions.entries()) {
              // Only fetch recommendations when adding the last one
              const isLast = index == res.questions.length - 1
              this.addQuestion(new LocalQuestion(q.question, q.answerType, q.originalQuestion?.id), isLast)
            }
          } else {
            this.errorMsg = 'Checklist not found!'
          }

          this.loadingSavedChecklist = false
        })
    }
  }

  search(event: AutoCompleteCompleteEvent) {
    // Searches for questions based on the user's input
    //TODO: Error handling
    this.questionsService.getAppQuestionSearch(event.query)
      .pipe(
        tap(res => {
          res.forEach(q => {
            q.question = this.sanitizer.sanitize(SecurityContext.HTML, q.question) ?? ''
          })
        })
      )
      .subscribe(res => {
        this.suggestions = res
      })
  }

  select(event: AutoCompleteSelectEvent) {
    // Adds the selected question to the checklist
    let question: Question = event.value
    this.addQuestion(new LocalQuestion(question.question, question.answerType, question.id))
    this.autocomplete.clear()
  }

  edit(index: number) {
    // Opens the editor to modify a question
    this.questionEditor.editQuestion(this.questions[index])
      .subscribe(editedQuestion => {
        this.questions[index] = editedQuestion
      })
  }

  remove(index: number) {
    this.questions.splice(index, 1)
  }

  move(index: number, up: boolean) {
    // Reorders questions in the checklist
    let newPos = index + (up ? -1 : 1)
    let tmp = this.questions[newPos]
    this.questions[newPos] = this.questions[index]
    this.questions[index] = tmp
  }


  addQuestion(question: LocalQuestion, fetchRecommendations = true) {
    this.questions.push(question)
    if (fetchRecommendations) {
      if (this.recommendedQuestions.length == 0) {
        this.fetchRecommendations()
      }

      if (this.recommendedGPTQuestions.length == 0) {
        this.fetchGPTRecommendations()
      }
    }
  }

  fetchRecommendations() {
    // Fetches similar questions from the archive
    //TODO: Error handling
    this.activeRecommendation = null
    this.fetchRecommendationsLoading = true

    let except = this.questions.reduce((filtered, current) => {
      if (current.originalQuestionId) {
        filtered.push(current.originalQuestionId)
      }
      return filtered
    }, [] as string[])

    this.recommendedQuestions.forEach(q => {
      if (q.id) {
        except.push(q.id)
      }
    })

    let query = this.questions.reduce((accu, current) => {
      return accu + ' ' + current.question
    }, '')

    this.questionsService.getAppQuestionSimilar(query, except)
      .subscribe(res => {
        this.recommendedQuestions = res
        this.fetchRecommendationsLoading = false
      })
  }

  fetchGPTRecommendations() {
    //TODO: Error handling
    this.fetchGPTRecommendationsLoading = true

    let questionStrings = this.questions.map(q => q.question)

    this.questionsService.getAppQuestionGpt(questionStrings)
      .pipe(
        catchError((err: HttpErrorResponse) => {
          if (err.status == 429) {
            this.rateLimitNextFree = err.error
          }

          return EMPTY
        }),
        finalize(() => {
          this.fetchGPTRecommendationsLoading = false
        })
      )
      .subscribe(res => {
        const newQuestions = res.map(qText => {
          return new LocalQuestion(qText, AnswerType.FreeTextAndJustification)
        })
        this.rateLimitNextFree = ''
        this.recommendedGPTQuestions = newQuestions
      })
  }

  generateTex() {
    this.texGenerationLoading = true
    this.texOutput = this.texService.buildTex(this.currentChecklistName, this.currentChecklistDescription, this.questions)
    this.outputDialogVisible = true
    this.texGenerationLoading = false
  }

  copyTexToClipboard() {
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

  downloadTex() {
    let blob = new Blob([this.texOutput])
    let a = document.createElement('a')
    a.style.display = 'none'
    a.download = 'checklist.tex'
    a.href = URL.createObjectURL(blob)

    // For some browser we need to append it to the body
    document.body.appendChild(a)

    a.click()

    // Cleanup
    document.body.removeChild(a)
    URL.revokeObjectURL(a.href)
  }

  suggestionAdd(event: MouseEvent, question: Question, index: number) {
    // Adds a recommended question to the checklist
    event.stopPropagation()
    this.recommendedQuestions.splice(index, 1)
    this.addQuestion(new LocalQuestion(question.question, question.answerType, question.id))
  }

  gptSuggestionAdd(event: MouseEvent, question: LocalQuestion, index: number) {
    // Adds a ChatGPT-recommended question to the checklist
    event.stopPropagation()
    this.recommendedGPTQuestions.splice(index, 1)
    this.addQuestion(question)
  }

  saveChecklist() {
    let questionRequests: SaveQuestionRequest[] = this.questions.map(q => {
      return {
        question: {
          question: q.question,
          answerType: q.answerType
        },
        originalQuestion: q.originalQuestionId ?? null
      }
    })

    let request: SaveChecklistRequest = {
      uuid: this.currentChecklistUuid,
      name: this.currentChecklistName,
      description: this.currentChecklistDescription,
      questionRequests: questionRequests
    }

    this.saveChecklistLoading = true

    this.saveService.postSave(request)
      .subscribe(res => {
        this.location.go('/generator/' + res)
        this.currentEditLink = window.location.href
        this.saveDialogVisible = true
        this.saveChecklistLoading = false
      })

  }

  copyEditLinkToClipboard() {
    navigator.clipboard.writeText(this.currentEditLink)
      .then(() => {
        this.msgService.add({
          severity: 'success',
          summary: 'Copied!',
          detail: 'Link copied to your clipboard!'
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
}

export class LocalQuestion {

  // Only used for tracking in @for
  id = uuidv4()

  constructor(public question: string, public answerType: AnswerType, public originalQuestionId?: string | null) { }
}
