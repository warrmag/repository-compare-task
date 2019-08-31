<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\ComparisionData;
use App\Service\Comparision\ComparisionServiceInterface;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ComparisonController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    /** @var ComparisionServiceInterface */
    private $comparisionService;

    public function __construct(SerializerInterface $serializer, ComparisionServiceInterface $comparisionService)
    {
        $this->serializer = $serializer;
        $this->comparisionService = $comparisionService;
    }

    /**
     * @SWG\Parameter(
     *     name="List of repository",in="body",
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
    public function createComparison(Request $request): Response
    {
        /** @var ComparisionData $comparisonData */
        $comparisonData = $this->serializer->deserialize(
            $request->getContent(),
            ComparisionData::class,
            'json'
        );
        $result = $this->comparisionService->create($comparisonData);
        return new Response($this->serializer->serialize($result, 'json'), Response::HTTP_ACCEPTED);
    }
}