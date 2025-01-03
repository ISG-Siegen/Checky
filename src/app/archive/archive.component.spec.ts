// Unit tests for the ArchiveComponent to verify its initialization and behavior.

import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ArchiveComponent } from './archive.component';

describe('ArchiveComponent', () => {
  let component: ArchiveComponent; // Reference to the component being tested.
  let fixture: ComponentFixture<ArchiveComponent>; // Test fixture to interact with the component and its template.

  beforeEach(async () => {
    // Sets up the testing module with the ArchiveComponent declared.
    await TestBed.configureTestingModule({
      declarations: [ArchiveComponent],
    }).compileComponents();

    // Creates the component instance and initializes its template and data bindings.
    fixture = TestBed.createComponent(ArchiveComponent);
    component = fixture.componentInstance;
    fixture.detectChanges(); 
  });

  it('should create', () => {
    // Test case to verify the component is created successfully.
    expect(component).toBeTruthy();
  });
});
