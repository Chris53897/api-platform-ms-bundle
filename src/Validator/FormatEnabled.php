<?php

namespace Mtarld\ApiPlatformMsBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @final @internal
 *
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @author Mathias Arlaud <mathias.arlaud@gmail.com>
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class FormatEnabled extends Constraint
{
    /**
     * @var string
     *
     * Violation message
     */
    public $message = "'{{ format }}' format is not enabled.";

    /**
     * @param array<string, mixed> $options
     * @param array<string>        $groups
     */
    public function __construct(array $options = [], ?string $message = null, ?array $groups = null, mixed $payload = null)
    {
        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}
