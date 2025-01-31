// Service for interacting with the API to manage and retrieve checklist data.

import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '../environments/environment';
import { Checklist } from '../dto/checklist';

@Injectable({
  providedIn: 'root' // Makes the service available throughout the app via dependency injection.
})
export class ArchiveService {

  constructor(private http: HttpClient) { } // Injects HttpClient for making HTTP requests.

  // Fetches all checklists from the API.
  getAllChecklists() {
    return this.http.get<Checklist[]>(environment.apiBase + 'checklist/all');
  }

  // Fetches a specific checklist by its ID from the API.
  getChecklistById(id: string) {
    return this.http.get<Checklist>(environment.apiBase + 'checklist/' + id);
  }

}

