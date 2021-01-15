# PHP-CS-Fixer Configuration

[![Latest Stable Version](https://img.shields.io/packagist/v/romanzipp/PHP-CS-Fixer-Config.svg?style=flat-square)](https://packagist.org/packages/romanzipp/php-cs-fixer-config)
[![Total Downloads](https://img.shields.io/packagist/dt/romanzipp/PHP-CS-Fixer-Config.svg?style=flat-square)](https://packagist.org/packages/romanzipp/php-cs-fixer-config)
[![License](https://img.shields.io/packagist/l/romanzipp/PHP-CS-Fixer-Config.svg?style=flat-square)](https://packagist.org/packages/romanzipp/php-cs-fixer-config)
[![GitHub Build Status](https://img.shields.io/github/workflow/status/romanzipp/PHP-CS-Fixer-Config/Tests?style=flat-square)](https://github.com/romanzipp/PHP-CS-Fixer-Config/actions)

Personal PHP-CS-Fixer wrapper & preset management.

## Installation

```
composer require romanzipp/php-cs-fixer-config
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
