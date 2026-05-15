<?php

namespace App\Http\Controllers\Common;

use App\Enums\PageEnum;
use App\Enums\RolesEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\RouteAttributes\Attributes\Get;

class ConfigController extends R
{
    #[Get('config')]
    public function __invoke(): JsonResponse
    {
        $label = [];
        foreach (RolesEnum::cases() as $item) {
            $label[$item->value] = $item->label();
        }
        $menus = $this->getMenus();
        if(tenant()){
            unset($menus[2]['children'][0],$menus[2]['children'][1]);
            $menus[2]['children'] = array_values($menus[2]['children']);
        }

        $permissions = [];
        if (!Gate::check('is-admin')) {
            //权限判断
            $user = Auth::user();
            $permissions = $user->getAllPermissions()->pluck('name')->toArray();
            $flippedPermissions = array_flip($permissions);

            $filter = function ($menus) use ($flippedPermissions, &$filter) {
                $result = [];
                foreach ($menus as $menu) {
                    $p = $menu['permission'] ?? '';

                    if (!empty($menu['children'])) {
                        $menu['children'] = $filter($menu['children']);
                        if (!empty($menu['children'])) {
                            $result[] = $menu;
                        }
                        continue;
                    }

                    $viewPermission = $p . '.' . RolesEnum::VIEW->value;
                    $allPermission = $p . '.' . RolesEnum::ALL->value;

                    $hasAccess = empty($p) ||
                        isset($flippedPermissions[$viewPermission]) ||
                        isset($flippedPermissions[$allPermission]);

                    if ($hasAccess) {
                        $result[] = $menu;
                    }
                }
                return $result;
            };
            $menus = $filter($menus);
        }
        return $this->success([
            'label' => $label,
            'isAdmin' => Gate::check('is-admin'),
            'permissions' => $permissions,
            'menus' => $menus,
        ]);
    }

    private function getMenus(): array
    {
        return [
            [
                'name' => '个人中心',
                'icon' => 'Setting',
                'key' => 'center',
                'is_menu' => true,
                'permission' => '',
            ],
            [
                'name' => '商品管理',
                'icon' => 'Handbag',
                'key' => '_goods',
                'is_menu' => true,
                'children' => [
                    [
                        'name' => PageEnum::GOODS->label(),
                        'key' => PageEnum::GOODS->value,
                        'is_menu' => true,
                        'permission' => PageEnum::GOODS->value,
                    ],
                    [
                        'name' => '',
                        'key' => 'goods-edit',
                        'page' => 'goods-edit/:id?',
                        'is_menu' => false,
                        'permission' => PageEnum::GOODS->value,
                    ],
                    [
                        'name' => PageEnum::GOODS_CATS->label(),
                        'key' => PageEnum::GOODS_CATS->value,
                        'is_menu' => true,
                        'permission' => PageEnum::GOODS_CATS->value,
                    ],
                ]
            ],
            [
                'name' => '订单管理',
                'icon' => 'Handbag',
                'key' => '_order',
                'is_menu' => true,
                'children' => [
                    [
                        'name' => PageEnum::ORDER->label(),
                        'key' => PageEnum::ORDER->value,
                        'is_menu' => true,
                        'permission' => PageEnum::ORDER->value,
                    ],
                    [
                        'name' => '',
                        'key' => 'order-detail',
                        'page' => 'order-detail/:id?',
                        'is_menu' => false,
                        'permission' => PageEnum::ORDER->value,
                    ]
                ]
            ],
            [
                'name' => '用户管理',
                'icon' => 'Handbag',
                'key' => '_user',
                'is_menu' => true,
                'children' => [
                    [
                        'name' => PageEnum::ROLE->label(),
                        'key' => PageEnum::ROLE->value,
                        'is_menu' => true,
                        'permission' => PageEnum::ROLE->value,
                    ],
                    [
                        'name' => '',
                        'key' => 'role-edit',
                        'page' => 'role-edit/:id?',
                        'is_menu' => false,
                        'permission' => PageEnum::ROLE->value,
                    ],
                    [
                        'name' => PageEnum::USER->label(),
                        'key' => PageEnum::USER->value,
                        'is_menu' => true,
                        'permission' => PageEnum::USER->value,
                    ],
                    [
                        'name' => '',
                        'key' => 'user-edit',
                        'page' => 'user-edit/:id?',
                        'is_menu' => false,
                        'permission' => PageEnum::USER->value,
                    ],
                ]
            ],
            [
                'name' => '多租户',
                'icon' => 'Handbag',
                'key' => '_tenant',
                'is_menu' => true,
                'children' => [
                    [
                        'name' => PageEnum::TENANT->label(),
                        'key' => PageEnum::TENANT->value,
                        'is_menu' => true,
                        'permission' => PageEnum::TENANT->value,
                    ]
                ]
            ],
            [
                'name' => '调试工具',
                'icon' => 'Handbag',
                'key' => '_watch',
                'is_menu' => true,
                'children' => [
                    [
                        'name' => PageEnum::DOCS->label(),
                        'key' => PageEnum::DOCS->value,
                        'is_menu' => true,
                        'permission' => PageEnum::DOCS->value,
                    ],
                    [
                        'name' => PageEnum::TELESCOPE->label(),
                        'key' => PageEnum::TELESCOPE->value,
                        'is_menu' => true,
                        'permission' => PageEnum::TELESCOPE->value,
                    ],
                    [
                        'name' => PageEnum::HORIZON->label(),
                        'key' => PageEnum::HORIZON->value,
                        'is_menu' => true,
                        'permission' => PageEnum::HORIZON->value,
                    ],
                    [
                        'name' => PageEnum::PULSE->label(),
                        'key' => PageEnum::PULSE->value,
                        'is_menu' => true,
                        'permission' => PageEnum::PULSE->value,
                    ],
                ]
            ]
        ];
    }

}