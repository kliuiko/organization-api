<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Repositories\Interfaces\OrganizationRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizationRepository implements OrganizationRepositoryInterface
{
    public function getById(int $id): Organization
    {
        return Organization::with(['building', 'activities', 'phones'])
            ->findOrFail($id);
    }

    public function getAll(int $perPage = 50): LengthAwarePaginator
    {
        return Organization::query()
            ->paginate($perPage);
    }

    public function getByBuildingId(int $id, int $perPage = 50): LengthAwarePaginator
    {
        return Organization::query()
            ->where('building_id', $id)
            ->paginate($perPage);
    }

    public function getByActivityId(int $id, int $perPage = 50): LengthAwarePaginator
    {
        return Organization::query()
            ->whereHas('activities', function (Builder $query) use ($id) {
                $query->where('id', $id);
            })
            ->paginate($perPage);
    }

    public function getInRadius(float $latitude, float $longitude, float $radius, int $perPage = 50): LengthAwarePaginator
    {
        return Organization::query()->whereHas('building', function (Builder $query) use ($latitude, $longitude, $radius) {
            $query->whereRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) < ?",
                [$latitude, $longitude, $latitude, $radius]
            );
        })->paginate($perPage);
    }

    public function getInBounds(float $minLat, float $maxLat, float $minLng, float $maxLng, int $perPage = 50): LengthAwarePaginator
    {
        return Organization::query()->whereHas('building', function (Builder $query) use ($minLat, $maxLat, $minLng, $maxLng) {
            $query->whereBetween('latitude', [$minLat, $maxLat])
                ->whereBetween('longitude', [$minLng, $maxLng]);
        })->paginate($perPage);
    }
}
