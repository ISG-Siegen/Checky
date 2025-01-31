export * from './archive.service';
import { ArchiveService } from './archive.service';
export * from './default.service';
import { DefaultService } from './default.service';
export * from './questions.service';
import { QuestionsService } from './questions.service';
export * from './save.service';
import { SaveService } from './save.service';
export const APIS = [ArchiveService, DefaultService, QuestionsService, SaveService];
