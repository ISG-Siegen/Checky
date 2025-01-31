// Test suite for the AnswerTypePipe to ensure its correct behavior and instantiation.
import { AnswerTypePipe } from './answer-type.pipe';

describe('AnswerTypePipe', () => {
  it('create an instance', () => {
    const pipe = new AnswerTypePipe();
    expect(pipe).toBeTruthy();
  });
});
