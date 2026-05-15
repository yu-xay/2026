<?php

namespace App\Http\Controllers\Common;

use App\Http\Requests\Admin\UserRequest;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\RouteAttributes\Attributes\ApiResource;
use Spatie\RouteAttributes\Attributes\Middleware;
use Throwable;

#[ApiResource(resource: 'user')]
#[Middleware(['permission:user'])]
class UserController extends R
{
    public function index(UserRequest $request): JsonResponse
    {
        $userModel = User::with(['roles', 'avatar'])->keyword('name')->page();
        $response = $this->toArray($userModel);

        $result = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'avatar' => $item->avatar?->url,
                'name' => $item['name'],
                'email' => $item['email'],
                'roles' => $item['roles']->pluck('name')->join(','),
                'created_at' => $item['created_at']
            ];
        }, $response['data']);

        return $this->success($result, $response['paginate']);
    }

    /**
     * @throws Throwable
     */
    public function store(UserRequest $request): JsonResponse
    {
        $form = $request->validated();
        DB::transaction(function () use ($form) {
            $role = Role::findById($form['role_id']);
            $user = User::create([
                'name' => $form['name'],
                'email' => $form['email'],
                'password' => Hash::make($form['password'])
            ]);
            $user->assignRole($role);
            Attachment::saveOne($form['attachment_id'], $user);
        });

        return $this->success([
            'message' => self::MESSAGE['STORE']
        ]);
    }

    public function show(UserRequest $request, User $user): JsonResponse
    {
        return $this->success([
            'avatar' => $user->avatar?->url,
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->roles->first()?->id,
            'attachment_id' => $user->avatar?->id,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $form = $request->validated();
        DB::transaction(function () use ($form, $user) {
            $role = Role::findById($form['role_id']);
            $user->syncRoles($role);

            $user->name = $form['name'];
            $user->email = $form['email'];
            if (isset($form['password']) && $form['password']) {
                $user->password = Hash::make($form['password']);
            }
            $user->save();

            Attachment::saveOne($form['attachment_id'], $user);
        });

        return $this->success([
            'message' => self::MESSAGE['UPDATE']
        ]);
    }

    public function destroy(UserRequest $request, User $user): JsonResponse
    {
        if ($user->id == 1) {
            abort(403, '禁止修改管理员');
        }
        $user->delete();

        return $this->success([
            'message' => self::MESSAGE['DESTROY']
        ]);
    }
}
