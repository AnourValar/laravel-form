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
<x-input type="text" name="foo" value="some text" />
```

```php
<x-input type="checkbox" name="foo" value="1" :checked="$isChecked" />
```

```php
<x-input type="radio" name="foo" value="1" checked-value="2" />
<x-input type="radio" name="foo" value="2" checked-value="2" /> <!-- will be checked -->
<x-input type="radio" name="foo" value="3" checked-value="2" />
```


### Select

```php
<x-select name="foo" :options="[1 => ['title' => 'One'], 2 => ['title' => 'Two']]" selected="2" />
```


### Textarea

```php
<x-textarea name="foo">Text</x-textarea>
```
