# MultiSelect

## Install

`composer require ziffdavis/nova-multiselect`

## Description

Allows having a BelongsToMany relationship MultiSelect Form Field powered by Selectize.  This field is hidden on the index page.

## Usage
```
public function fields(Request $request)
{
    return [
        MultiSelect::make('Category', 'categories')->options(\App\Models\Category::get(['id', 'name']))->placeHolder('Select Categories'),
    ];
}
```

### Methods
| Method                | Description                                    |
|-----------------------|------------------------------------------------|
| `options($options)`   | Sets the options to use.  May be a collection or array of Objects |
| `placeHolder($text)`  | Sets the text to use as the palce holder  |
| `disabled($bool)`     | Sets whether or not the field is disabled | 
| `optionLabel($label)` | Sets the attribute name to use for the option label (Default 'name')  | 
| `optionValue($value)` | Sets the attribute name to use for the option value (Default 'id')  | 
 