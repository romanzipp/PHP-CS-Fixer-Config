# PHP-CS-Fixer Configuration

Personal PHP-CS-Fixer wrapper & preset management.

## Installation

```
composer require romanzipp/laravel-model-doc
```

## Usage

#### `.php_cs.dist`

```php
return romanzipp\Fixer\Config::make()
    ->in(__DiR__)
    ->preset(
        new romanzipp\Fixer\Presets\PrettyLaravel()
    )
    ->out();
```
