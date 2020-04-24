We use the following commands with PhpUnit and PhpDoc to test and produce documentation you may wish to do the same.
- **Run Unit Tests**
  - *phpunit [pathToProjectRoot]/tests*
- **Produce Documentation**
  - *phpdoc --ignore */mocks/* -d [pathToProjectRoot]/code/,[pathToProjectRoot]/tests/ -t [pathToDocumentationFolder]/php --visibility=public,protected && phpunit --coverage-html [pathToDocumentationFolder]/coverage [pathToProjectRoot]/tests --whitelist [pathToProjectRoot]/code*
