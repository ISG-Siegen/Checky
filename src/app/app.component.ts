import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrl: './app.component.scss'
})
export class AppComponent {
  title = 'checklist';

  items = [
    {
      label: 'Start',
      icon: 'pi pi-home',
      route: '',
    },
    {
      label: 'Archive',
      icon: 'pi pi-building-columns',
      route: '/archive',
    },
    {

      label: 'Generator',
      icon: 'pi pi-cog',
      route: '/generator',
    }
  ]

}
