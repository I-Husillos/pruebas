<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\TreatmentCategory;

use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\TreatmentCategory;

#[OA\Tag(
    name: "Treatment Categories",
    description: "Endpoints para gestionar treatment categories"
)]
final class TreatmentCategoriesGetController extends ApiController
{
    #[OA\Get(
        path: "/api/v1/treatment-categories",
        tags: ["Treatment Categories"],
        summary: "Listar TreatmentCategory",
        description: "Listar TreatmentCategory",
        operationId: "listTreatmentCategory",
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(response: 200, description: "Éxito")]
    #[OA\Response(response: 401, description: "No autenticado")]
    public function __invoke(): JsonResponse
    {
        $categories = TreatmentCategory::all();
        return $this->sendResponse($categories->toArray(), 'Treatment categories retrieved successfully');
    }
}
