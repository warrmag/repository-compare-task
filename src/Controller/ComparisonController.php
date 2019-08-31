<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\ComparisonData;
use App\Service\Comparison\ComparisonServiceInterface;
use JMS\Serializer\SerializerInterface;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ComparisonController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var ComparisonServiceInterface */
    private $comparisonService;

    public function __construct(SerializerInterface $serializer, ComparisonServiceInterface $comparisionService)
    {
        $this->serializer = $serializer;
        $this->comparisonService = $comparisionService;
    }

    /**
     * @SWG\Parameter(
     *     name="List of repositories to compare",in="body",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(
     *              property="repository_list",
     *              type="array",
     *              @SWG\Items(type="string")
     *          )
     *      ),
     *     description="array of URL's or names as user/repository fe. \'symfony/symfony-docs \'"
     * )
     * @SWG\Response(response=200, description="Returns repository list with basic statistics")
     * @param Request $request
     * @return Response
     */
    public function createRepositoriesComparison(Request $request): Response
    {
        /** @var ComparisonData $comparisonData */
        $comparisonData = $this->serializer->deserialize(
            $request->getContent(),
            ComparisonData::class,
            'json'
        );
        $result = $this->comparisonService->create($comparisonData);
        return new Response($this->serializer->serialize($result, 'json'), Response::HTTP_ACCEPTED);
    }
}