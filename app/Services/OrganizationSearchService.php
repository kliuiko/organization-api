<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrganizationSearchService
{
    public function search(Request $request, int $per_page = 50): LengthAwarePaginator
    {
        $query = Organization::query();

        $this->filterByName($query, $request);
        $this->filterByActivity($query, $request);
        $this->filterByAddress($query, $request);

        return $query->paginate($per_page);
    }

    private function filterByName(Builder $query, Request $request): void
    {
        if ($request->has('name')) {
            $query->whereRaw("MATCH(name) AGAINST(? IN BOOLEAN MODE)", [trim($request->query('name'))]);
        }
    }

    private function filterByActivity(Builder $query, Request $request): void
    {
        if ($request->has('activity')) {
            $activityName = trim($request->query('activity'));

            $activityIds = Activity::query()->where('name', 'like', '%' . $activityName . '%')
                ->orWhereIn('parent_id', function ($query) use ($activityName) {
                    $query->select('id')->from('activities')->whereRaw("MATCH(name) AGAINST(? IN BOOLEAN MODE)", [$activityName]);
                })
                ->pluck('id');

            if ($activityIds->isNotEmpty()) {
                $query->whereHas('activities', function (Builder $query) use ($activityIds) {
                    $query->whereIn('activities.id', $activityIds);
                });
            }
        }
    }

    private function filterByAddress(Builder $query, Request $request): void
    {
        if ($request->has('address')) {
            $address = trim($request->query('address'));
            $query->whereHas('building', function ($query) use ($address) {
                $query->where('address', 'like', '%' . $address . '%');
            });
        }
    }
}
