// Main application module that configures the core components, modules, and services.

import { NgModule, importProvidersFrom } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';

import { MenubarModule } from 'primeng/menubar';

import { StartComponent } from './start/start.component';
import { HttpClientModule } from '@angular/common/http';
import { ApiModule, BASE_PATH } from './api';
import { environment } from '../environments/environment';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { registerLocaleData } from '@angular/common';
import localeDe from '@angular/common/locales/de';
import { CardModule } from 'primeng/card';

registerLocaleData(localeDe)

@NgModule({
  declarations: [
    AppComponent,
    StartComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    ApiModule,
    BrowserAnimationsModule,
    MenubarModule,
    CardModule
  ],
  providers: [
    importProvidersFrom(HttpClientModule),
    { provide: BASE_PATH, useValue: environment.apiBase },
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
