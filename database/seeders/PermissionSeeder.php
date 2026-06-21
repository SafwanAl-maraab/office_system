<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();

        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */
            'view.dashboard',

            /*
            |--------------------------------------------------------------------------
            | Roles
            |--------------------------------------------------------------------------
            */
            'view.roles',
            'create.roles',
            'update.roles',
            'delete.roles',

            /*
            |--------------------------------------------------------------------------
            | Settings
            |--------------------------------------------------------------------------
            */
            'view.settings',
            'update.settings',

            /*
            |--------------------------------------------------------------------------
            | Users
            |--------------------------------------------------------------------------
            */
            'view.users',
            'create.users',
            'update.users',
            'delete.users',

            /*
            |--------------------------------------------------------------------------
            | Employees
            |--------------------------------------------------------------------------
            */
            'view.employees',
            'create.employees',
            'update.employees',
            'delete.employees',

            /*
            |--------------------------------------------------------------------------
            | Clients
            |--------------------------------------------------------------------------
            */
            'view.clients',
            'create.clients',
            'update.clients',
            'delete.clients',
            'statement.clients',

            /*
            |--------------------------------------------------------------------------
            | Request Types
            |--------------------------------------------------------------------------
            */
            'view.request-types',
            'create.request-types',
            'update.request-types',
            'delete.request-types',

            /*
            |--------------------------------------------------------------------------
            | Requests
            |--------------------------------------------------------------------------
            */
            'view.requests',
            'create.requests',
            'update.requests',
            'delete.requests',
            'change-status.requests',
            'attach-travel.requests',

            /*
            |--------------------------------------------------------------------------
            | Travels
            |--------------------------------------------------------------------------
            */
            'view.travels',
            'create.travels',
            'update.travels',
            'delete.travels',

            /*
            |--------------------------------------------------------------------------
            | Trips
            |--------------------------------------------------------------------------
            */
            'view.trips',
            'create.trips',
            'update.trips',
            'delete.trips',

            /*
            |--------------------------------------------------------------------------
            | Bookings
            |--------------------------------------------------------------------------
            */
            'view.bookings',
            'create.bookings',
            'update.bookings',
            'delete.bookings',
            'change-status.bookings',
            'payment.bookings',

            /*
            |--------------------------------------------------------------------------
            | Invoices
            |--------------------------------------------------------------------------
            */
            'view.invoices',
            'refund.invoices',
            'cancel.invoices',
            'pdf.invoices',

            /*
            |--------------------------------------------------------------------------
            | Payments
            |--------------------------------------------------------------------------
            */
            'view.payments',
            'create.payments',
            'delete.payments',

            /*
            |--------------------------------------------------------------------------
            | Expenses
            |--------------------------------------------------------------------------
            */
            'view.expenses',
            'create.expenses',

            /*
            |--------------------------------------------------------------------------
            | Incomes
            |--------------------------------------------------------------------------
            */
            'view.incomes',
            'create.incomes',

            /*
            |--------------------------------------------------------------------------
            | Cashboxes
            |--------------------------------------------------------------------------
            */
            'view.cashboxes',
            'create.cashboxes',
            'update.cashboxes',
            'transactions.cashboxes',

            /*
            |--------------------------------------------------------------------------
            | Cashbox Exchanges
            |--------------------------------------------------------------------------
            */
            'view.cashbox-exchanges',
            'create.cashbox-exchanges',
            'reverse.cashbox-exchanges',

            /*
            |--------------------------------------------------------------------------
            | Exchange Rates
            |--------------------------------------------------------------------------
            */
            'view.exchange-rates',
            'create.exchange-rates',
            'update.exchange-rates',

            /*
            |--------------------------------------------------------------------------
            | Client Vouchers
            |--------------------------------------------------------------------------
            */
            'view.client-vouchers',
            'create.client-vouchers',

            /*
            |--------------------------------------------------------------------------
            | Voucher Settlements
            |--------------------------------------------------------------------------
            */
            'view.voucher-settlements',
            'create.voucher-settlements',

            /*
            |--------------------------------------------------------------------------
            | Drivers
            |--------------------------------------------------------------------------
            */
            'view.drivers',
            'create.drivers',
            'update.drivers',
            'delete.drivers',

            /*
            |--------------------------------------------------------------------------
            | Buses
            |--------------------------------------------------------------------------
            */
            'view.buses',
            'create.buses',
            'update.buses',
            'delete.buses',

            /*
            |--------------------------------------------------------------------------
            | Bus Assignments
            |--------------------------------------------------------------------------
            */
            'view.bus-assignments',
            'create.bus-assignments',
            'update.bus-assignments',
            'delete.bus-assignments',

            /*
            |--------------------------------------------------------------------------
            | Visas
            |--------------------------------------------------------------------------
            */
            'view.visas',
            'create.visas',
            'update.visas',
            'delete.visas',
            'change-status.visas',
            'attach-trip-group.visas',
            'attach-package.visas',
            'payment.visas',

            /*
            |--------------------------------------------------------------------------
            | Visa Types
            |--------------------------------------------------------------------------
            */
            'view.visa-types',
            'create.visa-types',
            'update.visa-types',
            'delete.visa-types',

            /*
            |--------------------------------------------------------------------------
            | Trip Groups
            |--------------------------------------------------------------------------
            */
            'view.trip-groups',
            'create.trip-groups',
            'update.trip-groups',
            'delete.trip-groups',
            'attach-bus.trip-groups',

            /*
            |--------------------------------------------------------------------------
            | Agents
            |--------------------------------------------------------------------------
            */
            'view.agents',
            'create.agents',
            'update.agents',
            'delete.agents',
            'payment.agents',
            'statement.agents',
            'export.agents',

            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */
            'view.financial-reports',
            'export.financial-reports',

            'view.profit-analysis',
            'export.profit-analysis',

            /*
            |--------------------------------------------------------------------------
            | Profile
            |--------------------------------------------------------------------------
            */
            'view.profile',
            'update.profile',
            'delete.profile',
        ];

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name'       => $permission,
                'guard_name' => 'web',
            ]);

        }
    }
}
