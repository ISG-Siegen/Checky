import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ArchiveService } from '../archive.service';
import { Checklist } from '../../dto/checklist';

@Component({
  selector: 'app-checklist',
  templateUrl: './checklist.component.html',
  styleUrl: './checklist.component.scss'
})
export class ChecklistComponent {

  checklist: Checklist | null = null

  constructor(private route: ActivatedRoute, private archive: ArchiveService) {
    let id = route.snapshot.paramMap.get('id')

    if (id == null) {
      return
    }

    archive.getChecklistById(id).subscribe(res => {
      this.checklist = res
    })
  }
}
