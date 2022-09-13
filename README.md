[![PHPStan](https://github.com/wimski/html-data-extractor/actions/workflows/phpstan.yml/badge.svg)](https://github.com/wimski/html-data-extractor/actions/workflows/phpstan.yml)
[![PHPUnit](https://github.com/wimski/html-data-extractor/actions/workflows/phpunit.yml/badge.svg)](https://github.com/wimski/html-data-extractor/actions/workflows/phpunit.yml)
[![Coverage Status](https://coveralls.io/repos/github/wimski/html-data-extractor/badge.svg?branch=master)](https://coveralls.io/github/wimski/html-data-extractor?branch=master)
[![Latest Stable Version](http://poser.pugx.org/wimski/html-data-extractor/v)](https://packagist.org/packages/wimski/html-data-extractor)

# HTML Data Extractor

Extract data from an HTML string by using placeholders in a reverse template.

* [Changelog](#changelog)
* [Setup](#setup)
  * [Install](#install)
  * [Bindings](#bindings)
    * [Service Container](#service-container)
    * [Manually](#manually)
* [Usage](#usage)

## Changelog

[View the changelog.](./CHANGELOG.md)

## Setup

### Install

```bash
composer require wimski/html-data-extractor
```

### Bindings

#### Service Container

If you're using a service container, you can create (singleton) bindings for the following interfaces:

| Interface                                                                         | Concrete                                                       |
|-----------------------------------------------------------------------------------|----------------------------------------------------------------|
| `Wimski\HtmlDataExtractor\Contracts\Extractors\GroupNameExtractorInterface`       | `Wimski\HtmlDataExtractor\Extractors\GroupNameExtractor`       |
| `Wimski\HtmlDataExtractor\Contracts\Extractors\HtmlDataExtractorInterface`        | `Wimski\HtmlDataExtractor\Extractors\HtmlDataExtractor`        |
| `Wimski\HtmlDataExtractor\Contracts\Extractors\PlaceholderExtractorInterface`     | `Wimski\HtmlDataExtractor\Extractors\PlaceholderExtractor`     |
| `Wimski\HtmlDataExtractor\Contracts\Extractors\PlaceholderNameExtractorInterface` | `Wimski\HtmlDataExtractor\Extractors\PlaceholderNameExtractor` |
| `Wimski\HtmlDataExtractor\Contracts\Factories\ExtractableNodeFactoryInterface`    | `Wimski\HtmlDataExtractor\Factories\ExtractableNodeFactory`    |
| `Wimski\HtmlDataExtractor\Contracts\Factories\GroupNameFactoryInterface`          | `Wimski\HtmlDataExtractor\Factories\GroupNameFactory`          |
| `Wimski\HtmlDataExtractor\Contracts\Factories\SelectorFactoryInterface`           | `Wimski\HtmlDataExtractor\Factories\SelectorFactory`           |
| `Wimski\HtmlDataExtractor\Contracts\Processors\ExtractableNodeProcessorInterface` | `Wimski\HtmlDataExtractor\Processors\ExtractableNodeProcessor` |

#### Manually

Or you can set up everything manually like this:

```php
use Wimski\HtmlDataExtractor\Extractors\GroupNameExtractor;
use Wimski\HtmlDataExtractor\Extractors\HtmlDataExtractor;
use Wimski\HtmlDataExtractor\Template\TemplateDataExtractor;
use Wimski\HtmlDataExtractor\Extractors\PlaceholderNameExtractor;
use Wimski\HtmlDataExtractor\Template\TemplateParser;
use Wimski\HtmlDataExtractor\Factories\GroupNameFactory;
use Wimski\HtmlDataExtractor\Factories\SelectorFactory;
use Wimski\HtmlDataExtractor\Source\SourceParser;

$placeholderNameExtractor = new PlaceholderNameExtractor();
$placeholderExtractor     = new TemplateDataExtractor($placeholderNameExtractor);
$groupNameExtractor       = new GroupNameExtractor();
$groupNameFactory         = new GroupNameFactory($groupNameExtractor);
$selectorFactory          = new SelectorFactory($placeholderNameExtractor);
$extractableNodeFactory   = new TemplateParser($selectorFactory, $groupNameFactory, $placeholderExtractor);
$extractableNodeProcessor = new SourceParser();

$htmlDataExtractor = new HtmlDataExtractor(
    $extractableNodeFactory,
    $extractableNodeProcessor,
);
```

## Usage

Comprehensive documentation has to be written. See [`HtmlDataExtractorTest`](./tests/Extractors/HtmlDataExtractorTest.php) for an example in the meantime.