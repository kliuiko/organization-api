<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetOrganizationsInBoundsRequest;
use App\Http\Requests\GetOrganizationsInRadiusRequest;
use App\Http\Requests\SearchOrganizationsRequest;
use App\Http\Resources\ApiResponseResource;
use App\Repositories\Interfaces\OrganizationRepositoryInterface;
use App\Services\OrganizationSearchService;

/**
 * @OA\Info(
 *     title="API документация",
 *     version="1.0.0",
 *     description="Описание API",
 * )
 * @OA\PathItem(
 *      path="/api/v1/"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="apiKeyAuth",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-KEY"
 * )
 */
class OrganizationController extends Controller
{
    /**
     * @param OrganizationRepositoryInterface $organizationRepository
     * @param OrganizationSearchService $organizationSearchService
     */
    public function __construct(
        private readonly OrganizationRepositoryInterface $organizationRepository,
        private readonly OrganizationSearchService       $organizationSearchService
    )
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/organizations/",
     *     summary="Получение всех организаций",
     *     tags={"Organizations"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Baumbach, Mitchell and Walker"),
     *                     @OA\Property(property="building", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="address", type="string", example="259 Blanche Ford\nPort Ephraim, MN 75163"),
     *                         @OA\Property(property="latitude", type="string", example="-53.7149580"),
     *                         @OA\Property(property="longitude", type="string", example="140.4706130")
     *                     ),
     *                     @OA\Property(property="phones", type="array",
     *                         @OA\Items(type="string", example="+1-760-833-6745")
     *                     ),
     *                     @OA\Property(property="activities", type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=8),
     *                             @OA\Property(property="name", type="string", example="Аксессуары")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(): ApiResponseResource
    {
        $organizations = $this->organizationRepository->getAll();
        return new ApiResponseResource($organizations);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/organizations/{id}",
     *     summary="Получение организации по ID",
     *     tags={"Organizations"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Baumbach, Mitchell and Walker"),
     *                 @OA\Property(property="building", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="address", type="string", example="259 Blanche Ford\nPort Ephraim, MN 75163"),
     *                     @OA\Property(property="latitude", type="string", example="-53.7149580"),
     *                     @OA\Property(property="longitude", type="string", example="140.4706130")
     *                 ),
     *                 @OA\Property(property="phones", type="array",
     *                     @OA\Items(type="string", example="+1-760-833-6745")
     *                 ),
     *                 @OA\Property(property="activities", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=8),
     *                         @OA\Property(property="name", type="string", example="Аксессуары")
     *                     )
     *                 )
     *             )
     *         ),
     *     )
     * )
     */
    public function show(int $id): ApiResponseResource
    {
        $organization = $this->organizationRepository->getById($id);
        return new ApiResponseResource($organization);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/organizations/by-building/{id}",
     *     summary="Получение всех организаций в здании с ID",
     *     tags={"Organizations"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Baumbach, Mitchell and Walker"),
     *                     @OA\Property(property="building", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="address", type="string", example="259 Blanche Ford\nPort Ephraim, MN 75163"),
     *                         @OA\Property(property="latitude", type="string", example="-53.7149580"),
     *                         @OA\Property(property="longitude", type="string", example="140.4706130")
     *                     ),
     *                     @OA\Property(property="phones", type="array",
     *                         @OA\Items(type="string", example="+1-760-833-6745")
     *                     ),
     *                     @OA\Property(property="activities", type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=8),
     *                             @OA\Property(property="name", type="string", example="Аксессуары")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function byBuilding(int $id): ApiResponseResource
    {
        $organizations = $this->organizationRepository->getByBuildingId($id);
        return new ApiResponseResource($organizations);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/organizations/by-activity/{id}",
     *     summary="Получение всех организаций с видом деятельности ID",
     *     tags={"Organizations"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Baumbach, Mitchell and Walker"),
     *                     @OA\Property(property="building", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="address", type="string", example="259 Blanche Ford\nPort Ephraim, MN 75163"),
     *                         @OA\Property(property="latitude", type="string", example="-53.7149580"),
     *                         @OA\Property(property="longitude", type="string", example="140.4706130")
     *                     ),
     *                     @OA\Property(property="phones", type="array",
     *                         @OA\Items(type="string", example="+1-760-833-6745")
     *                     ),
     *                     @OA\Property(property="activities", type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=8),
     *                             @OA\Property(property="name", type="string", example="Аксессуары")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function byActivity(int $id): ApiResponseResource
    {
        $organizations = $this->organizationRepository->getByActivityId($id);
        return new ApiResponseResource($organizations);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/organizations/by-radius",
     *     summary="Поиск организаций в заданном радиусе",
     *     tags={"Organizations"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *          name="latitude",
     *          in="query",
     *          required=true,
     *          description="Широта (-90 до 90)",
     *          @OA\Schema(type="number", format="float", example=55.751244, minimum=-90, maximum=90)
     *      ),
     *      @OA\Parameter(
     *          name="longitude",
     *          in="query",
     *          required=true,
     *          description="Долгота (-180 до 180)",
     *          @OA\Schema(type="number", format="float", example=37.618423, minimum=-180, maximum=180)
     *      ),
     *      @OA\Parameter(
     *          name="radius",
     *          in="query",
     *          required=true,
     *          description="Радиус поиска (мин. 0.1)",
     *          @OA\Schema(type="number", format="float", example=10.5, minimum=0.1)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Baumbach, Mitchell and Walker"),
     *                     @OA\Property(property="building", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="address", type="string", example="259 Blanche Ford\nPort Ephraim, MN 75163"),
     *                         @OA\Property(property="latitude", type="string", example="-53.7149580"),
     *                         @OA\Property(property="longitude", type="string", example="140.4706130")
     *                     ),
     *                     @OA\Property(property="phones", type="array",
     *                         @OA\Items(type="string", example="+1-760-833-6745")
     *                     ),
     *                     @OA\Property(property="activities", type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=8),
     *                             @OA\Property(property="name", type="string", example="Аксессуары")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function byRadius(GetOrganizationsInRadiusRequest $request): ApiResponseResource
    {
        $organizations = $this->organizationRepository->getInRadius(
            $request->validated('latitude'),
            $request->validated('longitude'),
            $request->validated('radius')
        );

        return new ApiResponseResource($organizations);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/organizations/by-bounds",
     *     summary="Поиск организаций в заданной прямоугольной области",
     *     tags={"Organizations"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *          name="min_lat",
     *          in="query",
     *          required=true,
     *          description="Минимальная широта (-90 до 90)",
     *          @OA\Schema(type="number", format="float", example=-50.0, minimum=-90, maximum=90)
     *      ),
     *      @OA\Parameter(
     *          name="max_lat",
     *          in="query",
     *          required=true,
     *          description="Максимальная широта (-90 до 90, должна быть >= min_lat)",
     *          @OA\Schema(type="number", format="float", example=50.0, minimum=-90, maximum=90)
     *      ),
     *      @OA\Parameter(
     *          name="min_lng",
     *          in="query",
     *          required=true,
     *          description="Минимальная долгота (-180 до 180)",
     *          @OA\Schema(type="number", format="float", example=-100.0, minimum=-180, maximum=180)
     *      ),
     *      @OA\Parameter(
     *          name="max_lng",
     *          in="query",
     *          required=true,
     *          description="Максимальная долгота (-180 до 180, должна быть >= min_lng)",
     *          @OA\Schema(type="number", format="float", example=100.0, minimum=-180, maximum=180)
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Baumbach, Mitchell and Walker"),
     *                     @OA\Property(property="building", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="address", type="string", example="259 Blanche Ford\nPort Ephraim, MN 75163"),
     *                         @OA\Property(property="latitude", type="string", example="-53.7149580"),
     *                         @OA\Property(property="longitude", type="string", example="140.4706130")
     *                     ),
     *                     @OA\Property(property="phones", type="array",
     *                         @OA\Items(type="string", example="+1-760-833-6745")
     *                     ),
     *                     @OA\Property(property="activities", type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=8),
     *                             @OA\Property(property="name", type="string", example="Аксессуары")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function byBounds(GetOrganizationsInBoundsRequest $request): ApiResponseResource
    {
        $organizations = $this->organizationRepository->getInBounds(
            $request->validated('min_latitude'),
            $request->validated('max_latitude'),
            $request->validated('min_longitude'),
            $request->validated('max_longitude')
        );

        return new ApiResponseResource($organizations);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/organizations/search",
     *     summary="Поиск организаций по названию, деятельности или адресу",
     *     tags={"Organizations"},
     *     security={{"apiKeyAuth":{}}},
     *     @OA\Parameter(
     *          name="name",
     *          in="query",
     *          required=false,
     *          description="Название организации",
     *          @OA\Schema(type="string", example="Baumbach, Mitchell and Walker")
     *      ),
     *      @OA\Parameter(
     *          name="activity",
     *          in="query",
     *          required=false,
     *          description="Название деятельности",
     *          @OA\Schema(type="string", example="Аксессуары")
     *      ),
     *      @OA\Parameter(
     *          name="address",
     *          in="query",
     *          required=false,
     *          description="Адрес организации",
     *          @OA\Schema(type="string", example="259 Blanche Ford Port Ephraim, MN 75163")
     *      ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Baumbach, Mitchell and Walker"),
     *                     @OA\Property(property="building", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="address", type="string", example="259 Blanche Ford\nPort Ephraim, MN 75163"),
     *                         @OA\Property(property="latitude", type="string", example="-53.7149580"),
     *                         @OA\Property(property="longitude", type="string", example="140.4706130")
     *                     ),
     *                     @OA\Property(property="phones", type="array",
     *                         @OA\Items(type="string", example="+1-760-833-6745")
     *                     ),
     *                     @OA\Property(property="activities", type="array",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=8),
     *                             @OA\Property(property="name", type="string", example="Аксессуары")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function search(SearchOrganizationsRequest $request): ApiResponseResource
    {
        $organizations = $this->organizationSearchService->search($request);
        return new ApiResponseResource($organizations);
    }
}
