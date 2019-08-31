<?php
declare(strict_types=1);

namespace App\Api\Git;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

abstract class AbstractGitClient
{
    /** @var ParameterBagInterface */
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param string $urlPart
     * @param array $options
     * @return array|null
     * @throws GitException
     */
    public function getArrayResponse(string $urlPart, array $options = []): array
    {
        $client = HttpClient::create();
        try {
            $response = $client->request(
                Request::METHOD_GET,
                $this->parameterBag->get('github_api') . $urlPart,
                $options
            );
            $arrayResponse = $response->toArray();
        } catch (ClientExceptionInterface $exception) {
            throw new GitException('Cannot find requested repository', Response::HTTP_NOT_FOUND);
        } catch (ExceptionInterface $exception) {
            throw new GitException("Whoops, something went wrong", $exception->getCode());
        }
        return $arrayResponse;
    }
}