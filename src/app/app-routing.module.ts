import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ArchiveComponent } from './archive/archive.component';
import { StartComponent } from './start/start.component';
import { ChecklistComponent } from './checklist/checklist.component';

const routes: Routes = [
  { path: '', component: StartComponent },
  { path: 'archive', component: ArchiveComponent },
  { path: 'archive/:id', component: ChecklistComponent },
  //TODO: Add 404 handler
  { path: '**', component: StartComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
