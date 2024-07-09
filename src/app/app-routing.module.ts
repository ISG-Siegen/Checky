import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ArchiveComponent } from './archive/archive.component';
import { StartComponent } from './start/start.component';

const routes: Routes = [
  { path: '', component: StartComponent },
  { path: 'archive', component: ArchiveComponent },
  { path: 'archive', loadChildren: () => import('./archive/archive.module').then(m => m.ArchiveModule) },
  { path: 'generator', loadChildren: () => import('./generator/generator.module').then(m => m.GeneratorModule) },
  //TODO: Add 404 handler
  { path: '**', component: StartComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
