// Unit tests for TexGeneratorService to verify its creation and basic functionality.

import { TestBed } from '@angular/core/testing';

import { TexGeneratorService } from './tex-generator.service';

describe('TexGeneratorService', () => {
  let service: TexGeneratorService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(TexGeneratorService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
