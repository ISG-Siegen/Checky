
import subprocess
import re
import os

# Generates OpenAPI spec and client code
# It is assumed that the frontend lives in ../checklist
# openapi-generator-cli is required: npm install @openapitools/openapi-generator-cli -g

API_MODULE_DIR = '../checklist/src/app/api'
API_MODEL_DIR = API_MODULE_DIR + '/model/'
HYDRA_RE_PATTERN = re.compile(r'hydra(.+?)(\?)?:')
AT_RE_PATTERN = re.compile(r'(id|type|context)(\?)?:')

def run(cmd):
    subprocess.run(cmd, shell=True)

# Generate OpenAPI spec file
run('php bin/console api:openapi:export --output=api_spec.json')

# Generate angular typescript client from file
run(f'npx openapi-generator-cli generate -i api_spec.json -g typescript-angular -o {API_MODULE_DIR} --additional-properties fileNaming=kebab-case,withInterfaces=true')

# Fix colon and @ in hydra names
for file in os.listdir(API_MODEL_DIR):
    print(f'Patching file {file}')
    with open(API_MODEL_DIR + file, 'r+') as f:
        content = f.read()
        content = re.sub(HYDRA_RE_PATTERN, r"'hydra:\1'\2:", content)
        content = re.sub(AT_RE_PATTERN, r"'@\1'\2:", content)
        f.seek(0)
        f.truncate()
        f.write(content)
