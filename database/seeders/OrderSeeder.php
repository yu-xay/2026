<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('zh_CN');

        // 假设状态含义：0=待支付, 1=已取消, 2=已支付, 3=已发货, 4=已完成
        for ($i = 0; $i < 50; $i++) {
            $status = $faker->randomElement([0, 1, 2, 3, 4]);

            // 1. 先生成基础创建时间（今年内）
            $createdAt = $faker->dateTimeThisYear();

            // 2. 初始化支付与发货时间
            $paidAt = null;
            $shippedAt = null;

            // 3. 根据状态，严格按时间先后顺序推算
            if (in_array($status, [2, 3, 4])) {
                // 支付时间在创建时间之后，3天内
                $paidAt = $faker->dateTimeBetween($createdAt, $createdAt->format('Y-m-d H:i:s') . ' +3 days');

                // 只有已发货(3)和已完成(4)才有发货时间
                if (in_array($status, [3, 4])) {
                    // 发货时间在支付时间之后，3天内
                    $shippedAt = $faker->dateTimeBetween($paidAt, $paidAt->format('Y-m-d H:i:s') . ' +3 days');
                }
            }

            $totalAmount = $faker->randomFloat(2, 100, 5000);

            DB::table('orders')->insert([
                'uuid' => Str::uuid(),
                'order_sn' => date('YmdHis') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'user_id' => rand(1, 10),

                'total_amount' => $totalAmount,
                // 修复：$status 是数字，原代码 'pending' 字符串判断不成立
                'pay_amount' => in_array($status, [0, 1]) ? 0 : $totalAmount - rand(0, 10),

                'status' => $status,
                'address_snapshot' => json_encode([
                    'name' => $faker->name,
                    'phone' => $faker->phoneNumber,
                    'address' => $faker->address,
                ], JSON_UNESCAPED_UNICODE),

                'paid_at' => $paidAt,
                'shipped_at' => $shippedAt,
                'created_at' => $createdAt, // 使用推算出的创建时间
                'updated_at' => now(),
                'deleted_at' => null,
            ]);
        }
    }

}
