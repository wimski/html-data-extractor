[![PHPStan](https://github.com/wimski/html-data-extractor/actions/workflows/phpstan.yml/badge.svg)](https://github.com/wimski/html-data-extractor/actions/workflows/phpstan.yml)
[![PHPUnit](https://github.com/wimski/html-data-extractor/actions/workflows/phpunit.yml/badge.svg)](https://github.com/wimski/html-data-extractor/actions/workflows/phpunit.yml)
[![Coverage Status](https://coveralls.io/repos/github/wimski/html-data-extractor/badge.svg?branch=master)](https://coveralls.io/github/wimski/html-data-extractor?branch=master)
[![Latest Stable Version](http://poser.pugx.org/wimski/html-data-extractor/v)](https://packagist.org/packages/wimski/html-data-extractor)

# HTML Data Extractor

This package lets you easily extract data from an HTML string by using a reverse template in [Twig](https://twig.symfony.com) style.

* [Changelog](#changelog)
* [Setup](#setup)
  * [Install](#install)
  * [Bindings](#bindings)
* [Usage](#usage)

## Changelog

[View the changelog.](./CHANGELOG.md)

## Setup

### Install

```bash
composer require wimski/html-data-extractor
```

### Bindings

```php
use Wimski\HtmlDataExtractor\Extractors\HtmlDataExtractor;
use Wimski\HtmlDataExtractor\Factories\SelectorFactory;
use Wimski\HtmlDataExtractor\HtmlLoader;
use Wimski\HtmlDataExtractor\Source\SourceParser;
use Wimski\HtmlDataExtractor\Matching\GroupMatcher;
use Wimski\HtmlDataExtractor\Matching\PlaceholderMatcher;
use Wimski\HtmlDataExtractor\Template\TemplateDataExtractor;
use Wimski\HtmlDataExtractor\Template\TemplateGroupsValidator;
use Wimski\HtmlDataExtractor\Template\TemplateParser;
use Wimski\HtmlDataExtractor\Template\TemplateRootNodeExtractor;
use Wimski\HtmlDataExtractor\Template\TemplateValidator;

$htmlLoader                = new HtmlLoader();
$placeholderMatcher        = new PlaceholderMatcher();
$groupMatcher              = new GroupMatcher();
$templateGroupsValidator   = new TemplateGroupsValidator($htmlLoader, $groupMatcher);
$templateValidator         = new TemplateValidator($templateGroupsValidator);
$selectorFactory           = new SelectorFactory($placeholderMatcher);
$templateDataExtractor     = new TemplateDataExtractor($placeholderMatcher);
$templateRootNodeExtractor = new TemplateRootNodeExtractor($htmlLoader);

$templateParser = new TemplateParser(
    $templateValidator,
    $groupMatcher,
    $selectorFactory,
    $templateRootNodeExtractor,
    $templateDataExtractor,
);

$sourceParser = new SourceParser();

$htmlDataExtractor = new HtmlDataExtractor(
    $templateParser,
    $sourceParser,
);
```

## Usage

Comprehensive documentation has to be written. See [`HtmlDataExtractorTest`](./tests/Extractors/HtmlDataExtractorTest.php) for an example in the meantime.