import { Component } from '@angular/core';
import { ArchiveService } from '../archive.service';
import { Checklist } from '../../dto/checklist';

@Component({
  selector: 'app-archive',
  templateUrl: './archive.component.html',
  styleUrl: './archive.component.scss'
})
export class ArchiveComponent {

  checklists: Checklist[] | null = null

  constructor(private archive: ArchiveService) {
    archive.getAllChecklists().subscribe(res => {
      this.checklists = res
    })
  }

}
