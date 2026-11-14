<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * SRS 5.2 default roles. Permission name = `module.resource.action`.
 * Super Admin gets everything; the rest are explicit allow-lists so a
 * newly added permission is NOT auto-granted to Manager/etc.
 */
class RoleAndPermissionSeeder extends Seeder
{
    protected array $resources = [
        'catalog.category' => ['view', 'create', 'update', 'delete'],
        'catalog.product' => ['view', 'create', 'update', 'delete'],
        'catalog.attribute' => ['view', 'create', 'update', 'delete'],
        'inventory.item' => ['view', 'adjust'],
        'orders.order' => ['view', 'create', 'update', 'update_status', 'print'],
        'orders.return' => ['view', 'approve'],
        'payments.payment' => ['view', 'refund'],
        'shipping.shipment' => ['view', 'book'],
        'customers.customer' => ['view', 'update', 'block'],
        'marketing.coupon' => ['view', 'create', 'update', 'delete'],
        'marketing.review' => ['view', 'moderate'],
        'cms.page' => ['view', 'create', 'update', 'delete'],
        'reports.report' => ['view'],
        'settings.setting' => ['view', 'update'],
        'roles.role' => ['view', 'manage'],
        'risk.rule' => ['view', 'manage'],
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $allPermissions = [];
        foreach ($this->resources as $resource => $actions) {
            foreach ($actions as $action) {
                $allPermissions[] = Permission::findOrCreate("{$resource}.{$action}");
            }
        }

        $superAdmin = Role::findOrCreate('Super Admin');
        $superAdmin->syncPermissions($allPermissions);

        Role::findOrCreate('Manager')->syncPermissions(
            collect($allPermissions)->reject(fn (Permission $p) => $p->name === 'roles.role.manage' || str_starts_with($p->name, 'settings.'))->all()
        );

        Role::findOrCreate('Order Handler')->syncPermissions([
            'orders.order.view', 'orders.order.update_status', 'orders.order.print',
            'orders.return.view', 'orders.return.approve',
            'shipping.shipment.view', 'shipping.shipment.book',
            'customers.customer.view',
            'inventory.item.view',
        ]);

        Role::findOrCreate('Content Editor')->syncPermissions([
            'catalog.category.view', 'catalog.category.create', 'catalog.category.update',
            'catalog.product.view', 'catalog.product.create', 'catalog.product.update',
            'cms.page.view', 'cms.page.create', 'cms.page.update', 'cms.page.delete',
            'marketing.review.view', 'marketing.review.moderate',
        ]);
    }
}
