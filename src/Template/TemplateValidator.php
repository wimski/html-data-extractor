<?php

declare(strict_types=1);

namespace Wimski\HtmlDataExtractor\Template;

use Wimski\HtmlDataExtractor\Contracts\Template\TemplateValidatorInterface;

class TemplateValidator implements TemplateValidatorInterface
{
    /**
     * @var array<int, TemplateValidatorInterface>
     */
    protected array $validators = [];

    public function __construct(
        TemplateValidatorInterface ...$templateValidator,
    ) {
        foreach ($templateValidator as $validator) {
            $this->validators[] = $validator;
        }
    }

    public function validate(string $template): void
    {
        foreach ($this->validators as $validator) {
            $validator->validate($template);
        }
    }
}
