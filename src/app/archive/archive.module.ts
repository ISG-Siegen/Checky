import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ArchiveRoutingModule } from './archive-routing.module';
import { ArchiveComponent } from './archive.component';
import { TreeModule } from 'primeng/tree';
import { ButtonModule } from 'primeng/button';
import { InputTextareaModule } from 'primeng/inputtextarea';
import { ToastModule } from 'primeng/toast';
import { DividerModule } from 'primeng/divider';


@NgModule({
  declarations: [
    ArchiveComponent
  ],
  imports: [
    CommonModule,
    ArchiveRoutingModule,
    TreeModule,
    ButtonModule,
    InputTextareaModule,
    ToastModule,
    DividerModule
  ]
})
export class ArchiveModule { }
