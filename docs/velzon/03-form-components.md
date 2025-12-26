# Form Components

Velzon provides a comprehensive set of form components built on Bootstrap's form system, including various input types, select elements, checkboxes, radios, file uploads, and validation styles.

---

## Table of Contents

- [Input Elements](#input-elements)
- [Input Sizing](#input-sizing)
- [Input Groups](#input-groups)
- [Textarea](#textarea)
- [Select Elements](#select-elements)
- [File Inputs](#file-inputs)
- [Checkboxes & Radios](#checkboxes--radios)
- [Switches](#switches)
- [Form Validation](#form-validation)
- [Advanced Selects (Choices.js)](#advanced-selects-choicesjs)
- [Best Practices](#best-practices)

---

## Input Elements

### Basic Inputs

Standard text input with label:

```blade
<div>
    <label for="basiInput" class="form-label">Basic Input</label>
    <input type="text" class="form-control" id="basiInput">
</div>
```

### Input with Placeholder

```blade
<div>
    <label for="placeholderInput" class="form-label">Input with Placeholder</label>
    <input type="password" class="form-control" id="placeholderInput" placeholder="Placeholder">
</div>
```

### Input with Value

```blade
<div>
    <label for="valueInput" class="form-label">Input with Value</label>
    <input type="text" class="form-control" id="valueInput" value="Input value">
</div>
```

### Readonly Inputs

#### Readonly Plain Text

```blade
<div>
    <label for="readonlyPlaintext" class="form-label">Readonly Plain Text Input</label>
    <input type="text" class="form-control-plaintext" id="readonlyPlaintext" 
           value="Readonly input" readonly>
</div>
```

#### Readonly Input

```blade
<div>
    <label for="readonlyInput" class="form-label">Readonly Input</label>
    <input type="text" class="form-control" id="readonlyInput" 
           value="Readonly input" readonly>
</div>
```

### Disabled Input

```blade
<div>
    <label for="disabledInput" class="form-label">Disabled Input</label>
    <input type="text" class="form-control" id="disabledInput" 
           value="Disabled input" disabled>
</div>
```

### Input with Icon

#### Icon on Left

```blade
<div>
    <label for="iconInput" class="form-label">Input with Icon</label>
    <div class="form-icon">
        <input type="email" class="form-control form-control-icon" 
               id="iconInput" placeholder="example@gmail.com">
        <i class="ri-mail-unread-line"></i>
    </div>
</div>
```

#### Icon on Right

```blade
<div>
    <label for="iconrightInput" class="form-label">Input with Icon Right</label>
    <div class="form-icon right">
        <input type="email" class="form-control form-control-icon" 
               id="iconrightInput" placeholder="example@gmail.com">
        <i class="ri-mail-unread-line"></i>
    </div>
</div>
```

### Specialized Input Types

#### Date Input

```blade
<div>
    <label for="exampleInputdate" class="form-label">Input Date</label>
    <input type="date" class="form-control" id="exampleInputdate">
</div>
```

#### Time Input

```blade
<div>
    <label for="exampleInputtime" class="form-label">Input Time</label>
    <input type="time" class="form-control" id="exampleInputtime">
</div>
```

#### Password Input

```blade
<div>
    <label for="exampleInputpassword" class="form-label">Input Password</label>
    <input type="password" class="form-control" id="exampleInputpassword" value="44512465">
</div>
```

#### Color Picker

```blade
<div>
    <label for="colorPicker" class="form-label">Color Picker</label>
    <input type="color" class="form-control form-control-color w-100" 
           id="colorPicker" value="#364574">
</div>
```

### Border Styles

#### Dashed Border

```blade
<div>
    <label for="borderInput" class="form-label">Input Border Style</label>
    <input type="text" class="form-control border-dashed" 
           id="borderInput" placeholder="Enter your name">
</div>
```

#### Rounded Input

```blade
<div>
    <label for="exampleInputrounded" class="form-label">Rounded Input</label>
    <input type="text" class="form-control rounded-pill" 
           id="exampleInputrounded" placeholder="Enter your name">
</div>
```

### Datalist

```blade
<label for="exampleDataList" class="form-label">Datalist example</label>
<input class="form-control" list="datalistOptions" 
       id="exampleDataList" placeholder="Search your country...">
<datalist id="datalistOptions">
    <option value="Switzerland">
    <option value="New York">
    <option value="France">
    <option value="Spain">
    <option value="Chicago">
    <option value="Bulgaria">
    <option value="Hong Kong">
</datalist>
```

### Floating Labels

```blade
<div class="form-floating">
    <input type="text" class="form-control" id="firstnamefloatingInput" 
           placeholder="Enter your firstname">
    <label for="firstnamefloatingInput">Floating Input</label>
</div>
```

### Form Text (Helper Text)

```blade
<div>
    <label for="formtextInput" class="form-label">Form Text</label>
    <input type="password" class="form-control" id="formtextInput">
    <div id="passwordHelpBlock" class="form-text">
        Must be 8-20 characters long.
    </div>
</div>
```

---

## Input Sizing

Velzon provides three sizes for input elements:

### Small Input

```blade
<input class="form-control form-control-sm" type="text" placeholder=".form-control-sm">
```

### Default Input

```blade
<input class="form-control" type="text" placeholder=".form-control">
```

### Large Input

```blade
<input class="form-control form-control-lg" type="text" placeholder=".form-control-lg">
```

---

## Input Groups

Input groups allow you to add text, buttons, or button groups before, after, or on both sides of form inputs.

### Basic Input Group

#### Prepend Text

```blade
<div class="input-group">
    <span class="input-group-text" id="basic-addon1">@</span>
    <input type="text" class="form-control" placeholder="Username" 
           aria-label="Username" aria-describedby="basic-addon1">
</div>
```

#### Append Text

```blade
<div class="input-group">
    <input type="text" class="form-control" placeholder="Recipient's username" 
           aria-label="Recipient's username" aria-describedby="basic-addon2">
    <span class="input-group-text" id="basic-addon2">@example.com</span>
</div>
```

#### Both Sides

```blade
<div class="input-group">
    <span class="input-group-text">$</span>
    <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
    <span class="input-group-text">.00</span>
</div>
```

### Multiple Inputs

```blade
<div class="input-group">
    <input type="text" class="form-control" placeholder="Username" aria-label="Username">
    <span class="input-group-text">@</span>
    <input type="text" class="form-control" placeholder="Server" aria-label="Server">
</div>
```

### Input Group with Textarea

```blade
<div class="input-group">
    <span class="input-group-text">With textarea</span>
    <textarea class="form-control" aria-label="With textarea" rows="2"></textarea>
</div>
```

### Prevent Wrapping

```blade
<div class="input-group flex-nowrap">
    <span class="input-group-text" id="addon-wrapping">@</span>
    <input type="text" class="form-control" placeholder="Username" 
           aria-label="Username" aria-describedby="addon-wrapping">
</div>
```

---

## Textarea

Standard textarea element:

```blade
<div>
    <label for="exampleFormControlTextarea5" class="form-label">Example Textarea</label>
    <textarea class="form-control" id="exampleFormControlTextarea5" rows="3"></textarea>
</div>
```

---

## Select Elements

### Default Select

```blade
<select class="form-select mb-3" aria-label="Default select example">
    <option selected>Select your Status</option>
    <option value="1">Declined Payment</option>
    <option value="2">Delivery Error</option>
    <option value="3">Wrong Amount</option>
</select>
```

### Rounded Select

```blade
<select class="form-select rounded-pill mb-3" aria-label="Default select example">
    <option selected>Search for services</option>
    <option value="1">Information Architecture</option>
    <option value="2">UI/UX Design</option>
    <option value="3">Back End Development</option>
</select>
```

### Disabled Select

```blade
<select class="form-select" aria-label="Disabled select example" disabled>
    <option selected>Open this select menu (Disabled)</option>
    <option value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
</select>
```

### Multiple Select

```blade
<select class="form-select" multiple aria-label="multiple select example">
    <option selected>Open this select menu (multiple select option)</option>
    <option value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
</select>
```

### Select with Size

```blade
<select class="form-select" size="3" aria-label="size 3 select example">
    <option selected>Open this select menu (select menu size)</option>
    <option value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
    <option value="4">Four</option>
    <option value="5">Five</option>
</select>
```

### Select Sizing

#### Small Select

```blade
<select class="form-select form-select-sm" aria-label=".form-select-sm example">
    <option selected>Open this select menu</option>
    <option value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
</select>
```

#### Default Select

```blade
<select class="form-select" aria-label="Default select">
    <option selected>Open this select menu</option>
    <option value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
</select>
```

#### Large Select

```blade
<select class="form-select form-select-lg" aria-label=".form-select-lg example">
    <option selected>Open this select menu</option>
    <option value="1">One</option>
    <option value="2">Two</option>
    <option value="3">Three</option>
</select>
```

---

## File Inputs

### Default File Input

```blade
<div>
    <label for="formFile" class="form-label">Default File Input Example</label>
    <input class="form-control" type="file" id="formFile">
</div>
```

### Multiple Files

```blade
<div>
    <label for="formFileMultiple" class="form-label">Multiple Files Input Example</label>
    <input class="form-control" type="file" id="formFileMultiple" multiple>
</div>
```

### Disabled File Input

```blade
<div>
    <label for="formFileDisabled" class="form-label">Disabled File Input Example</label>
    <input class="form-control" type="file" id="formFileDisabled" disabled>
</div>
```

### File Input Sizing

#### Small

```blade
<input class="form-control form-control-sm" id="formSizeSmall" type="file">
```

#### Default

```blade
<input class="form-control" id="formSizeDefault" type="file">
```

#### Large

```blade
<input class="form-control form-control-lg" id="formSizeLarge" type="file">
```

---

## Checkboxes & Radios

Checkboxes and radios are implemented using Bootstrap's `.form-check` classes.

### Checkboxes

#### Default Checkbox

```blade
<div class="form-check">
    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
    <label class="form-check-label" for="defaultCheck1">
        Default checkbox
    </label>
</div>
```

#### Checked Checkbox

```blade
<div class="form-check">
    <input class="form-check-input" type="checkbox" value="" id="defaultCheck2" checked>
    <label class="form-check-label" for="defaultCheck2">
        Checked checkbox
    </label>
</div>
```

#### Disabled Checkbox

```blade
<div class="form-check">
    <input class="form-check-input" type="checkbox" value="" id="defaultCheck3" disabled>
    <label class="form-check-label" for="defaultCheck3">
        Disabled checkbox
    </label>
</div>
```

### Radio Buttons

#### Default Radio

```blade
<div class="form-check">
    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
    <label class="form-check-label" for="flexRadioDefault1">
        Default radio
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
    <label class="form-check-label" for="flexRadioDefault2">
        Default checked radio
    </label>
</div>
```

#### Disabled Radio

```blade
<div class="form-check">
    <input class="form-check-input" type="radio" name="flexRadioDisabled" id="flexRadioDisabled" disabled>
    <label class="form-check-label" for="flexRadioDisabled">
        Disabled radio
    </label>
</div>
```

---

## Switches

Switches are a variant of checkboxes styled as toggles.

### Default Switch

```blade
<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
    <label class="form-check-label" for="flexSwitchCheckDefault">Default switch checkbox input</label>
</div>
```

### Checked Switch

```blade
<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
    <label class="form-check-label" for="flexSwitchCheckChecked">Checked switch checkbox input</label>
</div>
```

### Disabled Switch

```blade
<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDisabled" disabled>
    <label class="form-check-label" for="flexSwitchCheckDisabled">Disabled switch checkbox input</label>
</div>
```

---

## Form Validation

Velzon supports both browser default and custom Bootstrap validation styles.

### Browser Default Validation

```blade
<form class="row g-3">
    <div class="col-md-4">
        <label for="validationDefault01" class="form-label">First name</label>
        <input type="text" class="form-control" id="validationDefault01" value="Mark" required>
    </div>
    <div class="col-md-4">
        <label for="validationDefault02" class="form-label">Last name</label>
        <input type="text" class="form-control" id="validationDefault02" value="Otto" required>
    </div>
    <div class="col-md-4">
        <label for="validationDefaultUsername" class="form-label">Username</label>
        <div class="input-group">
            <span class="input-group-text" id="inputGroupPrepend2">@</span>
            <input type="text" class="form-control" id="validationDefaultUsername" 
                   aria-describedby="inputGroupPrepend2" required>
        </div>
    </div>
    <div class="col-12">
        <button class="btn btn-primary" type="submit">Submit form</button>
    </div>
</form>
```

### Custom Validation Styles

Add the `.needs-validation` class and `novalidate` attribute:

```blade
<form class="row g-3 needs-validation" novalidate>
    <div class="col-md-4">
        <label for="validationCustom01" class="form-label">First name</label>
        <input type="text" class="form-control" id="validationCustom01" value="Mark" required>
        <div class="valid-feedback">
            Looks good!
        </div>
    </div>
    <div class="col-md-4">
        <label for="validationCustom02" class="form-label">Last name</label>
        <input type="text" class="form-control" id="validationCustom02" value="Otto" required>
        <div class="valid-feedback">
            Looks good!
        </div>
    </div>
    <div class="col-md-4">
        <label for="validationCustomUsername" class="form-label">Username</label>
        <div class="input-group has-validation">
            <span class="input-group-text" id="inputGroupPrepend">@</span>
            <input type="text" class="form-control" id="validationCustomUsername" 
                   aria-describedby="inputGroupPrepend" required>
            <div class="invalid-feedback">
                Please choose a username.
            </div>
        </div>
    </div>
    <div class="col-12">
        <button class="btn btn-primary" type="submit">Submit form</button>
    </div>
</form>
```

### Validation Feedback

#### Valid Feedback

```blade
<div class="valid-feedback">
    Looks good!
</div>
```

#### Invalid Feedback

```blade
<div class="invalid-feedback">
    Please provide a valid city.
</div>
```

### Validation Tooltips

Use `.valid-tooltip` and `.invalid-tooltip` classes for tooltip-style feedback:

```blade
<div class="col-md-4 position-relative">
    <label for="validationTooltip01" class="form-label">First name</label>
    <input type="text" class="form-control" id="validationTooltip01" value="Mark" required>
    <div class="valid-tooltip">
        Looks good!
    </div>
</div>
```

### Supported Elements Validation

#### Textarea Validation

```blade
<div class="mb-3">
    <label for="validationTextarea" class="form-label">Textarea</label>
    <textarea class="form-control" id="validationTextarea" 
              placeholder="Required example textarea" required></textarea>
    <div class="invalid-feedback">
        Please enter a message in the textarea.
    </div>
</div>
```

#### Checkbox Validation

```blade
<div class="form-check mb-3">
    <input type="checkbox" class="form-check-input" id="validationFormCheck1" required>
    <label class="form-check-label" for="validationFormCheck1">Check this checkbox</label>
    <div class="invalid-feedback">Example invalid feedback text</div>
</div>
```

#### Radio Validation

```blade
<div class="form-check">
    <input type="radio" class="form-check-input" id="validationFormCheck2" 
           name="radio-stacked" required>
    <label class="form-check-label" for="validationFormCheck2">Toggle this radio</label>
</div>
<div class="form-check mb-3">
    <input type="radio" class="form-check-input" id="validationFormCheck3" 
           name="radio-stacked" required>
    <label class="form-check-label" for="validationFormCheck3">Or toggle this other radio</label>
    <div class="invalid-feedback">More example invalid feedback text</div>
</div>
```

#### Select Validation

```blade
<div class="mb-3">
    <select class="form-select" required aria-label="select example">
        <option value="">Open this select menu</option>
        <option value="1">One</option>
        <option value="2">Two</option>
        <option value="3">Three</option>
    </select>
    <div class="invalid-feedback">Example invalid select feedback</div>
</div>
```

#### File Input Validation

```blade
<div class="mb-3">
    <input type="file" class="form-control" aria-label="file example" required>
    <div class="invalid-feedback">Example invalid form file feedback</div>
</div>
```

---

## Advanced Selects (Choices.js)

Velzon integrates Choices.js for enhanced select elements with search, multi-select, and more.

### Single Select with Choices

```blade
<select class="form-control" data-choices name="choices-single-default" id="choices-single-default">
    <option value="">This is a placeholder</option>
    <option value="Choice 1">Choice 1</option>
    <option value="Choice 2">Choice 2</option>
    <option value="Choice 3">Choice 3</option>
</select>
```

### Select with Option Groups

```blade
<select class="form-control" id="choices-single-groups" data-choices 
        data-choices-groups data-placeholder="Select City" name="choices-single-groups">
    <option value="">Choose a city</option>
    <optgroup label="UK">
        <option value="London">London</option>
        <option value="Manchester">Manchester</option>
        <option value="Liverpool">Liverpool</option>
    </optgroup>
    <optgroup label="FR">
        <option value="Paris">Paris</option>
        <option value="Lyon">Lyon</option>
        <option value="Marseille">Marseille</option>
    </optgroup>
    <optgroup label="US">
        <option value="New York">New York</option>
        <option value="Washington" disabled>Washington</option>
        <option value="Michigan">Michigan</option>
    </optgroup>
</select>
```

### Select Without Search

```blade
<select class="form-control" id="choices-single-no-search" 
        name="choices-single-no-search" data-choices 
        data-choices-search-false data-choices-removeItem>
    <option value="Zero">Zero</option>
    <option value="One">One</option>
    <option value="Two">Two</option>
    <option value="Three">Three</option>
</select>
```

### Select Without Sorting

```blade
<select class="form-control" id="choices-single-no-sorting" 
        name="choices-single-no-sorting" data-choices data-choices-sorting-false>
    <option value="Madrid">Madrid</option>
    <option value="Toronto">Toronto</option>
    <option value="Vancouver">Vancouver</option>
    <option value="London">London</option>
</select>
```

### Multiple Select

```blade
<select class="form-control" id="choices-multiple-default" 
        data-choices name="choices-multiple-default" multiple>
    <option value="Choice 1" selected>Choice 1</option>
    <option value="Choice 2">Choice 2</option>
    <option value="Choice 3">Choice 3</option>
    <option value="Choice 4" disabled>Choice 4</option>
</select>
```

### Multiple Select with Remove Button

```blade
<select class="form-control" id="choices-multiple-remove-button" 
        data-choices data-choices-removeItem name="choices-multiple-remove-button" multiple>
    <option value="Choice 1" selected>Choice 1</option>
    <option value="Choice 2">Choice 2</option>
    <option value="Choice 3">Choice 3</option>
    <option value="Choice 4">Choice 4</option>
</select>
```

### Multiple Select with Option Groups

```blade
<select class="form-control" id="choices-multiple-groups" 
        name="choices-multiple-groups" data-choices 
        data-choices-multiple-groups="true" multiple>
    <option value="">Choose a city</option>
    <optgroup label="UK">
        <option value="London">London</option>
        <option value="Manchester">Manchester</option>
        <option value="Liverpool">Liverpool</option>
    </optgroup>
    <optgroup label="FR">
        <option value="Paris">Paris</option>
        <option value="Lyon">Lyon</option>
        <option value="Marseille">Marseille</option>
    </optgroup>
</select>
```

### Text Input with Choices

#### With Limit and Remove Button

```blade
<input class="form-control" id="choices-text-remove-button" 
       data-choices data-choices-limit="3" data-choices-removeItem 
       type="text" value="Task-1" />
```

#### Unique Values Only

```blade
<input class="form-control" id="choices-text-unique-values" 
       data-choices data-choices-text-unique-true 
       type="text" value="Project-A, Project-B" />
```

#### Disabled Text Input

```blade
<input class="form-control" id="choices-text-disabled" 
       data-choices data-choices-text-disabled-true 
       type="text" value="josh@joshuajohnson.co.uk, joe@bloggs.co.uk" />
```

---

## Best Practices

### Accessibility

1. **Always use labels** - Every form control should have an associated `<label>` element
2. **Use appropriate `aria-*` attributes** - Enhance accessibility with ARIA attributes
3. **Provide clear error messages** - Use `.invalid-feedback` to guide users
4. **Ensure keyboard navigation** - All form controls should be keyboard accessible

### Form Organization

1. **Group related fields** - Use logical grouping for better UX
2. **Use appropriate input types** - Leverage HTML5 input types (`email`, `tel`, `date`, etc.)
3. **Implement progressive disclosure** - Show fields conditionally when needed
4. **Provide helper text** - Use `.form-text` for additional guidance

###Validation

1. **Client-side validation** - Provide immediate feedback
2. **Server-side validation** - Always validate on the server
3. **Clear error messages** - Be specific about what went wrong
4. **Validate on blur** - Check fields as users complete them

### Styling

1. **Consistent sizing** - Use `.form-control-sm` or `.form-control-lg` consistently
2. **Visual hierarchy** - Use spacing and grouping to guide users
3. **Avoid over-styling** - Maintain Bootstrap's clean aesthetic
4. **Responsive design** - Test forms on all device sizes

### Performance

1. **Lazy load advanced components** - Load Choices.js only when needed
2. **Minimize DOM manipulation** - Batch updates when possible
3. **Optimize validation** - Debounce validation checks
4. **Use native controls when possible** - Better performance and accessibility

---

## Form Control Classes Reference

| Class | Purpose |
|-------|---------|
| `.form-control` | Standard input styling |
| `.form-control-sm` | Small input size |
| `.form-control-lg` | Large input size |
| `.form-control-plaintext` | Readonly plain text |
| `.form-control-color` | Color picker styling |
| `.form-control-icon` | Input with icon |
| `.form-select` | Select element |
| `.form-select-sm` | Small select |
| `.form-select-lg` | Large select |
| `.form-check` | Checkbox/radio wrapper |
| `.form-check-input` | Checkbox/radio input |
| `.form-check-label` | Checkbox/radio label |
| `.form-switch` | Switch toggle |
| `.form-floating` | Floating label |
| `.form-icon` | Icon container |
| `.form-icon.right` | Right-aligned icon |
| `.form-text` | Helper text |
| `.input-group` | Input group container |
| `.input-group-text` | Input group addon |
| `.needs-validation` | Custom validation |
| `.was-validated` | Applied after validation |
| `.valid-feedback` | Valid state message |
| `.invalid-feedback` | Invalid state message |
| `.valid-tooltip` | Valid tooltip |
| `.invalid-tooltip` | Invalid tooltip |

---

## Related Documentation

- [Layout System](01-layout-system.md) - Master templates and page structure
- [UI Components](02-ui-components.md) - Buttons, alerts, badges, cards
- [Overview](00-overview.md) - Color system, typography, spacing
- [Validation Patterns](03-form-components.md#form-validation) - Form validation techniques

---

## Summary

Velzon's form components provide:

- **Comprehensive input types** - Text, email, password, date, time, color, file, and more
- **Flexible sizing** - Small, default, and large variants
- **Input groups** - Combine inputs with text, buttons, or other elements
- **Advanced selects** - Choices.js integration for enhanced functionality
- **Robust validation** - Both browser default and custom Bootstrap validation
- **Accessibility first** - Proper labels, ARIA attributes, and keyboard support
- **Consistent styling** - Matches Velzon's design system

By leveraging these form components, you can create user-friendly, accessible, and visually consistent forms throughout your application.
