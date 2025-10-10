<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'seller']);
    }

    public function view(User $user, Property $property)
    {
        return $user->isAdmin() || $user->id === $property->seller_id;
    }

    public function create(User $user)
    {
        return $user->isSeller();
    }

    public function update(User $user, Property $property)
    {
        return $user->role === 'admin' || $user->id === $property->seller_id;
    }

    public function delete(User $user, Property $property)
    {
        return $user->role === 'admin' || $user->id === $property->seller_id;
    }

    /**
     * Determine whether the user can conduct raffle for the property.
     */
    public function conductRaffle(User $user, Property $property)
    {
        // Only admin can conduct raffles
        if ($user->role !== 'admin') {
            return false;
        }

        // Property must be active
        if ($property->status !== 'active') {
            return false;
        }

        // Property must not have existing raffle
        if ($property->raffles()->exists()) {
            return false;
        }

        // Property must have at least one coupon sold
        if ($property->coupons()->count() === 0) {
            return false;
        }

        return true;
    }
}
