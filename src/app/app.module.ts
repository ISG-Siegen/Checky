import { NgModule, importProvidersFrom } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';

import { MenubarModule } from 'primeng/menubar';
import { CardModule } from 'primeng/card';

import { ArchiveComponent } from './archive/archive.component';
import { StartComponent } from './start/start.component';
import { HttpClientModule } from '@angular/common/http';
import { ChecklistComponent } from './checklist/checklist.component';

@NgModule({
  declarations: [
    AppComponent,
    ArchiveComponent,
    StartComponent,
    ChecklistComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    MenubarModule,
    CardModule,
  ],
  providers: [
    importProvidersFrom(HttpClientModule)
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
