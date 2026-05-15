<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TenantRequest;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\RouteAttributes\Attributes\ApiResource;
use Spatie\RouteAttributes\Attributes\Middleware;

#[ApiResource(resource: 'tenant', except: ['show'])]
#[Middleware(['permission:tenant'])]
class TenantController extends R
{
    public function index(TenantRequest $request): JsonResponse
    {
        $tenant = Tenant::keyword(['id'])->with('domains')->page();
        $response = $this->toArray($tenant);

        $result = array_map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
                'tenancy_db_name' => $item->tenancy_db_name,
                'domain' => request()->getScheme() . '://' . $item->domains->first()?->domain,
            ];
        }, $response['data']);
        return $this->success($result, $response['paginate']);
    }

    /**
     * @throws \Throwable
     */
    public function store(TenantRequest $request): JsonResponse
    {
        $form = $request->validated();
        $tenant = Tenant::create([
            'id' => $form['id'],
            'name' => $form['name'],
        ]);
        $host = parse_url(config('app.url'), PHP_URL_HOST);

        $tenant->domains()->create([
            'domain' => $tenant['id'] . '.' . $host,
        ]);

        return $this->success([
            'message' => self::MESSAGE['STORE']
        ]);
    }

    public function update(TenantRequest $request, Tenant $tenant): JsonResponse
    {
        $form = $request->validated();
        $tenant->update([
            'name' => $form['name'],
        ]);

        return $this->success([
            'message' => self::MESSAGE['UPDATE']
        ]);
    }

    public function destroy(TenantRequest $request, Tenant $tenant): JsonResponse
    {
        $tenant->delete();

        return $this->success([
            'message' => self::MESSAGE['DESTROY']
        ]);
    }
}
