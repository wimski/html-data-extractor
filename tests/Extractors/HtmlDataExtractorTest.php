<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Extractors;

use PHPUnit\Framework\TestCase;
use Throwable;
use Wimski\HtmlDataExtractor\Contracts\Source\Data\SourceRowInterface;
use Wimski\HtmlDataExtractor\Exceptions\HtmlDataExtractionException;
use Wimski\HtmlDataExtractor\Exceptions\TemplateNodeChildAlreadyExistsException;
use Wimski\HtmlDataExtractor\Exceptions\TemplateNodeDataAlreadyExistsException;
use Wimski\HtmlDataExtractor\Exceptions\TemplateParsingException;
use Wimski\HtmlDataExtractor\Exceptions\TemplateValidationException;
use Wimski\HtmlDataExtractor\Extractors\HtmlDataExtractor;
use Wimski\HtmlDataExtractor\Factories\SelectorFactory;
use Wimski\HtmlDataExtractor\HtmlLoader;
use Wimski\HtmlDataExtractor\Matching\GroupMatcher;
use Wimski\HtmlDataExtractor\Matching\PlaceholderMatcher;
use Wimski\HtmlDataExtractor\Source\SourceParser;
use Wimski\HtmlDataExtractor\Template\TemplateDataExtractor;
use Wimski\HtmlDataExtractor\Template\TemplateGroupsValidator;
use Wimski\HtmlDataExtractor\Template\TemplateParser;
use Wimski\HtmlDataExtractor\Template\TemplateRootNodeExtractor;
use Wimski\HtmlDataExtractor\Template\TemplateValidator;

class HtmlDataExtractorTest extends TestCase
{
    protected HtmlDataExtractor $htmlDataExtractor;

    protected function setUp(): void
    {
        parent::setUp();

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

        $this->htmlDataExtractor = new HtmlDataExtractor(
            $templateParser,
            $sourceParser,
        );
    }

    /**
     * @test
     */
    public function it_extracts_a_single_text_value(): void
    {
        $this->extractAndMatch('text-single', [
            [
                'text' => ['Lipsum'],
            ],
        ]);
    }

    /**
     * @test
     * @depends it_extracts_a_single_text_value
     */
    public function it_extracts_an_empty_text_value(): void
    {
        $this->extractAndMatch('text-empty', [
            [
                'text' => [''],
            ],
        ]);
    }

    /**
     * @test
     * @depends it_extracts_a_single_text_value
     */
    public function it_extracts_multiple_text_values(): void
    {
        $this->extractAndMatch('text-multiple', [
            [
                'text' => ['Lorem'],
            ],
            [
                'text' => ['Ipsum'],
            ],
        ]);
    }

    /**
     * @test
     */
    public function it_extracts_a_single_attribute_value(): void
    {
        $this->extractAndMatch('attribute-single', [
            [
                'attribute' => ['Lipsum'],
            ],
        ]);
    }

    /**
     * @test
     * @depends it_extracts_a_single_attribute_value
     */
    public function it_extracts_an_empty_attribute_value(): void
    {
        $this->extractAndMatch('attribute-empty', [
            [
                'attribute' => [''],
            ],
        ]);
    }

    /**
     * @test
     * @depends it_extracts_a_single_attribute_value
     */
    public function it_extracts_multiple_attribute_values(): void
    {
        $this->extractAndMatch('attribute-multiple', [
            [
                'attribute' => ['Lorem'],
            ],
            [
                'attribute' => ['Ipsum'],
            ],
        ]);
    }

    /**
     * @test
     */
    public function it_extracts_with_a_specific_selector(): void
    {
        $this->extractAndMatch('selector-specific', [
            [
                'text'      => ['Ipsum'],
                'attribute' => ['Lorem'],
            ],
        ]);
    }

    /**
     * @test
     */
    public function it_extracts_on_varying_levels(): void
    {
        $this->extractAndMatch('varying-levels', [
            [
                'text' => ['Lorem'],
            ],
            [
                'text' => ['Ipsum'],
            ],
            [
                'text' => ['Dolor'],
            ],
            [
                'text' => ['Amet'],
            ],
        ]);
    }

    /**
     * @test
     */
    public function it_extracts_groups(): void
    {
        $this->extractAndMatch('groups', [
            [
                'name'        => ['Lorem'],
                'description' => ['Lorem ipsum dolor sit amet'],
                'image'       => ['http://image.jpg'],
                'authors'     => [
                    [
                        'first_name' => ['John'],
                        'last_name'  => ['Doe'],
                    ],
                    [
                        'first_name' => ['Jane'],
                        'last_name'  => ['Smith'],
                    ],
                ],
                'links'       => [
                    [
                        'name' => ['Website 1'],
                        'url'  => ['http://site1.com'],
                    ],
                    [
                        'name' => ['Website 2'],
                        'url'  => ['http://site2.com'],
                    ],
                ],
            ],
            [
                'name'        => ['Ipsum'],
                'description' => [''],
                'image'       => ['http://picture.png'],
                'authors'     => [
                    [
                        'first_name' => ['Bob'],
                        'last_name'  => ['Bobson'],
                    ],
                ],
                'links'       => [
                    [
                        'name' => ['Website 3'],
                        'url'  => ['http://site3.com'],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function it_extracts_nested_groups(): void
    {
        $this->extractAndMatch('groups-nested', [
            [
                'families' => [
                    [
                        'name'    => ['The Does'],
                        'members' => [
                            [
                                'first_name' => ['John'],
                                'last_name'  => ['Doe'],
                            ],
                            [
                                'first_name' => ['Jane'],
                                'last_name'  => ['Doe'],
                            ],
                        ],
                    ],
                    [
                        'name'    => ['The Fockers'],
                        'members' => [
                            [
                                'first_name' => ['Gaylord'],
                                'last_name'  => ['Focker'],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_an_orphan_group_end_tag_is_found(): void
    {
        try {
            $this->extractAndMatch('exception-group-end-orphan', []);
        } catch (HtmlDataExtractionException $exception) {
            /** @var Throwable $validationException */
            $validationException = $exception->getPrevious();

            self::assertInstanceOf(TemplateValidationException::class, $validationException);
            self::assertSame('Group end tag found without a group start tag', $validationException->getMessage());
        }
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_a_group_start_tag_if_not_followed_by_an_element_node(): void
    {
        try {
            $this->extractAndMatch('exception-group-start-element', []);
        } catch (HtmlDataExtractionException $exception) {
            /** @var Throwable $validationException */
            $validationException = $exception->getPrevious();

            self::assertInstanceOf(TemplateValidationException::class, $validationException);
            self::assertSame('Missing element node after group start tag', $validationException->getMessage());
        }
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_a_group_end_tag_is_missing(): void
    {
        try {
            $this->extractAndMatch('exception-group-end-missing', []);
        } catch (HtmlDataExtractionException $exception) {
            /** @var Throwable $validationException */
            $validationException = $exception->getPrevious();

            self::assertInstanceOf(TemplateValidationException::class, $validationException);
            self::assertSame('Missing group end tag after element node', $validationException->getMessage());
        }
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_the_same_placeholder_occurs_more_than_once(): void
    {
        try {
            $this->extractAndMatch('exception-duplicate-data', []);
        } catch (HtmlDataExtractionException $exception) {
            /** @var Throwable $parsingException */
            $parsingException = $exception->getPrevious();

            self::assertInstanceOf(TemplateParsingException::class, $parsingException);
            self::assertInstanceOf(TemplateNodeDataAlreadyExistsException::class, $parsingException->getPrevious());
        }
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_the_same_selector_occurs_more_than_once(): void
    {
        try {
            $this->extractAndMatch('exception-duplicate-child', []);
        } catch (HtmlDataExtractionException $exception) {
            /** @var Throwable $parsingException */
            $parsingException = $exception->getPrevious();

            self::assertInstanceOf(TemplateParsingException::class, $parsingException);
            self::assertInstanceOf(TemplateNodeChildAlreadyExistsException::class, $parsingException->getPrevious());
        }
    }

    /**
     * @param string                           $directory
     * @param array<int, array<string, mixed>> $expected
     * @throws HtmlDataExtractionException
     */
    protected function extractAndMatch(string $directory, array $expected): void
    {
        /** @var string $source */
        $source = file_get_contents(__DIR__ . "/../stubs/{$directory}/source.html");

        /** @var string $template */
        $template = file_get_contents(__DIR__ . "/../stubs/{$directory}/template.html.twig");

        $rows = $this->htmlDataExtractor->extract($source, $template);

        $actual = array_map(function (SourceRowInterface $row): array {
            return $row->toArray();
        }, $rows);

        self::assertSame($expected, $actual);
    }
}
