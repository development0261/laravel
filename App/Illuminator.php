<?php

declare(strict_types=1);

namespace App\Domains\Illuminator;

use App\Core\Eloquent\ModelExtensions;
use App\Domains\Agreement\Agreement;
use App\Domains\Auth\HasApiAccess;
use App\Domains\Auth\Tokenable;
use App\Domains\Auth\User;
use App\Domains\Core\Address;
use App\Domains\Unit\Unit;
use Eloquence\Behaviours\CamelCasing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Illuminator extends Model implements Tokenable
{
    use CamelCasing, SoftDeletes, HasApiAccess, ModelExtensions;

    protected $with = ['contactAddress'];

    protected $withCount = ['units'];

    protected $casts = [
        'primary_account_manager_id' => 'int',
        'contact_address_id' => 'int',
        'agreement_id' => 'int',
        'revision_request_id' => 'int',
        'revision_request_for' => 'int',
    ];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function contactAddress()
    {
        return $this->belongsTo(Address::class, 'contact_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function technicalAddress()
    {
        return $this->belongsTo(Address::class, 'technical_address_id');
    }

    public function primaryAccountManager()
    {
        return $this->belongsTo(User::class, 'primary_account_manager_id');
    }

    public function accountManagers()
    {
        return $this->belongsToMany(User::class, 'illuminator_account_managers', 'illuminator_id', 'account_manager_id')
            ->using(IlluminatorAccountManager::class);
    }

    public function agreement()
    {
        return $this->morphOne(Agreement::class, 'owner');
    }

    public function isManagedBy(User $user)
    {
        return $this->primaryAccountManagerId === $user->getKey() ||
            $this->accountManagers->contains('id', '=', $user->getKey());
    }
}
