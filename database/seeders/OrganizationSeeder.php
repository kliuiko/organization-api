<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organization;
use App\Models\Phone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OrganizationSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $activities = [
            'Еда' => ['Мясная продукция', 'Молочная продукция'],
            'Автомобили' => ['Грузовые', 'Легковые' => ['Запчасти', 'Аксессуары']]
        ];

        $activityIds = [];
        foreach ($activities as $parent => $children) {
            $parentActivity = Activity::query()->create(['name' => $parent]);
            foreach ((array) $children as $child => $subchildren) {
                if (is_array($subchildren)) {
                    $childActivity = Activity::query()->create(['name' => $child, 'parent_id' => $parentActivity->id]);
                    foreach ($subchildren as $subchild) {
                        $activityIds[] = Activity::query()->create(['name' => $subchild, 'parent_id' => $childActivity->id])->id;
                    }
                } else {
                    $activityIds[] = Activity::query()->create(['name' => $subchildren, 'parent_id' => $parentActivity->id])->id;
                }
            }
        }

        for ($i = 0; $i < 100; $i++) {
            $building = Building::query()->create([
                'address' => $faker->address,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
            ]);

            $organization = Organization::create([
                'name' => $faker->company,
                'building_id' => $building->id,
            ]);

            for ($j = 0; $j < rand(1, 3); $j++) {
                Phone::query()->create([
                    'organization_id' => $organization->id,
                    'phone_number' => $faker->phoneNumber,
                ]);
            }

            $organization->activities()->attach(array_rand(array_flip($activityIds), rand(1, 3)));
        }
    }
}
