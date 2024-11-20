<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                @if(isset($setup->company_logo) && $setup->company_logo)
                    <img src="{{ asset($setup->company_logo) }}" alt="Company Logo" style="height: 25px;">
                @endif
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">{{ ucwords(str_replace('_', ' ', $setup->app_name)) }}</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <li class="menu-item @yield('dashboard-active')">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'dashboard')) }}</div>
            </a>
        </li>

        @if (
                in_array('role.index', $permissions) ||
                in_array('user.index', $permissions) ||
                in_array('activity_log', $permissions)
            )
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Access</span>
        </li>
        @endif

        @if (
                in_array('role.index', $permissions) ||
                in_array('user.index', $permissions)
            )
            <li
                class="menu-item
                @yield('role-active')
                @yield('user-active')
            ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-folder"></i>
                <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'master')) }}</div>
            </a>
            <ul class="menu-sub">
                @if (in_array('role.index', $permissions))
                    <li class="menu-item @yield('role-active')">
                        <a href="{{ route('role.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'role')) }}
                            </div>
                        </a>
                    </li>
                @endif
                @if (in_array('user.index', $permissions))
                    <li class="menu-item @yield('user-active')">
                        <a href="{{ route('user.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'user')) }}
                            </div>
                        </a>
                    </li>
                @endif
            </ul>
        @endif

        @if (
                in_array('activity_log', $permissions)
            )
            <li
                class="menu-item
                @yield('activity_log-active')
            ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-transfer-alt"></i>
                <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'transaction')) }}</div>
            </a>
            <ul class="menu-sub">
                @if (in_array('activity_log', $permissions))
                    <li class="menu-item @yield('activity_log-active')">
                        <a href="{{ route('activity_log') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'activity_log')) }}
                            </div>
                        </a>
                    </li>
                @endif
            </ul>
        @endif

        @if (
                in_array('cash_flow_category.index', $permissions) ||
                in_array('financial_statement.index', $permissions) ||
                in_array('normal_balance.index', $permissions) ||
                in_array('account_group.index', $permissions) ||
                in_array('main_account.index', $permissions) ||
                in_array('sub_account.index', $permissions) ||
                in_array('account.index', $permissions) ||
                in_array('tax_rate.index', $permissions) ||
                in_array('payment_term.index', $permissions) ||
                in_array('invoice_category.index', $permissions) ||
                in_array('budget.index', $permissions) ||
                in_array('journal.index', $permissions) ||
                in_array('balance_sheet.index', $permissions) ||
                in_array('income_statement.index', $permissions) ||
                in_array('cash_flow.index', $permissions) ||
                in_array('ledger.index', $permissions) ||
                in_array('invoice_category.index', $permissions) ||
                in_array('repayment_category.index', $permissions) ||
                in_array('transaction.index', $permissions)
            )
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Financial</span>
        </li>
        @endif

        @if (
                in_array('cash_flow_category.index', $permissions) ||
                in_array('financial_statement.index', $permissions) ||
                in_array('normal_balance.index', $permissions) ||
                in_array('account_group.index', $permissions) ||
                in_array('main_account.index', $permissions) ||
                in_array('sub_account.index', $permissions) ||
                in_array('account.index', $permissions) ||
                in_array('tax_rate.index', $permissions) ||
                in_array('payment_term.index', $permissions) ||
                in_array('invoice_category.index', $permissions) ||
                in_array('repayment_category.index', $permissions)
            )
            <li
                class="menu-item
                @yield('cash_flow_category-active')
                @yield('financial_statement-active')
                @yield('normal_balance-active')
                @yield('account_group-active')
                @yield('main_account-active')
                @yield('sub_account-active')
                @yield('account-active')
                @yield('tax_rate-active')
                @yield('payment_term-active')
                @yield('invoice_category-active')
                @yield('repayment_category-active')
            ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'master')) }}</div>
                </a>
                <ul class="menu-sub">
                    {{-- @if (in_array('cash_flow_category.index', $permissions))
                    <li class="menu-item @yield('cash_flow_category-active')">
                        <a href="{{ route('cash_flow_category.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'cash_flow_category')) }}
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (in_array('financial_statement.index', $permissions))
                    <li class="menu-item @yield('financial_statement-active')">
                        <a href="{{ route('financial_statement.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'financial_statement')) }}
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (in_array('normal_balance.index', $permissions))
                    <li class="menu-item @yield('normal_balance-active')">
                        <a href="{{ route('normal_balance.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'normal_balance')) }}
                            </div>
                        </a>
                    </li>
                    @endif --}}
                    @if (in_array('account_group.index', $permissions))
                        <li class="menu-item @yield('account_group-active')">
                            <a href="{{ route('account_group.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'account_group')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('main_account.index', $permissions))
                        <li class="menu-item @yield('main_account-active')">
                            <a href="{{ route('main_account.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'main_account')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('sub_account.index', $permissions))
                        <li class="menu-item @yield('sub_account-active')">
                            <a href="{{ route('sub_account.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'sub_account')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('account.index', $permissions))
                        <li class="menu-item @yield('account-active')">
                            <a href="{{ route('account.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'account')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('tax_rate.index', $permissions))
                        <li class="menu-item @yield('tax_rate-active')">
                            <a href="{{ route('tax_rate.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'tax_rate')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('payment_term.index', $permissions))
                        <li class="menu-item @yield('payment_term-active')">
                            <a href="{{ route('payment_term.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'payment_term')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('invoice_category.index', $permissions))
                        <li class="menu-item @yield('invoice_category-active')">
                            <a href="{{ route('invoice_category.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'invoice_category')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('repayment_category.index', $permissions))
                        <li class="menu-item @yield('repayment_category-active')">
                            <a href="{{ route('repayment_category.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'repayment_category')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (
                in_array('journal.index', $permissions) ||
                in_array('budget.index', $permissions) ||
                in_array('invoice.index', $permissions) ||
                in_array('repayment.index', $permissions) ||
                in_array('ledger.index', $permissions)
            )
            <li
                class="menu-item
                @yield('journal-active')
                @yield('budget-active')
                @yield('invoice-active')
                @yield('repayment-active')
                @yield('ledger-active')
            ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-transfer-alt"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'transaction')) }}</div>
                </a>
                <ul class="menu-sub">
                    @if (in_array('budget.index', $permissions))
                        <li class="menu-item @yield('budget-active')">
                            <a href="{{ route('budget.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'budget')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('journal.index', $permissions))
                        <li class="menu-item @yield('journal-active')">
                            <a href="{{ route('journal.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'journal')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('invoice.index', $permissions))
                    <li class="menu-item @yield('invoice-active')">
                        <a href="{{ route('invoice.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'invoice')) }}
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (in_array('repayment.index', $permissions))
                    <li class="menu-item @yield('repayment-active')">
                        <a href="{{ route('repayment.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'repayment')) }}
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (in_array('ledger.index', $permissions))
                        <li class="menu-item @yield('ledger-active')">
                            <a href="{{ route('ledger.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'ledger')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (
                in_array('balance_sheet.index', $permissions) ||
                in_array('income_statement.index', $permissions) ||
                in_array('cash_flow.index', $permissions)
            )
            <li
                class="menu-item
                @yield('balance_sheet-active')
                @yield('income_statement-active')
                @yield('cash_flow-active')
            ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-bar-chart"></i>
                    {{-- <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAKVJREFUSEvllQsOQDAQRJ+bcBM3w0lcxU24CdlExae6/RDCJk2T6s6rSdPJuLmym/WxAUqgAmR2VQ002gFtgB7Itcb5uwqxAUYPcRGWIeWExAKkT2xUISkAOb0KSQWokCsAe8hGMxbgugdBALPZ52YZ6M8BPpYlWfQ9gO2PLrXo/QCPl/uwRbUoJHD26gNQrBfPIrMNSDWj180RKvNSj4R+jO+nPRPGLzQZA0u51gAAAABJRU5ErkJggg=="/> --}}
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'report')) }}</div>
                </a>
                <ul class="menu-sub">
                    @if (in_array('balance_sheet.index', $permissions))
                        <li class="menu-item @yield('balance_sheet-active')">
                            <a href="{{ route('balance_sheet.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'balance_sheet')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('income_statement.index', $permissions))
                        <li class="menu-item @yield('income_statement-active')">
                            <a href="{{ route('income_statement.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'income_statement')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    {{-- @if (in_array('cash_flow.index', $permissions))
                        <li class="menu-item @yield('cash_flow-active')">
                            <a href="{{ route('cash_flow.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'cash_flow')) }}
                                </div>
                            </a>
                        </li>
                    @endif --}}
                </ul>
            </li>
        @endif

        @if (
                in_array('warehouse.index', $permissions) ||
                in_array('unit.index', $permissions) ||
                in_array('material_category.index', $permissions) ||
                in_array('material_sub_category.index', $permissions) ||
                in_array('material.index', $permissions) ||
                in_array('service.index', $permissions) ||
                in_array('vendor.index', $permissions) ||
                in_array('supplier.index', $permissions)
            )
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Inventory</span>
        </li>
        @endif

        @if (
                in_array('warehouse.index', $permissions) ||
                in_array('unit.index', $permissions) ||
                in_array('material_category.index', $permissions) ||
                in_array('material_sub_category.index', $permissions) ||
                in_array('material.index', $permissions) ||
                in_array('service.index', $permissions) ||
                in_array('supplier.index', $permissions) ||
                in_array('vendor.index', $permissions)
            )
            <li
                class="menu-item
                @yield('warehouse-active')
                @yield('unit-active')
                @yield('material_category-active')
                @yield('material_sub_category-active')
                @yield('material-active')
                @yield('service-active')
                @yield('supplier-active')
                @yield('vendor-active')
            ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'master')) }}</div>
                </a>
                <ul class="menu-sub">
                    @if (in_array('warehouse.index', $permissions))
                        <li class="menu-item @yield('warehouse-active')">
                            <a href="{{ route('warehouse.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'warehouse')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('unit.index', $permissions))
                        <li class="menu-item @yield('unit-active')">
                            <a href="{{ route('unit.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'unit')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('material_category.index', $permissions))
                        <li class="menu-item @yield('material_category-active')">
                            <a href="{{ route('material_category.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'material_category')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('material_sub_category.index', $permissions))
                        <li class="menu-item @yield('material_sub_category-active')">
                            <a href="{{ route('material_sub_category.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'material_sub_category')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('material.index', $permissions))
                        <li class="menu-item @yield('material-active')">
                            <a href="{{ route('material.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'material')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('service.index', $permissions))
                        <li class="menu-item @yield('service-active')">
                            <a href="{{ route('service.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'service')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('supplier.index', $permissions))
                        <li class="menu-item @yield('supplier-active')">
                            <a href="{{ route('supplier.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'supplier')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('vendor.index', $permissions))
                    <li class="menu-item @yield('vendor-active')">
                        <a href="{{ route('vendor.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'vendor')) }}
                            </div>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (
                in_array('region.index', $permissions) ||
                in_array('business.index', $permissions) ||
                in_array('customer.index', $permissions)
            )
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">CRM</span>
        </li>
        @endif

        @if (
                in_array('region.index', $permissions) ||
                in_array('business.index', $permissions) ||
                in_array('customer.index', $permissions)
            )
            <li
                class="menu-item
                @yield('region-active')
                @yield('business-active')
                @yield('customer-active')
            ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'master')) }}</div>
                </a>
                <ul class="menu-sub">
                    @if (in_array('region.index', $permissions))
                        <li class="menu-item @yield('region-active')">
                            <a href="{{ route('region.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'region')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('business.index', $permissions))
                        <li class="menu-item @yield('business-active')">
                            <a href="{{ route('business.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'business')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('customer.index', $permissions))
                    <li class="menu-item @yield('customer-active')">
                        <a href="{{ route('customer.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'customer')) }}
                            </div>
                        </a>
                    </li>
                @endif
                </ul>
            </li>
        @endif

        {{-- @if (
                in_array('department.index', $permissions) ||
                in_array('sub_department.index', $permissions) ||
                in_array('level.index', $permissions) ||
                in_array('employee_status.index', $permissions) ||
                in_array('education.index', $permissions) ||
                in_array('campus.index', $permissions) ||
                in_array('major.index', $permissions) ||
                in_array('religion.index', $permissions) ||
                in_array('marital_status.index', $permissions) ||
                in_array('bank.index', $permissions) ||
                in_array('skill.index', $permissions) ||
                in_array('title.index', $permissions) ||
                in_array('employee_identity.index', $permissions) ||
                in_array('employee.index', $permissions) ||
                in_array('shift.index', $permissions) ||
                in_array('attendance.index', $permissions) ||
                in_array('overtime.index', $permissions) ||
                in_array('checklog.index', $permissions) ||
                in_array('allowance.index', $permissions) ||
                in_array('deduction.index', $permissions) ||
                in_array('payroll.index', $permissions) ||
                in_array('day.index', $permissions) ||
                in_array('holiday.index', $permissions) ||
                in_array('leave.index', $permissions)
            )
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Human Resource</span>
        </li>
        @endif

        @if (
                in_array('department.index', $permissions) ||
                in_array('sub_department.index', $permissions) ||
                in_array('level.index', $permissions) ||
                in_array('employee_status.index', $permissions) ||
                in_array('education.index', $permissions) ||
                in_array('campus.index', $permissions) ||
                in_array('major.index', $permissions) ||
                in_array('religion.index', $permissions) ||
                in_array('marital_status.index', $permissions) ||
                in_array('bank.index', $permissions) ||
                in_array('skill.index', $permissions) ||
                in_array('title.index', $permissions) ||
                in_array('employee_identity.index', $permissions) ||
                in_array('employee.index', $permissions) ||
                in_array('shift.index', $permissions) ||
                in_array('allowance.index', $permissions) ||
                in_array('deduction.index', $permissions) ||
                in_array('day.index', $permissions) ||
                in_array('holiday.index', $permissions)
            )
            <li
                class="menu-item
                @yield('department-active')
                @yield('sub_department-active')
                @yield('level-active')
                @yield('employee_status-active')
                @yield('education-active')
                @yield('campus-active')
                @yield('major-active')
                @yield('religion-active')
                @yield('marital_status-active')
                @yield('bank-active')
                @yield('skill-active')
                @yield('title-active')
                @yield('employee_identity-active')
                @yield('employee-active')
                @yield('shift-active')
                @yield('allowance-active')
                @yield('deduction-active')
                @yield('day-active')
                @yield('holiday-active')
            ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-folder"></i>
                    <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'master')) }}</div>
                </a>
                <ul class="menu-sub">
                    @if (in_array('department.index', $permissions))
                        <li class="menu-item @yield('department-active')">
                            <a href="{{ route('department.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'department')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('sub_department.index', $permissions))
                        <li class="menu-item @yield('sub_department-active')">
                            <a href="{{ route('sub_department.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'sub_department')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('level.index', $permissions))
                        <li class="menu-item @yield('level-active')">
                            <a href="{{ route('level.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'level')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('employee_status.index', $permissions))
                        <li class="menu-item @yield('employee_status-active')">
                            <a href="{{ route('employee_status.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'employee_status')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('education.index', $permissions))
                        <li class="menu-item @yield('education-active')">
                            <a href="{{ route('education.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'education')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('campus.index', $permissions))
                        <li class="menu-item @yield('campus-active')">
                            <a href="{{ route('campus.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'campus')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('major.index', $permissions))
                        <li class="menu-item @yield('major-active')">
                            <a href="{{ route('major.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'major')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('religion.index', $permissions))
                        <li class="menu-item @yield('religion-active')">
                            <a href="{{ route('religion.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'religion')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('marital_status.index', $permissions))
                        <li class="menu-item @yield('marital_status-active')">
                            <a href="{{ route('marital_status.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'marital_status')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('bank.index', $permissions))
                        <li class="menu-item @yield('bank-active')">
                            <a href="{{ route('bank.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'bank')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('skill.index', $permissions))
                        <li class="menu-item @yield('skill-active')">
                            <a href="{{ route('skill.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'skill')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('title.index', $permissions))
                        <li class="menu-item @yield('title-active')">
                            <a href="{{ route('title.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'title')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('employee_identity.index', $permissions))
                        <li class="menu-item @yield('employee_identity-active')">
                            <a href="{{ route('employee_identity.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'employee_identity')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('employee.index', $permissions))
                        <li class="menu-item @yield('employee-active')">
                            <a href="{{ route('employee.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'employee')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('shift.index', $permissions))
                        <li class="menu-item @yield('shift-active')">
                            <a href="{{ route('shift.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'shift')) }}
                                </div>
                            </a>
                        </li>
                    @endif
                    @if (in_array('allowance.index', $permissions))
                    <li class="menu-item @yield('allowance-active')">
                        <a href="{{ route('allowance.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'allowance')) }}
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (in_array('deduction.index', $permissions))
                    <li class="menu-item @yield('deduction-active')">
                        <a href="{{ route('deduction.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'deduction')) }}
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (in_array('day.index', $permissions))
                    <li class="menu-item @yield('day-active')">
                        <a href="{{ route('day.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'day')) }}
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (in_array('holiday.index', $permissions))
                    <li class="menu-item @yield('holiday-active')">
                        <a href="{{ route('holiday.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'holiday')) }}
                            </div>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
        @endif

        @if (
                in_array('attendance.index', $permissions) ||
                in_array('overtime.index', $permissions) ||
                in_array('checklog.index', $permissions) ||
                in_array('payroll.index', $permissions) ||
                in_array('leave.index', $permissions)
            )
            <li
                class="menu-item
                @yield('attendance-active')
                @yield('overtime-active')
                @yield('checklog-active')
                @yield('payroll-active')
                @yield('leave-active')
            ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-transfer-alt"></i>
                <div data-i18n="Analytics">{{ ucwords(str_replace('_', ' ', 'transaction')) }}</div>
            </a>
            <ul class="menu-sub">
                @if (in_array('checklog.index', $permissions))
                    <li class="menu-item @yield('checklog-active')">
                        <a href="{{ route('checklog.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'checklog')) }}
                            </div>
                        </a>
                    </li>
                @endif
                @if (in_array('attendance.index', $permissions))
                    <li class="menu-item @yield('attendance-active')">
                        <a href="{{ route('attendance.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'attendance')) }}
                            </div>
                        </a>
                    </li>
                @endif
                @if (in_array('overtime.index', $permissions))
                    <li class="menu-item @yield('overtime-active')">
                        <a href="{{ route('overtime.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'overtime')) }}
                            </div>
                        </a>
                    </li>
                @endif
                @if (in_array('leave.index', $permissions))
                    <li class="menu-item @yield('leave-active')">
                        <a href="{{ route('leave.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'leave')) }}
                            </div>
                        </a>
                    </li>
                @endif
                @if (in_array('payroll.index', $permissions))
                <li class="menu-item @yield('payroll-active')">
                    <a href="{{ route('payroll.index') }}" class="menu-link">
                        <div data-i18n="Without menu">{{ ucwords(str_replace('_', ' ', 'payroll')) }}
                        </div>
                    </a>
                </li>
                @endif
            </ul>
        @endif --}}

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text"></span>
        </li>

    </ul>

</aside>
<!-- / Menu -->
