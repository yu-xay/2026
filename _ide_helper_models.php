<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $attachable_type
 * @property int|null $attachable_id
 * @property string $mime_type
 * @property string $url
 * @property int|null $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $imageable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereAttachableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereAttachableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment withoutTrashed()
 */
	class Attachment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $domain
 * @property string $tenant_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tenant $tenant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Domain newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Domain newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Domain query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Domain whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Domain whereDomain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Domain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Domain whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Domain whereUpdatedAt($value)
 */
	class Domain extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $sub_title
 * @property int $category_id
 * @property string|null $description
 * @property int $status
 * @property string|null $config1
 * @property string|null $config2
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods whereConfig1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods whereConfig2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods whereSubTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Goods withoutTrashed()
 */
	class Goods extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsCat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsCat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsCat onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsCat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsCat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsCat whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsCat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsCat whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsCat whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsCat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsCat withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsCat withoutTrashed()
 */
	class GoodsCat extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $goods_id
 * @property int $sku_code
 * @property float $price
 * @property float $cost_price
 * @property int $stock
 * @property int $weight
 * @property int $volume
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku whereCostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku whereSkuCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku whereVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSku whereWeight($value)
 */
	class GoodsSku extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $goods_id
 * @property string $spec_key_name
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecKey query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecKey whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecKey whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecKey whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecKey whereSpecKeyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecKey whereUpdatedAt($value)
 */
	class GoodsSpecKey extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $goods_id
 * @property int $spec_key_id
 * @property string $spec_value
 * @property int $spec_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecValue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecValue whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecValue whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecValue whereSpecKeyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecValue whereSpecOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecValue whereSpecValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GoodsSpecValue whereUpdatedAt($value)
 */
	class GoodsSpecValue extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $orderId
 * @property string $uuid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUuid($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array<array-key, mixed>|null $data
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Stancl\Tenancy\Database\Models\Domain> $domains
 * @property-read int|null $domains_count
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> all($columns = ['*'])
 * @method static \Stancl\Tenancy\Database\TenantCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant whereUpdatedAt($value)
 */
	class Tenant extends \Eloquent implements \Stancl\Tenancy\Contracts\TenantWithDatabase {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Attachment|null $avatar
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

