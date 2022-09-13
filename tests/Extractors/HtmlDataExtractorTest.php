<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Extractors;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Contracts\Source\SourceRowInterface;
use Wimski\HtmlDataExtractor\Extractors\HtmlDataExtractor;
use Wimski\HtmlDataExtractor\Factories\SelectorFactory;
use Wimski\HtmlDataExtractor\HtmlLoader;
use Wimski\HtmlDataExtractor\Source\SourceParser;
use Wimski\HtmlDataExtractor\Template\GroupMatcher;
use Wimski\HtmlDataExtractor\Template\PlaceholderMatcher;
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
        $rows = $this->extract('text-single');

        self::assertCount(1, $rows);

        $row = $rows[0];

        self::assertCount(1, $row->getData());

        $data = $row->getData()[0];

        self::assertSame('text', $data->getPlaceholder());
        self::assertSame(['Lipsum'], $data->getValues());
    }

    /**
     * @test
     * @depends it_extracts_a_single_text_value
     */
    public function it_extracts_an_empty_text_value(): void
    {
        $rows = $this->extract('text-empty');

        self::assertCount(1, $rows);

        $row = $rows[0];

        self::assertCount(1, $row->getData());

        $data = $row->getData()[0];

        self::assertSame('text', $data->getPlaceholder());
        self::assertSame([''], $data->getValues());
    }

    /**
     * @test
     * @depends it_extracts_a_single_text_value
     */
    public function it_extracts_multiple_text_values(): void
    {
        $rows = $this->extract('text-multiple');

        self::assertCount(2, $rows);

        $row1 = $rows[0];

        self::assertCount(1, $row1->getData());

        $data1 = $row1->getData()[0];

        self::assertSame('text', $data1->getPlaceholder());
        self::assertSame(['Lorem'], $data1->getValues());

        $row2 = $rows[1];

        self::assertCount(1, $row2->getData());

        $data2 = $row2->getData()[0];

        self::assertSame('text', $data2->getPlaceholder());
        self::assertSame(['Ipsum'], $data2->getValues());
    }

    /**
     * @test
     */
    public function it_extracts_a_single_attribute_value(): void
    {
        $rows = $this->extract('attribute-single');

        self::assertCount(1, $rows);

        $row = $rows[0];

        self::assertCount(1, $row->getData());

        $data = $row->getData()[0];

        self::assertSame('attribute', $data->getPlaceholder());
        self::assertSame(['Lipsum'], $data->getValues());
    }

    /**
     * @test
     * @depends it_extracts_a_single_attribute_value
     */
    public function it_extracts_an_empty_attribute_value(): void
    {
        $rows = $this->extract('attribute-empty');

        self::assertCount(1, $rows);

        $row = $rows[0];

        self::assertCount(1, $row->getData());

        $data = $row->getData()[0];

        self::assertSame('attribute', $data->getPlaceholder());
        self::assertSame([''], $data->getValues());
    }

    /**
     * @test
     * @depends it_extracts_a_single_attribute_value
     */
    public function it_extracts_multiple_attribute_values(): void
    {
        $rows = $this->extract('attribute-multiple');

        self::assertCount(2, $rows);

        $row1 = $rows[0];

        self::assertCount(1, $row1->getData());

        $data1 = $row1->getData()[0];

        self::assertSame('attribute', $data1->getPlaceholder());
        self::assertSame(['Lorem'], $data1->getValues());

        $row2 = $rows[1];

        self::assertCount(1, $row2->getData());

        $data2 = $row2->getData()[0];

        self::assertSame('attribute', $data2->getPlaceholder());
        self::assertSame(['Ipsum'], $data2->getValues());
    }

    /**
     * @test
     */
    public function it_extracts_with_a_specific_selector(): void
    {
        $rows = $this->extract('selector-specific');

        self::assertCount(1, $rows);

        $row = $rows[0];

        self::assertCount(2, $row->getData());

        $data1 = $row->getData()[0];

        self::assertSame('text', $data1->getPlaceholder());
        self::assertSame(['Ipsum'], $data1->getValues());

        $data2 = $row->getData()[1];

        self::assertSame('attribute', $data2->getPlaceholder());
        self::assertSame(['Lorem'], $data2->getValues());
    }

    /**
     * @test
     */
    public function it_extracts_on_varying_levels(): void
    {
        $rows = $this->extract('varying-levels');

        self::assertCount(4, $rows);

        $texts = [
            'Lorem',
            'Ipsum',
            'Dolor',
            'Amet',
        ];

        foreach ($rows as $i => $row) {
            self::assertSame($texts[$i], $row->getFirstValueByPlaceholder('text'));
        }
    }

    /**
     * @test
     */
    public function it_extracts_groups(): void
    {
        $rows = $this->extract('groups');

        self::assertCount(2, $rows);

        $row1 = $rows[0];

        self::assertSame('Lorem', $row1->getFirstValueByPlaceholder('name'));
        self::assertSame('Lorem ipsum dolor sit amet', $row1->getFirstValueByPlaceholder('description'));
        self::assertSame('http://image.jpg', $row1->getFirstValueByPlaceholder('image'));
        self::assertCount(3, $row1->getData());
        self::assertCount(2, $row1->getGroups());

        $authors1 = $row1->getGroupByName('authors');

        self::assertCount(2, $authors1->getRows());

        $author11 = $authors1->getRows()[0];

        self::assertSame('John', $author11->getFirstValueByPlaceholder('first_name'));
        self::assertSame('Doe', $author11->getFirstValueByPlaceholder('last_name'));

        $author12 = $authors1->getRows()[1];

        self::assertSame('Jane', $author12->getFirstValueByPlaceholder('first_name'));
        self::assertSame('Smith', $author12->getFirstValueByPlaceholder('last_name'));

        $links1 = $row1->getGroupByName('links');

        self::assertCount(2, $links1->getRows());

        $link11 = $links1->getRows()[0];

        self::assertSame('http://site1.com', $link11->getFirstValueByPlaceholder('url'));
        self::assertSame('Website 1', $link11->getFirstValueByPlaceholder('name'));

        $link12 = $links1->getRows()[1];

        self::assertSame('http://site2.com', $link12->getFirstValueByPlaceholder('url'));
        self::assertSame('Website 2', $link12->getFirstValueByPlaceholder('name'));

        $row2 = $rows[1];

        self::assertSame('Ipsum', $row2->getFirstValueByPlaceholder('name'));
        self::assertSame('', $row2->getFirstValueByPlaceholder('description'));
        self::assertSame('http://picture.png', $row2->getFirstValueByPlaceholder('image'));
        self::assertCount(3, $row2->getData());
        self::assertCount(2, $row2->getGroups());

        $authors2 = $row2->getGroupByName('authors');

        self::assertCount(1, $authors2->getRows());

        $author21 = $authors2->getRows()[0];

        self::assertCount(2, $author21->getData());
        self::assertSame('Bob', $author21->getFirstValueByPlaceholder('first_name'));
        self::assertSame('Bobson', $author21->getFirstValueByPlaceholder('last_name'));

        $links2 = $row2->getGroupByName('links');

        self::assertCount(1, $links2->getRows());

        $link21 = $links2->getRows()[0];

        self::assertSame('http://site3.com', $link21->getFirstValueByPlaceholder('url'));
        self::assertSame('Website 3', $link21->getFirstValueByPlaceholder('name'));
    }

    /**
     * @test
     */
    public function it_extracts_nested_groups(): void
    {
        $rows = $this->extract('groups-nested');

        self::assertCount(1, $rows);

        $row = $rows[0];

        self::assertCount(1, $row->getGroups());

        $familyGroup = $row->getGroupByName('families');

        self::assertCount(2, $familyGroup->getRows());

        $family1 = $familyGroup->getRows()[0];

        self::assertCount(1, $family1->getData());
        self::assertSame('The Does', $family1->getFirstValueByPlaceholder('name'));

        self::assertCount(1, $family1->getGroups());

        $memberGroup1 = $family1->getGroupByName('members');

        self::assertCount(2, $memberGroup1->getRows());

        $member11 = $memberGroup1->getRows()[0];

        self::assertSame('John', $member11->getFirstValueByPlaceholder('first_name'));
        self::assertSame('Doe', $member11->getFirstValueByPlaceholder('last_name'));

        $member12 = $memberGroup1->getRows()[1];

        self::assertSame('Jane', $member12->getFirstValueByPlaceholder('first_name'));
        self::assertSame('Doe', $member12->getFirstValueByPlaceholder('last_name'));

        $family2 = $familyGroup->getRows()[1];

        self::assertCount(1, $family2->getData());
        self::assertSame('The Fockers', $family2->getFirstValueByPlaceholder('name'));

        self::assertCount(1, $family2->getGroups());

        $memberGroup2 = $family2->getGroupByName('members');

        self::assertCount(1, $memberGroup2->getRows());

        $member21 = $memberGroup2->getRows()[0];

        self::assertSame('Gaylord', $member21->getFirstValueByPlaceholder('first_name'));
        self::assertSame('Focker', $member21->getFirstValueByPlaceholder('last_name'));
    }

    /**
     * @param string $directory
     * @return array<int, SourceRowInterface>
     */
    protected function extract(string $directory): array
    {
        /** @var string $source */
        $source = file_get_contents(__DIR__ . "/../stubs/{$directory}/source.html");

        /** @var string $template */
        $template = file_get_contents(__DIR__ . "/../stubs/{$directory}/template.html.twig");

        return $this->htmlDataExtractor->extract($source, $template);
    }
}
