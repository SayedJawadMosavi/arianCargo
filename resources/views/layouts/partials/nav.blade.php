<div class="main-sidemenu">
    <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg"
            fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
            <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
        </svg></div>
    <ul class="side-menu">
        <li class="sub-category d-none">
            <h3>Home </h3>
        </li>
        <li class="slide">
            <a class="side-menu__item has-link" data-bs-toggle="slide" href="{{route('dashboard')}}"><i
                    class="side-menu__icon fe fe-home"></i><span
                    class="side-menu__label">{{__('home.home')}} </span></a>
        </li>
        <li class="slide">
            <a class="side-menu__item has-link" data-bs-toggle="slide" href="{{route('journal.get')}}"><i
                    class="side-menu__icon fe fe-book"></i><span
                    class="side-menu__label">{{__('home.journal')}} </span></a>
        </li>
        @canany(['product.create', 'product.view', 'category.create', 'category.view' ])

        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-grid"></i><span class="side-menu__label">{{__('home.products')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @canany(['product.view', 'product.create', 'product.edit'])
                <li><a href="{{route('product.index')}}" class="slide-item"> {{__('home.view_products')}}</a></li>
                @endcanany
                <li><a href="{{route('product.min_stock')}}" class="slide-item"> {{__('home.min_stock')}}</a></li>
                @canany(['category.view', 'category.create', 'category.edit'])
                <li><a href="{{route('category.index')}}" class="slide-item"> {{__('home.view_categories')}}</a></li>
                @endcanany
            </ul>
        </li>
        @endcanany

        @canany(['purchase.create', 'purchase.view', 'purchase.edit', 'purchase.delete', 'sell.create', 'sell.view', 'sell.edit', 'sell.delete','sell_return.create', 'sell_return.view', 'sell_return.edit', 'sell_return.delete'  ])
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-shopping-cart"></i><span class="side-menu__label">{{__('home.operation')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @canany(['purchase.create', 'purchase.view', 'purchase.edit', 'purchase.delete' ])
                <li><a href="{{route('purchase.index')}}" class="slide-item"> {{__('home.view_purchases')}}</a></li>
                @endcanany
                @canany(['sell.create', 'sell.view', 'sell.edit', 'sell.delete' ])
                <li><a href="{{route('sell.index')}}" class="slide-item"> {{__('home.view_sells')}}</a></li>
                @endcanany
                @canany(['sell_return.create', 'sell_return.view', 'sell_return.edit', 'sell_return.delete' ])
                <li><a href="{{route('sellreturn.index')}}" class="slide-item"> {{__('home.view_returns')}}</a></li>
                @endcanany
                <li><a href="{{route('client.receivable')}}" class="slide-item"> {{ __('home.client_receivable') }}</a></li>
                <li><a href="{{route('client.payable')}}" class="slide-item"> {{ __('home.client_payable') }} </a></li>
            </ul>
        </li>
        @endcanany

        @canany(['vendor.create', 'vendor.view', 'vendor.edit', 'vendor.delete','client.create', 'client.view', 'client.edit', 'client.delete','shareholder.create', 'shareholder.view', 'shareholder.edit', 'shareholder.delete' ])
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-users"></i><span class="side-menu__label">{{__('home.contacts')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @canany(['vendor.create', 'vendor.view', 'vendor.edit', 'vendor.delete'])
                <li><a href="{{route('vendors.index')}}" class="slide-item"> {{__('home.view_vendors')}}</a></li>
                @endcanany
                @canany(['client.create', 'client.view', 'client.edit', 'client.delete'])
                <li><a href="{{route('client.index')}}" class="slide-item"> {{__('home.view_clients')}}</a></li>
                @endcanany
                @canany(['shareholder.create', 'shareholder.view', 'shareholder.edit', 'shareholder.delete'])
                <li class=""><a href="{{route('shareholder.index')}}" class="slide-item"> {{__('home.view_shareholders')}}</a></li>
                @endcanany
            </ul>
        </li>
        @endcanany
        @canany(['account_transaction.create', 'account_transaction.view', 'account_transaction.edit', 'account_transaction.delete', 'account_transfer.create', 'account_transfer.view', 'account_transfer.edit', 'account_transfer.delete', 'account.create', 'account.view', 'account.edit', 'account.delete' ])
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-layers"></i><span class="side-menu__label">{{__('home.account')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @canany(['account.create', 'account.view', 'account.edit', 'account.delete'])
                <li><a href="{{route('account.index')}}" class="slide-item"> {{__('home.view_accounts')}}</a></li>
                @endcanany
                @canany(['account_transaction.create', 'account_transaction.view', 'account_transaction.edit', 'account_transaction.delete'])
                <li><a href="{{route('account_transaction.index')}}" class="slide-item"> {{__('home.view_accounts_transaction')}}</a></li>
                @endcanany
                @canany(['account_transfer.create', 'account_transfer.view', 'account_transfer.edit', 'account_transfer.delete'])
                <li><a href="{{route('account_transfer.index')}}" class="slide-item"> {{__('home.view_transfer')}}</a></li>
                @endcanany

            </ul>
        </li>
        @endcanany
        @canany(['expense.create', 'expense.view', 'expense.edit', 'expense_category.create', 'expense_category.view', 'expense_category.edit' ])

        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-package"></i><span class="side-menu__label">{{__('home.expenses')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @canany(['expense.create', 'expense.view', 'expense.edit', 'expense.delete'])
                <li><a href="{{route('expense.index')}}" class="slide-item"> {{__('home.expense')}}</a></li>
                <li><a href="{{route('income.index')}}" class="slide-item"> {{__('home.income')}}</a></li>
                @endcanany
                @canany(['expense_category.create', 'expense_category.view', 'expense_category.edit', 'expense_category.delete'])
                <li><a href="{{route('expense_category.index')}}" class="slide-item"> {{__('home.view_expense_category')}}</a></li>
                @endcanany
            </ul>
        </li>
        @endcanany
        @canany(['staff.create', 'staff.view', 'staff.edit', 'staff.delete', 'staff_salary.create', 'staff_salary.view', 'staff_salary.edit', 'staff_salary.delete'])

        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-user-plus"></i><span class="side-menu__label">{{__('home.staffs')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @canany(['staff.view', 'staff.create', 'staff.edit', 'staff.delete'])
                <li><a href="{{route('staff.index')}}" class="slide-item"> {{__('home.view_staff')}}</a></li>
               @endcanany
                @canany(['staff_salary.view', 'staff_salary.create', 'staff_salary.edit', 'staff_salary.delete'])
                <li><a href="{{route('staff_salary.index')}}" class="slide-item"> {{__('home.view_staff_salary')}}</a></li>
               @endcanany
            </ul>
        </li>
        @endcanany
        @canany(['branch.create', 'branch.view', 'branch.edit', 'branch.delete'])

        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-copy"></i><span class="side-menu__label">{{__('home.branch')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @canany(['branch.view', 'branch.create', 'branch.edit', 'branch.delete'])
                <li><a href="{{route('branch.index')}}" class="slide-item"> {{__('home.view_branch')}}</a></li>
               @endcanany

            </ul>
        </li>
        @endcanany
        @canany(['asset.create', 'asset.view', 'asset.edit', 'asset.delete'])

        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-box"></i><span class="side-menu__label">{{__('home.assets')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @canany(['asset.view', 'asset.create', 'asset.edit', 'asset.delete'])
                <li><a href="{{route('asset.index')}}" class="slide-item"> {{__('home.view_assets')}}</a></li>
               @endcanany
               @canany(['category.view', 'category.create', 'category.edit'])
                <li><a href="{{route('asset_category.index')}}" class="slide-item"> {{__('home.view_categories')}}</a></li>
                @endcanany

            </ul>
        </li>
        @endcanany

        @canany(['stock.create', 'stock.view', 'stock.edit', 'stock.delete'])
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-server"></i><span class="side-menu__label">{{__('home.stocks')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @canany(['stock.view', 'stock.edit', 'stock.create', 'stock.delete'])
                <li><a href="{{route('stock.index')}}" class="slide-item"> {{__('home.view_stocks')}}</a></li>
               @endcanany
               <li><a href="{{route('stock.main_stock')}}" class="slide-item"> {{__('home.main_stock')}}</a></li>
                {{-- @can('stock.view') --}}
                <li><a href="{{route('stock.productsList')}}" class="slide-item"> {{__('home.other_stocks')}}</a></li>
               {{-- @endcan --}}
            </ul>
        </li>
        @endcanany

        @canany(['main_transfer.create', 'main_transfer.view', 'main_transfer.edit', 'main_transfer.delete', 'stock_transfer.create', 'stock_transfer.view', 'stock_transfer.edit', 'stock_transfer.delete'])
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-truck"></i><span class="side-menu__label">{{__('home.transfers')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @canany(['main_transfer.view', 'main_transfer.create', 'main_transfer.edit', 'main_transfer.delete'])
                <li><a href="{{route('main_transfer.index')}}" class="slide-item"> {{__('home.main_transfers')}}</a></li>
                @endcanany
                @canany(['stock_transfer.view', 'stock_transfer.create', 'stock_transfer.edit', 'stock_transfer.delete'])
                <li><a href="{{route('stock_transfer.index')}}" class="slide-item"> {{__('home.stock_transfers')}}</a></li>
                @endcanany
            </ul>
        </li>
        @endcanany
          @canany(['document.create', 'document.view', 'document.edit', 'document.delete'])

        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-box"></i><span class="side-menu__label">{{__('home.documents')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @canany(['document.view', 'document.create', 'document.edit', 'document.delete'])
                <li><a href="{{route('document.index')}}" class="slide-item"> {{__('home.view_documents')}}</a></li>
               @endcanany
               @canany(['category.view', 'category.create', 'category.edit'])
                <li><a href="{{route('document_category.index')}}" class="slide-item"> {{__('home.view_categories')}}</a></li>
                @endcanany

            </ul>
        </li>
        @endcanany
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-slack"></i><span class="side-menu__label">{{__('home.settings')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @can('setting.view')
                <li><a href="{{route('setting.get')}}" class="slide-item"> {{__('home.settings')}}</a></li>
                @endcan
                @canany(['currency.create', 'currency.view', 'currency.edit' ])
                <li><a href="{{route('currency.index')}}" class="slide-item"> {{__('home.currencies')}}</a></li>
                @endcanany
                @canany(['unit.view', 'unit.create', 'unit.edit'])
                <li><a href="{{route('unit.index')}}" class="slide-item"> {{__('home.units')}}</a></li>
                @endcanany
                <li><a href="{{url('backups')}}" class="slide-item"> {{__('home.backup')}}</a></li>
                <li><a href="{{url('rate')}}" class="slide-item"> {{__('home.rate')}}</a></li>

            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-book-open"></i><span class="side-menu__label">{{__('home.reports')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @can('report.purchase')
                <li><a href="{{route('report.purchase')}}" class="slide-item"> {{__('home.purchase_report')}}</a></li>
                @endcan
                @can('report.sell')
                <li><a href="{{route('report.sell')}}" class="slide-item"> {{__('home.sell_report')}}</a></li>
                @endcan
                @can('report.expense')
                <li><a href="{{route('report.expense')}}" class="slide-item"> {{__('home.expense_report')}}</a></li>
                @endcan
                @can('report.sell_return')
                <li><a href="{{route('report.income')}}" class="slide-item"> {{__('home.income_report')}}</a></li>
                @endcan
                @can('report.available_stock')
                <li><a href="{{route('report.available_stock')}}" class="slide-item"> {{__('home.available_stock_report')}}</a></li>
                @endcan
                @can('report.main_stock_report')
                <li><a href="{{route('report.main_stock_report')}}" class="slide-item"> {{__('home.main_stock_report')}}</a></li>
                @endcan
                @can('report.stock_transfer_report')
                <li><a href="{{route('report.stock_transfer_report')}}" class="slide-item"> {{__('home.stock_transfer_report')}}</a></li>
                @endcan
                @can('report.main_transfer_report')
                <li><a href="{{route('report.main_transfer_report')}}" class="slide-item"> {{__('home.main_transfer_report')}}</a></li>
                @endcan
                @can('report.due_clients')
                <li><a href="{{route('report.due_clients')}}" class="slide-item"> {{__('home.due_clients_report')}}</a></li>
                @endcan
                @can('report.due_vendor')
                <li><a href="{{route('report.due_vendor')}}" class="slide-item"> {{__('home.due_vendor_report')}}</a></li>
                @endcan

                @can('report.all_vailable_report')
                <li><a href="{{route('report.all_vailable_report')}}" class="slide-item"> {{__('home.all_vailable_report')}}</a></li>
                @endcan
                @can('report.profit_loss')
                <li><a href="{{route('report.profit_lost_report')}}" class="slide-item"> {{__('home.profit_lost_report')}}</a></li>
                @endcan
                @can('report.itemwise_sell')
                <li><a href="{{route('report.item_wise_sell_report')}}" class="slide-item"> {{__('home.item_wise_sell_report')}}</a></li>
                @endcan
                @can('report.itemwise_purchase')
                <li><a href="{{route('report.item_wise_purchase_report')}}" class="slide-item"> {{__('home.item_wise_purchase_report')}}</a></li>
                @endcan



            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fe fe-user"></i><span class="side-menu__label">{{__('home.users')}}</span><i class="angle fe fe-chevron-right"></i></a>
            <ul class="slide-menu">
                @canany(['users.view', 'users.create', 'users.edit', 'users.delete'])
                <li><a href="{{route('users.index')}}" class="slide-item"> {{__('home.view_users')}}</a></li>
                @endcanany
                @canany(['roles.view', 'roles.create', 'roles.edit', 'roles.delete'])
                <li><a href="{{route('roles.index')}}" class="slide-item"> {{__('home.view_roles')}}</a></li>
                @endcanany

                @canany(['permissions.view','permissions.create','permissions.edit','permissions.delete'])
                <li><a href="{{route('permissions.index')}}" class="slide-item"> {{__('home.view_permissions')}}</a></li>
                @endcanany
            </ul>
        </li>


        <li class="slide">
            <a class="side-menu__item has-link" data-bs-toggle="slide" href="{{route('logout')}}"><i
                    class="side-menu__icon fe fe-log-out"></i><span
                    class="side-menu__label">{{ __('home.sign_out')}} </span></a>
        </li>
    </ul>
    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
            width="24" height="24" viewBox="0 0 24 24">
            <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
        </svg></div>
</div>
