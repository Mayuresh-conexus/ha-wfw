<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="HealthApp Mobile API",
 *      description="REST API for Volunteer Mobile App",
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Parse the 'fields' query parameter for selective column retrieval.
     */
    protected function getSelectFields(\Illuminate\Http\Request $request, array $defaultFields): array
    {
        $fields = $request->query('fields');

        if (!$fields) {
            return $defaultFields;
        }

        $requested = array_filter(array_map('trim', explode(',', $fields)));

        // Optionally restrict to intersection with default/allowed fields
        // return array_intersect($requested, $defaultFields);

        return !empty($requested) ? $requested : $defaultFields;
    }
}
