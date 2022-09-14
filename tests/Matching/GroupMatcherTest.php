<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Tests\Matching;

use PHPUnit\Framework\TestCase;
use Wimski\HtmlDataExtractor\Matching\GroupMatcher;

class GroupMatcherTest extends TestCase
{
    protected GroupMatcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->matcher = new GroupMatcher();
    }

    /**
     * @test
     * @dataProvider groupStartDataProvider
     */
    public function it_matches_group_starts(string $value, ?string $result, bool $willMatch): void
    {
        static::assertSame($willMatch, $this->matcher->matchesGroupStart($value));
    }

    /**
     * @test
     * @dataProvider groupStartDataProvider
     */
    public function it_returns_group_start_matches(string $value, ?string $result, bool $willMatch): void
    {
        $match = $this->matcher->getGroupStartMatch($value);

        static::assertSame($result, $match?->getPartialMatch());
    }

    /**
     * @return array<int, array<int, bool|string|null>>
     */
    public function groupStartDataProvider(): array
    {
        return [
            ['{%group foo%}', 'foo', true],
            ['{% group foo %}', 'foo', true],
            ['{%group foo  %}', 'foo', true],
            ['{%  group foo%}', 'foo', true],
            ['  {%group foo%}  ', 'foo', true],
            ['{% group foo %} {% group bar %}', 'foo', true],
            ['{% groupfoo %}', null, false],
            ['{group foo}', null, false],
            ['{foo}', null, false],
            ['group foo', null, false],
            ['foo', null, false],
        ];
    }

    /**
     * @test
     * @dataProvider groupEndDataProvider
     */
    public function it_matches_group_ends(string $value, bool $willMatch): void
    {
        static::assertSame($willMatch, $this->matcher->matchesGroupEnd($value));
    }

    /**
     * @return array<int, array<int, bool|string>>
     */
    public function groupEndDataProvider(): array
    {
        return [
            ['{%endgroup%}', true],
            ['{% endgroup %}', true],
            ['{%endgroup  %}', true],
            ['{%  endgroup%}', true],
            ['  {%endgroup%}  ', true],
            ['{% endgroup %} {% endgroup %}', true],
            ['{% end group %}', false],
            ['{endgroup}', false],
            ['endgroup', false],
        ];
    }
}
