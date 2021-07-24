# Laravel blade components for HTML form elements

## Installation

```bash
composer require anourvalar/laravel-form
```


## Features

- Replacement to the old values on validation failure;

- Error class addition for invalid elements.


## Usage

### Input

```php
<x-form-input type="text" name="foo" value="some text" />
```

```php
<x-form-input type="checkbox" name="foo" value="1" :checked="$isChecked" />
```

```php
<x-form-input type="radio" name="foo" value="1" checked-value="2" />
<x-form-input type="radio" name="foo" value="2" checked-value="2" /> <!-- will be checked -->
<x-form-input type="radio" name="foo" value="3" checked-value="2" />
```


### Select

```php
<x-form-select name="foo" :options="[1 => ['title' => 'One'], 2 => ['title' => 'Two']]" selected="2" />
```


### Textarea

```php
<x-form-textarea name="foo">Text</x-form-textarea>
```
