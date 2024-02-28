<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Listing
 *
 * @mixin Builder
 * @property int $id
 * @property string $title
 * @property int $category_id
 * @property int $user_id
 * @property string|null $slug
 * @property string $description
 * @property string $condition
 * @property string $price
 * @property string $email
 * @property string $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Image> $images
 * @property-read int|null $images_count
 * @property-read \App\Models\User $user
 * @method static Builder|Listing filter(array $filters)
 * @method static Builder|Listing findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static Builder|Listing newModelQuery()
 * @method static Builder|Listing newQuery()
 * @method static Builder|Listing query()
 * @method static Builder|Listing whereCategoryId($value)
 * @method static Builder|Listing whereCondition($value)
 * @method static Builder|Listing whereCreatedAt($value)
 * @method static Builder|Listing whereDescription($value)
 * @method static Builder|Listing whereEmail($value)
 * @method static Builder|Listing whereId($value)
 * @method static Builder|Listing wherePhone($value)
 * @method static Builder|Listing wherePrice($value)
 * @method static Builder|Listing whereSlug($value)
 * @method static Builder|Listing whereTitle($value)
 * @method static Builder|Listing whereUpdatedAt($value)
 * @method static Builder|Listing whereUserId($value)
 * @method static Builder|Listing withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 * @mixin \Eloquent
 */
class Listing extends Model
{
//    use HasFactory;
    use Sluggable;


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    protected $guarded = [
        'id'
    ];

    public function scopeFilter($query, array $filters)
    {
        if ($filters['search'] ?? false) {
            $query->where('title', 'like', '%' . \request('search') . '%');
        }
        if ($filters['category'] ?? false) {
            $query->whereExists(function ($query) {
                $query->from('categories')
                    ->whereColumn('categories.id', 'listings.category_id')
                    ->where('categories.slug', request('category'));
            });
        }
    }

    public function thumbnail(): Attribute
    {
        return Attribute::make(
            get: fn() => 'storage/files/' . $this->images->pluck('image')->first(),
        );
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }


}
