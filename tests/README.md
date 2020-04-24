We use the following commands with PhpUnit and PhpDoc to test and produce documentation you may wish to do the same.
- **Run Unit Tests**
  - *phpunit [pathToProjectRoot]/tests*
- **Produce Documentation**
  - *phpdoc --ignore &ast;/mocks/&ast; -d [pathToProjectRoot]/code/,[pathToProjectRoot]/tests/ -t [pathToDocumentationFolder]/php --visibility=public,protected && phpunit --coverage-html [pathToDocumentationFolder]/coverage [pathToProjectRoot]/tests --whitelist [pathToProjectRoot]/code*
  - **NOTE: We ignore anything in a mocks folder as they may share namespaces with our main code and become entangled in there documentation.**
