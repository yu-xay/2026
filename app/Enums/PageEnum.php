<?php

namespace App\Enums;

enum PageEnum: string
{
    case ROLE = 'role';
    case USER = 'user';
    case TENANT = 'tenant';
    case TELESCOPE = 'telescope';
    case DOCS = 'docs';
    case HORIZON = 'horizon';
    case PULSE = 'pulse';
    case USERCENTER = 'user_center';
    case GOODS = 'goods';
    case GOODS_CATS = 'goods-cat';
    case ORDER = 'order';

    public function label(): string
    {
        return match ($this) {
            PageEnum::ROLE => '角色',
            PageEnum::GOODS => '商品列表',
            PageEnum::GOODS_CATS => '分类',
            PageEnum::USER => '用户中心',
            PageEnum::TENANT => '多租户',
            PageEnum::TELESCOPE => 'Telescope 调试工具',
            PageEnum::HORIZON => '队列监控面板 - Horizon',
            PageEnum::DOCS => 'Swagger - Docs',
            PageEnum::PULSE => '应用性能监控工具',
            PageEnum::USERCENTER => '个人中心',
            PageEnum::ORDER => '订单列表',
        };
    }

    public function exclude(): array
    {
        return match ($this) {
            PageEnum::TELESCOPE, PageEnum::DOCS, PageEnum::HORIZON, PageEnum::PULSE, PageEnum::USERCENTER => [RolesEnum::CREATE->value, RolesEnum::UPDATE->value, RolesEnum::DELETE->value],
            default => [],
        };
    }
}
