// Unit tests for the ArchiveService to verify its initialization and behavior.

import { TestBed } from '@angular/core/testing';
import { ArchiveService } from './archive.service';

describe('ArchiveService', () => {
  let service: ArchiveService;

  beforeEach(() => {
    // Sets up the testing module and provides the ArchiveService instance.
    TestBed.configureTestingModule({});
    service = TestBed.inject(ArchiveService);
  });

  it('should be created', () => {
    // Verifies that the service is successfully created.
    expect(service).toBeTruthy();
  });
});
