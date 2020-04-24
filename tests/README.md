We use the following commands with PhpUnit and PhpDoc to test and produce documentation you may wish to do the same.
**Run Unit Tests**
```bash
phpunit [pathToProjectRoot]/tests
```
**Produce Documentation**
```bash
phpdoc --ignore */mocks/* -d [pathToProjectRoot]/code/,[pathToProjectRoot]/tests/ -t [pathToDocumentationFolder]/php --visibility=public,protected && phpunit --coverage-html [pathToDocumentationFolder]/coverage [pathToProjectRoot]/tests --whitelist [pathToProjectRoot]/code
```
**NOTE: We ignore anything in a mocks folder as they may share namespaces with our main code and become entangled in there documentation.**
