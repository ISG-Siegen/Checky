// Unit tests for the GeneratorComponent to verify its initialization and basic behavior.

import { ComponentFixture, TestBed } from '@angular/core/testing';
import { GeneratorComponent } from './generator.component';

describe('GeneratorComponent', () => {
  let component: GeneratorComponent; 
  let fixture: ComponentFixture<GeneratorComponent>;

  beforeEach(async () => {
    // Configures and compiles the testing module with the GeneratorComponent declared.
    await TestBed.configureTestingModule({
      declarations: [GeneratorComponent],
    }).compileComponents();
    
    // Initializes the component and triggers Angular's change detection.
    fixture = TestBed.createComponent(GeneratorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    // Verifies that the component instance is created successfully.
    expect(component).toBeTruthy();
  });
});
