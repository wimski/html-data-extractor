<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor;

use Symfony\Component\DomCrawler\Crawler;
use Wimski\HtmlDataExtractor\Contracts\ExtractableInterface;
use Wimski\HtmlDataExtractor\Contracts\ExtractableProcessorInterface;
use Wimski\HtmlDataExtractor\Contracts\Results\ResultInterface;
use Wimski\HtmlDataExtractor\Contracts\Results\ResultSetInterface;
use Wimski\HtmlDataExtractor\Enums\ExtractableTypeEnum;
use Wimski\HtmlDataExtractor\Results\Result;
use Wimski\HtmlDataExtractor\Results\ResultSet;

class ExtractableProcessor implements ExtractableProcessorInterface
{
    public function process(string $html, array $extractables): ResultSetInterface
    {
        $resultSet = new ResultSet();

        $crawler = new Crawler($html);

        foreach ($extractables as $extractable) {
            $result = $this->getResultForExtractable($crawler, $extractable);

            $resultSet->addResult($result);
        }

        return $resultSet;
    }

    protected function getResultForExtractable(Crawler $html, ExtractableInterface $extractable): ResultInterface
    {
        $result = new Result($extractable->getPlaceholder());

        $html->filter($extractable->getSelector())->each(function (Crawler $item) use (&$result, $extractable) {
            switch ($extractable->getType()) {
                case ExtractableTypeEnum::TEXT:
                    $result->addData($item->text());
                    break;

                case ExtractableTypeEnum::ATTRIBUTE:
                    $result->addData($this->getAttributeData($item, $extractable));
                    break;
            }
        });

        return $result;
    }

    protected function getAttributeData(Crawler $html, ExtractableInterface $extractable): string
    {
        /** @var string $attributeName */
        $attributeName = $extractable->getAttributeName();

        /** @var string $attribute */
        $attribute = $html->attr($attributeName);

        return $attribute;
    }
}
