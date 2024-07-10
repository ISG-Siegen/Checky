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


@NgModule({
  declarations: [
    GeneratorComponent,
    QuestionEditorComponent
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
    AccordionModule

  ]
})
export class GeneratorModule { }
