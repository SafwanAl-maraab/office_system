<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | مدير عام
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::firstOrCreate([
            'name' => 'مدير عام',
            'guard_name' => 'web',
        ]);

        $superAdmin->syncPermissions(
            Permission::all()
        );

        /*
        |--------------------------------------------------------------------------
        | مدير فرع
        |--------------------------------------------------------------------------
        */

        $branchManager = Role::firstOrCreate([
            'name' => 'مدير فرع',
            'guard_name' => 'web',
        ]);

        $branchManager->syncPermissions([

            'view.dashboard',

            'view.clients',
            'create.clients',
            'update.clients',
            'statement.clients',

            'view.requests',
            'create.requests',
            'update.requests',
            'change-status.requests',

            'view.bookings',
            'create.bookings',
            'update.bookings',
            'change-status.bookings',
            'payment.bookings',

            'view.visas',
            'create.visas',
            'update.visas',
            'change-status.visas',
            'payment.visas',

            'view.trips',

            'view.trip-groups',
            'create.trip-groups',
            'attach-bus.trip-groups',

            'view.drivers',
            'create.drivers',
            'update.drivers',

            'view.buses',
            'create.buses',
            'update.buses',

            'view.bus-assignments',
            'create.bus-assignments',

            'view.payments',
            'create.payments',

            'view.invoices',

            'view.expenses',
            'create.expenses',

            'view.incomes',
            'create.incomes',

            'view.cashboxes',

            'view.exchange-rates',

            'view.agents',

            'view.financial-reports',
            'view.profit-analysis',

        ]);

        /*
        |--------------------------------------------------------------------------
        | محاسب
        |--------------------------------------------------------------------------
        */

        $accountant = Role::firstOrCreate([
            'name' => 'محاسب',
            'guard_name' => 'web',
        ]);

        $accountant->syncPermissions([

            'view.dashboard',

            'view.payments',
            'create.payments',

            'view.expenses',
            'create.expenses',

            'view.incomes',
            'create.incomes',

            'view.cashboxes',
            'create.cashboxes',
            'update.cashboxes',
            'transactions.cashboxes',

            'view.cashbox-exchanges',
            'create.cashbox-exchanges',

            'view.exchange-rates',
            'create.exchange-rates',
            'update.exchange-rates',

            'view.client-vouchers',
            'create.client-vouchers',

            'view.voucher-settlements',
            'create.voucher-settlements',

            'view.invoices',
            'refund.invoices',
            'pdf.invoices',

            'view.financial-reports',
            'export.financial-reports',

            'view.profit-analysis',
            'export.profit-analysis',

        ]);

        /*
        |--------------------------------------------------------------------------
        | موظف حجوزات
        |--------------------------------------------------------------------------
        */

        $bookingEmployee = Role::firstOrCreate([
            'name' => 'موظف حجوزات',
            'guard_name' => 'web',
        ]);

        $bookingEmployee->syncPermissions([

            'view.dashboard',

            'view.clients',
            'create.clients',
            'update.clients',

            'view.bookings',
            'create.bookings',
            'update.bookings',
            'payment.bookings',

            'view.trips',

            'view.trip-groups',

        ]);

        /*
        |--------------------------------------------------------------------------
        | موظف تأشيرات
        |--------------------------------------------------------------------------
        */

        $visaEmployee = Role::firstOrCreate([
            'name' => 'موظف تأشيرات',
            'guard_name' => 'web',
        ]);

        $visaEmployee->syncPermissions([

            'view.dashboard',

            'view.clients',
            'create.clients',

            'view.visas',
            'create.visas',
            'update.visas',
            'change-status.visas',

            'attach-trip-group.visas',
            'attach-package.visas',

            'payment.visas',

            'view.visa-types',

            'view.trip-groups',

        ]);

        /*
        |--------------------------------------------------------------------------
        | موظف استقبال
        |--------------------------------------------------------------------------
        */

        $reception = Role::firstOrCreate([
            'name' => 'موظف استقبال',
            'guard_name' => 'web',
        ]);

        $reception->syncPermissions([

            'view.dashboard',

            'view.clients',
            'create.clients',

            'view.bookings',

            'view.visas',

            'view.trip-groups',

        ]);
    }
}
