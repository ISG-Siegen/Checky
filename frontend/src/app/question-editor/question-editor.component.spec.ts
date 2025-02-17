// Unit tests for the QuestionEditorComponent to verify its initialization and behavior

import { ComponentFixture, TestBed } from '@angular/core/testing';

import { QuestionEditorComponent } from './question-editor.component';

describe('QuestionEditorComponent', () => {
  let component: QuestionEditorComponent;
  let fixture: ComponentFixture<QuestionEditorComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [QuestionEditorComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(QuestionEditorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
