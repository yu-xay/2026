<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Resource;
use OpenApi\Attributes as OA;

#[Resource(resource: 'role')]
#[Middleware(['permission:role'])]
class RoleController extends R
{
    #[OA\Get(path: '/role', responses: [new OA\Response(response: 200, description: 'Success')])]
    public function index(RoleRequest $request): JsonResponse
    {
        $roleModel = Role::withCount('users')->keyword('name')->page();
        $response = $this->toArray($roleModel);

        return $this->success($response['data'], $response['paginate']);
    }

    /**
     * @throws \Throwable
     */
    #[OA\Post(path: '/role', responses: [new OA\Response(response: 200, description: 'Success')])]
    public function store(RoleRequest $request): JsonResponse
    {
        $form = $request->validated();

        DB::transaction(function () use ($form) {
            $role = Role::create(['name' => $form['name']]);
            $role->givePermissionTo($form['permissions']);
        });

        return $this->success([
            'message' => self::MESSAGE['STORE']
        ]);
    }

    #[OA\Get(path: '/role/{id}', responses: [new OA\Response(response: 200, description: 'Success')])]
    public function show(RoleRequest $request, Role $role): JsonResponse
    {
        $permissions = $role->permissions->pluck('id');
        return $this->success(['name' => $role->name, 'permissions' => $permissions]);
    }

    #[OA\Put(path: '/role/{id}', responses: [new OA\Response(response: 200, description: 'Success')])]
    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        if ($role->id == 1) {
            abort(403, '禁止修改管理员');
        }

        $form = $request->validated();
        $role->name = $form['name'];
        $role->syncPermissions($form['permissions']);
        $role->save();

        return $this->success([
            'message' => self::MESSAGE['UPDATE']
        ]);
    }

    #[OA\Delete(path: '/role/{id}', responses: [new OA\Response(response: 200, description: 'Success')])]
    public function destroy(RoleRequest $request, Role $role): JsonResponse
    {
        if ($role->id == 1) {
            abort(403, '禁止修改管理员');
        }
        $role->delete();

        return $this->success([
            'message' => self::MESSAGE['DESTROY']
        ]);
    }
}
