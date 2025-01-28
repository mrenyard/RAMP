Full list of available properties for use in template files (.tpl)
------------------------------------------------------------------
## Universally available
 - id
   - attribute('id')
 - title
   - attribute('title')
 - heading
 - label
 - summary
 - placeholder
   - attribute('placeholder')
 - extendedSummary
 - extendedContent
 - footnote
 - style
 - class
   - attribute('class')

 - children

## Available under condition
`hasModel === TRUE` is prerequisite for all following properties. 
 - type

`hasErrors === TRUE` before `foreach($this->errors as $error) {…`
 - errors :foreach

`type == '[…] record-component' || type == '[…] field'`
 - isEditable :boolean
   - attribute('readonly')
 - value
   - attribute('value')

`type == '[…] field'`
 - isRequired
   - attribute('required')

`type =='input field'`
 - inputType
 - pattern
   - attribute('pattern')
 - minlength
   - attribute('minlength')
 - maxlength
   - attribute('maxlength')
 - min
   - attribute('min')
 - max
   - attribute('max')
 - step
   - attribute('step')
 - hint
   - attribute('hint')
