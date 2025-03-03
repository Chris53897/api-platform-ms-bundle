<?php

namespace Mtarld\ApiPlatformMsBundle\ApiResource;

use Mtarld\ApiPlatformMsBundle\Dto\ApiResourceExistenceCheckerPayload;
use Mtarld\ApiPlatformMsBundle\Dto\ApiResourceExistenceCheckerView;
use Mtarld\ApiPlatformMsBundle\HttpClient\GenericHttpClient;
use Mtarld\ApiPlatformMsBundle\HttpClient\ReplaceableHttpClientInterface;
use Mtarld\ApiPlatformMsBundle\HttpClient\ReplaceableHttpClientTrait;
use Mtarld\ApiPlatformMsBundle\Microservice\MicroservicePool;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

// Help opcache.preload discover always-needed symbols
class_exists(ApiResourceExistenceCheckerPayload::class);
class_exists(ApiResourceExistenceCheckerView::class);

/**
 * @final
 *
 * @author Mathias Arlaud <mathias.arlaud@gmail.com>
 */
class ExistenceChecker implements ReplaceableHttpClientInterface
{
    use ReplaceableHttpClientTrait;

    private $httpClient;

    public function __construct(
        GenericHttpClient $httpClient,
        private readonly SerializerInterface $serializer,
        private readonly MicroservicePool $microservices,
    ) {
        $this->httpClient = $httpClient;
    }

    /**
     * @param list<string> $iris
     *
     * @return array<string, bool>
     *
     * @throws ExceptionInterface
     */
    public function getExistenceStatuses(string $microserviceName, array $iris): array
    {
        if (empty($iris)) {
            return [];
        }

        $microservice = $this->microservices->get($microserviceName);

        $response = $this->httpClient->request(
            $microservice,
            'POST',
            sprintf('/%s_check_resource', $microserviceName),
            new ApiResourceExistenceCheckerPayload($iris),
            'application/json',
            'json'
        );

        /** @var ApiResourceExistenceCheckerView $checkedIris */
        $checkedIris = $this->serializer->deserialize($response->getContent(), ApiResourceExistenceCheckerView::class, 'json');

        return $checkedIris->existences;
    }
}
