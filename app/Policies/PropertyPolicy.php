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
        return $user->role === 'admin' || $user->id === $property->seller_id;
    }

    public function create(User $user)
    {
        return $user->role === 'seller';
    }

    public function update(User $user, Property $property)
    {
        return $user->role === 'admin' || $user->id === $property->seller_id;
    }

    public function delete(User $user, Property $property)
    {
        return $user->role === 'admin' || $user->id === $property->seller_id;
    }
}
