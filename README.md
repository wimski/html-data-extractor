[![PHPStan](https://github.com/wimski/html-data-extractor/actions/workflows/phpstan.yml/badge.svg)](https://github.com/wimski/html-data-extractor/actions/workflows/phpstan.yml)
[![PHPUnit](https://github.com/wimski/html-data-extractor/actions/workflows/phpunit.yml/badge.svg)](https://github.com/wimski/html-data-extractor/actions/workflows/phpunit.yml)

# HTML Data Extractor

Extract data from an HTML string by using placeholders in a reverse template.

* [Setup](#setup)
  * [Install](#install)
  * [Bindings](#bindings)
    * [Service Container](#service-container)
    * [Manually](#manually)
* [Usage](#usage)

## Setup

### Install

```bash
composer require wimski/html-data-extractor
```

### Bindings

#### Service Container

If you're using a service container, you can create (singleton) bindings for the following interfaces:

| Interface                                                          | Concrete                                        |
|--------------------------------------------------------------------|-------------------------------------------------|
| `Wimski\HtmlDataExtractor\Contracts\ExtractableFinderInterface`    | `Wimski\HtmlDataExtractor\ExtractableFinder`    |
| `Wimski\HtmlDataExtractor\Contracts\ExtractableProcessorInterface` | `Wimski\HtmlDataExtractor\ExtractableProcessor` |
| `Wimski\HtmlDataExtractor\Contracts\HtmlDataExtractorInterface`    | `Wimski\HtmlDataExtractor\HtmlDataExtractor`    |
| `Wimski\HtmlDataExtractor\Contracts\PlaceholderExtractorInterface` | `Wimski\HtmlDataExtractor\PlaceholderExtractor` |
| `Wimski\HtmlDataExtractor\Contracts\SelectorFactoryInterface`      | `Wimski\HtmlDataExtractor\SelectorFactory`      |

#### Manually

Or you can set up everything manually like this:

```php
use Wimski\HtmlDataExtractor\ExtractableFinder;
use Wimski\HtmlDataExtractor\ExtractableProcessor;
use Wimski\HtmlDataExtractor\HtmlDataExtractor;
use Wimski\HtmlDataExtractor\PlaceholderExtractor;
use Wimski\HtmlDataExtractor\SelectorFactory;

$placeholderExtractor = new PlaceholderExtractor();
$selectorFactory      = new SelectorFactory($placeholderExtractor);
$extractableFinder    = new ExtractableFinder($placeholderExtractor, $selectorFactory);
$extractableProcessor = new ExtractableProcessor();

$htmlDataExtractor = new HtmlDataExtractor(
    $extractableFinder,
    $extractableProcessor,
);
```

## Usage

Comprehensive documentation has to be written. See [`HtmlDataExtractorTest`](./tests/HtmlDataExtractorTest.php) for an example in the meantime.