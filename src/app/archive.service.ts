import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '../environments/environment';
import { Checklist } from '../dto/checklist';

@Injectable({
  providedIn: 'root'
})
export class ArchiveService {

  constructor(private http: HttpClient) { }

  getAllChecklists() {
    return this.http.get<Checklist[]>(environment.apiBase + 'checklist/all')
  }

  getChecklistById(id: string) {
    return this.http.get<Checklist>(environment.apiBase + 'checklist/' + id)
  }

}
