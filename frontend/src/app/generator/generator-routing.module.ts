// Routing module for the Generator feature, defining routes and their components.

import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { GeneratorComponent } from './generator.component';

const routes: Routes = [
  { path: ':uuid', component: GeneratorComponent },
  { path: '', component: GeneratorComponent }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class GeneratorRoutingModule { }
