<?php

namespace App\Repositories\Interfaces;

use App\Models\Organization;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrganizationRepositoryInterface
{
    /**
     * @param int $id
     * @return Organization
     */
    public function getById(int $id): Organization;

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAll(int $perPage = 50): LengthAwarePaginator;

    /**
     * @param int $id
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByBuildingId(int $id, int $perPage = 50): LengthAwarePaginator;

    /**
     * @param int $id
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getByActivityId(int $id, int $perPage = 50): LengthAwarePaginator;

    /**
     * @param float $latitude
     * @param float $longitude
     * @param float $radius
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getInRadius(float $latitude, float $longitude, float $radius, int $perPage = 50): LengthAwarePaginator;

    /**
     * @param float $minLat
     * @param float $maxLat
     * @param float $minLng
     * @param float $maxLng
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getInBounds(float $minLat, float $maxLat, float $minLng, float $maxLng, int $perPage = 50): LengthAwarePaginator;
}
