--------------------------------
Full list of avalible properties
for use in template files (.tpl)
--------------------------------
## Universally available

 - id
 - class
 - style
 - title
 - heading
 - label
 - summary
 - placeholder
 - extendedSummary (NOT as attribute)
 - extendedContent (NOT as attribute)
 - footnote (NOT as attribute)

## Available under condition

`->hasModel:TRUE`
 - type

` ->hasErrors:TRUE`
 -   errors:foreach

`->type == '[…] record-component'`
 - isEditable
 - value

`->type == '[…] field'`
 - isRequired

`->type =='input field'`
 - inputType
 - pattern
 - minlength
 - maxlength
 - min
 - max
 - step
 - hint
