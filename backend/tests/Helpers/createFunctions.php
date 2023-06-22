<?php

use App\Models\Ability;
use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

if (!function_exists('createProduct')) {
    function createProduct(
        array $fields = [],
        int $count = null,
    ): Product|Collection {
        $factory = Product::factory();

        if ($count) {
            $factory = $factory->count($count);
        }

        return $factory->create($fields);
    }
}

if (!function_exists('createOrderItem')) {
    function createOrderItem(
        array $fields = [],
        int $count = null,
    ): OrderItem|Collection {
        $factory = OrderItem::factory();

        if ($count) {
            $factory = $factory->count($count);
        }

        return $factory->create($fields);
    }
}

if (!function_exists('createOrder')) {
    function createOrder(
        array $fields = [],
        int $count = null,
    ): Order|Collection {
        $factory = Order::factory();

        if ($count) {
            $factory = $factory->count($count);
        }

        return $factory->create($fields);
    }
}

if (!function_exists('createAbility')) {
    function createAbility(
        array $fields = [],
        int $count = null,
    ): Ability|Collection {
        $factory = Ability::factory();

        if ($count) {
            $factory = $factory->count($count);
        }

        return $factory->create($fields);
    }
}

if (!function_exists('createRole')) {
    function createRole(
        array $fields = [],
        int $count = null,
    ): Role|Collection {
        $factory = Role::factory();

        if ($count) {
            $factory = $factory->count($count);
        }

        return $factory->create($fields);
    }
}

if (!function_exists('createCurrency')) {
    function createCurrency(
        array $fields = [],
        int $count = null,
    ): Currency|Collection {
        $factory = Currency::factory();

        if ($count) {
            $factory = $factory->count($count);
        }

        return $factory->create($fields);
    }
}

if (!function_exists('createUser')) {
    function createUser(
        array $fields = [],
        int $count = null,
    ): User|Collection {
        $factory = User::factory();

        if ($count) {
            $factory = $factory->count($count);
        }

        return $factory->create($fields);
    }
}
