<?php

namespace App\Models;

use App\Traits\HasLogo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory,
        HasLogo;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'website',
        'logo'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'logo_url',
    ];

    public function persons()
    {
        return $this->hasMany(User::class, 'organization_id', 'id');
    }}
