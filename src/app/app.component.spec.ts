// Unit tests for the AppComponent using Angular's testing framework. 
// These tests ensure the AppComponent initializes correctly, has the expected title, and renders the title in the template.

import { TestBed } from '@angular/core/testing';
import { RouterTestingModule } from '@angular/router/testing';
import { AppComponent } from './app.component';

describe('AppComponent', () => {
  beforeEach(async () => {
    // Sets up the testing environment for the AppComponent, including required modules and declarations.
    await TestBed.configureTestingModule({
      imports: [
        RouterTestingModule // Provides routing support for tests.
      ],
      declarations: [
        AppComponent // Declares the component being tested.
      ],
    }).compileComponents(); // Compiles the components for testing.
  });

  it('should create the app', () => {
    // Verifies that the AppComponent is successfully created.
    const fixture = TestBed.createComponent(AppComponent);
    const app = fixture.componentInstance;
    expect(app).toBeTruthy(); // Ensures the app instance is not null or undefined.
  });

  it(`should have as title 'checklist'`, () => {
    // Tests that the title property of the AppComponent matches the expected value.
    const fixture = TestBed.createComponent(AppComponent);
    const app = fixture.componentInstance;
    expect(app.title).toEqual('checklist');
  });

  it('should render title', () => {
    // Ensures the component's template renders the title correctly in an <h1> element.
    const fixture = TestBed.createComponent(AppComponent);
    fixture.detectChanges(); // Triggers change detection to update the template.
    const compiled = fixture.nativeElement as HTMLElement;
    expect(compiled.querySelector('h1')?.textContent).toContain('Hello, checklist');
  });
});
