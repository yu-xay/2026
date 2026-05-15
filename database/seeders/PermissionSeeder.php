<?php

namespace Database\Seeders;

use App\Enums\PageEnum;
use App\Enums\RolesEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // 禁用外键检查
        DB::table('permissions')->truncate();       // 执行 TRUNCATE 操作
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // 恢复外键检查

        $collectionA = collect(PageEnum::cases());
        $collectionB = collect(RolesEnum::cases());
        $cartesianProduct = $collectionA->flatMap(function (PageEnum $a) use ($collectionB) {
            return $collectionB->map(function ($b) use ($a) {
                if (!in_array($b->value, $a->exclude())) {
                    Permission::firstOrCreate([
                        'name' => $a->value . '.' . $b->value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
        });
    }
}
