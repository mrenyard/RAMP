The following scrips for testing are avalible at the root of this project:
```console
./coverage
./document
```

We also use the Visual Studio Extension **PhpTestExplorer** with the below settings:\
https://marketplace.visualstudio.com/items?itemName=renandelmonico.vscode-php-test-explorer
```json
{
    "phpunit.args": ["tests"],
    "phpunit.files": "php8.2 phpunit {tests,Tests}",
    "phpunit.shell": "bash",
    "phpunit.showAfterExecution": "always",
    "phpunit.logpanel": true,
    "html.format.indentInnerHtml": true,
    "html.format.wrapLineLength": 0,
    "editor.tabSize": 2,
    "git.confirmSync": false,
    "files.autoSave": "afterDelay",
    "workbench.editor.enablePreview": false,
    "zenMode.hideLineNumbers": false,
    "diffEditor.ignoreTrimWhitespace": false,
    "phpunit.phpunit": "/usr/bin/php8.2 phpunit",
    "phpunit.php": "/usr/bin/php8.2 phpunit.phar $HOME/Projects/RAMP/tests --whitelist $HOME/Projects/RAMP/code",
    "phpunit.relativeFilePath": true
}
```
