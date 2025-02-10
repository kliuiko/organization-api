<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'success' => true,
            'data' => $this->resolveData(),
            'pagination' => $this->resolvePagination(),
            'message' => $this->message ?? 'Запрос выполнен успешно'
        ];
    }

    private function resolveData(): OrganizationResource|AnonymousResourceCollection
    {
        if ($this->resource instanceof LengthAwarePaginator) {
            return OrganizationResource::collection(collect($this->resource->items()));
        }

        return OrganizationResource::make($this->resource);
    }

    private function resolvePagination(): ?array
    {
        if ($this->resource instanceof LengthAwarePaginator) {
            $queryParams = request()->query();
            unset($queryParams['page']);

            return [
                'total' => $this->resource->total(),
                'per_page' => $this->resource->perPage(),
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
                'from' => $this->resource->firstItem(),
                'to' => $this->resource->lastItem(),
                'next_page_url' => $this->resource->nextPageUrl()
                    ? $this->resource->nextPageUrl() . '&' . http_build_query($queryParams)
                    : null,
                'prev_page_url' => $this->resource->previousPageUrl()
                    ? $this->resource->previousPageUrl() . '&' . http_build_query($queryParams)
                    : null,
            ];
        }

        return null;
    }
}
