// Module for the Archive feature, managing its components and dependencies

import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FaqRoutingModule } from './faq-routing.module';
import { FaqComponent } from './faq.component';
import { AccordionModule } from 'primeng/accordion';
import { CardModule } from 'primeng/card';
import { DividerModule } from 'primeng/divider';


@NgModule({
  declarations: [
    FaqComponent
  ],
  imports: [
    CommonModule,
    FaqRoutingModule,
    AccordionModule, // PrimeNG Accordion
    CardModule,      // PrimeNG Card
    DividerModule    // PrimeNG Divider
  ]
})
export class FaqModule { }

