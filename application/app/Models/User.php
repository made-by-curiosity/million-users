<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
    ];

    public function address(): HasOne
    {
        return $this->hasOne(UserAddress::class);
    }

    public function scopeSearch(Builder $query, $searchText): Builder
    {
        return $query->when($searchText, function($query, $searchText) {
                $query->select('*')
                    ->selectRaw("MATCH(first_name, last_name, email) AGAINST(? IN BOOLEAN MODE) AS relevance", [$searchText . '*'])
                    ->whereRaw("MATCH(first_name, last_name, email) AGAINST(? IN BOOLEAN MODE)", [$searchText . '*'])
                    ->orderByDesc('relevance');
            });
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => 
                Carbon::parse($attributes['updated_at'])
                    ->format('M d, Y H:i'),
        );
    }
}
