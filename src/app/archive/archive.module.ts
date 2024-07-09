import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ArchiveRoutingModule } from './archive-routing.module';
import { ArchiveComponent } from './archive.component';
import { TreeModule } from 'primeng/tree';
import { ButtonModule } from 'primeng/button';


@NgModule({
  declarations: [
    ArchiveComponent
  ],
  imports: [
    CommonModule,
    ArchiveRoutingModule,
    TreeModule,
    ButtonModule
  ]
})
export class ArchiveModule { }
