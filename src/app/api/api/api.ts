export * from './archive.service';
import { ArchiveService } from './archive.service';
export * from './questions.service';
import { QuestionsService } from './questions.service';
export * from './save.service';
import { SaveService } from './save.service';
export const APIS = [ArchiveService, QuestionsService, SaveService];
