npx openapi-generator-cli generate -i http://127.0.0.1:8000/api/doc.json -g typescript-angular -o ../checklist/src/app/api --additional-properties fileNaming=kebab-case