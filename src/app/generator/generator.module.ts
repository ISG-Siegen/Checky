import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { GeneratorRoutingModule } from './generator-routing.module';
import { GeneratorComponent } from './generator.component';
import { FormsModule } from '@angular/forms';
import { AutoCompleteModule } from 'primeng/autocomplete';
import { CardModule } from 'primeng/card';
import { ButtonModule } from 'primeng/button';
import { DialogModule } from 'primeng/dialog';
import { FloatLabelModule } from 'primeng/floatlabel';
import { SelectButtonModule } from 'primeng/selectbutton';
import { InputTextareaModule } from 'primeng/inputtextarea';
import { ToastModule } from 'primeng/toast';
import { AccordionModule } from 'primeng/accordion';
import { QuestionEditorComponent } from '../question-editor/question-editor.component';
import { InputTextModule } from 'primeng/inputtext';
import { DividerModule } from 'primeng/divider';
import { SkeletonModule } from 'primeng/skeleton';
import { TooltipModule } from 'primeng/tooltip';

import { AnswerTypePipe } from '../answer-type.pipe';


@NgModule({
  declarations: [
    GeneratorComponent,
    QuestionEditorComponent,
    AnswerTypePipe
  ],
  imports: [
    CommonModule,
    GeneratorRoutingModule,
    FormsModule,
    AutoCompleteModule,
    CardModule,
    ButtonModule,
    DialogModule,
    InputTextareaModule,
    FloatLabelModule,
    SelectButtonModule,
    ToastModule,
    AccordionModule,
    InputTextModule,
    DividerModule,
    SkeletonModule,
    TooltipModule
  ]
})
export class GeneratorModule { }
