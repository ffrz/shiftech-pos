<?php

namespace App\Models;

class AclResource
{
    // menus
    const SYSTEM_MENU = 'system-menu';
    const PURCHASING_MENU = 'purchasing-menu';
    const SALES_MENU = 'sales-menu';
    const INVENTORY_MENU = 'inventory-menu';
    const SERVICE_MENU = 'service-menu';
    const REPORT_MENU = 'report-menu';
    const EXPENSE_MENU = 'cost-menu';
    const FINANCE_MENU = 'finance-menu';
    const DEBT_MENU = 'debt-menu';
    const EXECUTIVE_DASHBOARD = 'executive-dashboard';

    // system
    const VIEW_REPORTS = 'view-reports'; // sementara digrup, mungkin kedepannya diset spesifik

    const USER_ACTIVITY = 'user-activity';
    const USER_MANAGEMENT = 'user-management';
    const USER_GROUP_MANAGEMENT = 'user-group-management';
    const SETTINGS = 'settings';

    const CASH_TRANSACTION_CATEGORY_MANAGEMENT = 'cash-transaction-category-management';

    const CASH_ACCOUNT_LIST = 'cash-account-list';
    const ADD_CASH_ACCOUNT = 'add-cash-account';
    const EDIT_CASH_ACCOUNT = 'edit-cash-account';
    const DELETE_CASH_ACCOUNT = 'delete-cash-account';

    const CASH_TRANSACTION_LIST = 'cash-transaction-list';
    const ADD_CASH_TRANSACTION = 'add-cash-transaction';
    const EDIT_CASH_TRANSACTION = 'edit-cash-transaction';
    const DELETE_CASH_TRANSACTION = 'delete-cash-transaction';

    const SERVICE_ORDER_LIST = 'service-order-list';
    const ADD_SERVICE_ORDER = 'add-service-order';
    const EDIT_SERVICE_ORDER = 'edit-service-order';
    const DELETE_SERVICE_ORDER = 'delete-service-order';

    const PRODUCT_CATEGORY_MANAGEMENT = 'product-category-management';

    const EXPENSE_CATEGORY_MANAGEMENT = 'expense-category-management';
    const EXPENSE_MANAGEMENT = 'expense-management';

    const PRODUCT_LIST = 'product-list';
    const ADD_PRODUCT = 'add-product';
    const EDIT_PRODUCT = 'edit-product';
    const DELETE_PRODUCT = 'delete-product';

    CONST MANAGE_STOCK_ADJUSTMENT = 'manage-stock-adjustment';
    CONST VIEW_STOCK_UPDATE_HISTORY = 'view-stock-update-history';

    const SUPPLIER_LIST = 'supplier-list';
    const ADD_SUPPLIER = 'add-supplier';
    const EDIT_SUPPLIER = 'edit-supplier';
    const DELETE_SUPPLIER = 'delete-supplier';

    const PURCHASE_ORDER_LIST = 'purchase-order-list';
    const ADD_PURCHASE_ORDER = 'add-purchase-order';
    const EDIT_PURCHASE_ORDER = 'edit-purchase-order';
    const DELETE_PURCHASE_ORDER = 'delete-purchase-order';

    const CUSTOMER_LIST = 'customer-list';
    const ADD_CUSTOMER = 'add-customer';
    const EDIT_CUSTOMER = 'edit-customer';
    const DELETE_CUSTOMER = 'delete-customer';

    const SALES_ORDER_LIST = 'sales-order-list';
    const ADD_SALES_ORDER = 'add-sales-order';
    const EDIT_SALES_ORDER = 'edit-sales-order';
    const DELETE_SALES_ORDER = 'delete-sales-order';

    public static function all()
    {
        return [
            'Dashboard' => [
                self::EXECUTIVE_DASHBOARD => 'Dashboard Eksekutif',
            ],
            'Penjualan' => [
                self::SALES_MENU => 'Menu penjualan',
                'Order Penjualan' => [
                    self::SALES_ORDER_LIST => 'Lihat',
                    self::ADD_SALES_ORDER => 'Tambah',
                    self::EDIT_SALES_ORDER => 'Ubah',
                    self::DELETE_SALES_ORDER => 'Hapus',
                ],
                'Pelanggan' => [
                    self::CUSTOMER_LIST => 'Lihat',
                    self::ADD_CUSTOMER => 'Tambah',
                    self::EDIT_CUSTOMER => 'Ubah',
                    self::DELETE_CUSTOMER => 'Hapus',
                ],
            ],
            'Servis' => [
                'Order Servis' => [
                    self::SERVICE_ORDER_LIST => 'Lihat',
                    self::ADD_SERVICE_ORDER => 'Tambah',
                    self::EDIT_SERVICE_ORDER => 'Ubah',
                    self::DELETE_SALES_ORDER => 'Hapus',
                ],
            ],
            'Inventori' => [
                self::INVENTORY_MENU => 'Menu Inventory',
                self::PRODUCT_CATEGORY_MANAGEMENT => 'Kelola Kategori produk',
                self::MANAGE_STOCK_ADJUSTMENT => 'Kelola Stok Opname',
                self::VIEW_STOCK_UPDATE_HISTORY => 'Lihat Riwayat Stok',
                'Produk' => [
                    self::PRODUCT_LIST => 'Lihat',
                    self::ADD_PRODUCT => 'Tambah',
                    self::EDIT_PRODUCT => 'Ubah',
                    self::DELETE_PRODUCT => 'Hapus',
                ],
            ],
            'Pembelian' => [
                self::PURCHASING_MENU => 'Menu pembelian',
                'Order Pembelian' => [
                    self::PURCHASE_ORDER_LIST => 'Lihat',
                    self::ADD_PURCHASE_ORDER => 'Tambah',
                    self::EDIT_PURCHASE_ORDER => 'Ubah',
                    self::DELETE_PURCHASE_ORDER => 'Hapus',
                ],
                'Pemasok' => [
                    self::SUPPLIER_LIST => 'Lihat',
                    self::ADD_SUPPLIER => 'Tambah',
                    self::EDIT_SUPPLIER => 'Ubah',
                    self::DELETE_SUPPLIER => 'Hapus',
                ]
            ],
            'Keuangan' => [
                self::FINANCE_MENU => 'Menu Keuangan',
                self::CASH_TRANSACTION_CATEGORY_MANAGEMENT => 'Kelola Kategori Transaksi',
                'Transaksi' => [
                    self::CASH_TRANSACTION_LIST => 'Lihat',
                    self::ADD_CASH_TRANSACTION => 'Tambah',
                    self::EDIT_CASH_TRANSACTION => 'Ubah',
                    self::DELETE_CASH_TRANSACTION => 'Hapus',
                ],
                'Akun / Rekening' => [
                    self::CASH_ACCOUNT_LIST => 'Lihat',
                    self::ADD_CASH_ACCOUNT => 'Tambah',
                    self::EDIT_CASH_ACCOUNT => 'Ubah',
                    self::DELETE_CASH_ACCOUNT => 'Hapus',
                ],
            ],
            'Utang Piutang' => [
                self::DEBT_MENU => 'Menu Utang / Piutang',
            ],
            'Sistem' => [
                self::SYSTEM_MENU => 'Menu sistem',
                self::USER_ACTIVITY => 'Aktifitas Pengguna',
                self::USER_MANAGEMENT => 'Pengguna',
                self::USER_GROUP_MANAGEMENT => 'Grup pengguna',
                self::SETTINGS => 'Pengaturan',
            ]
        ];
    }
}
