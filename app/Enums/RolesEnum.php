<?php

namespace App\Enums;

enum RolesEnum: string
{
    case VIEW = 'view';
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case ALL = '*';

    public function label(): string
    {
        return match ($this) {
            RolesEnum::VIEW => '显示',
            RolesEnum::CREATE => '添加',
            RolesEnum::UPDATE => '修改',
            RolesEnum::DELETE => '删除',
            RolesEnum::ALL => '最高权限'
        };
    }
//    public function page(PageEnum $page): string
//    {
//        return match ($this) {
//            RolesEnum::VIEW => $page->value. '.'.RolesEnum::VIEW->value,
//            RolesEnum::CREATE => $page->value. '.'.RolesEnum::CREATE->value,
//            RolesEnum::UPDATE => $page->value. '.'.RolesEnum::UPDATE->value,
//            RolesEnum::DELETE => $page->value. '.'.RolesEnum::DELETE->value,
//            RolesEnum::ALL => $page->value. '.'.RolesEnum::DELETE->value,
//        };
//    }
}
