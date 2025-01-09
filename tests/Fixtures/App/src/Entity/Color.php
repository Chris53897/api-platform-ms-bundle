<?php

namespace Mtarld\ApiPlatformMsBundle\Tests\Fixtures\App\src\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @author Mathias Arlaud <mathias.arlaud@gmail.com>
 */
#[ApiResource(normalizationContext: ['groups' => 'read'])]
class Color
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        #[Groups(['read'])]
        public int $id,
        #[Groups(['read'])]
        public string $hex,
    ) {
    }
}
