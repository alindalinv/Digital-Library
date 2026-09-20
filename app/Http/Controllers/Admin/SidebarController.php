<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SidebarController extends Controller
{
    public function getMenuData()
    {
        $user = auth()->user();

        $menuGroups = [
            [
                'title' => 'Library',
                'items' => $this->libraryMenu($user),
            ],
            [
                'title' => 'Circulation',
                'items' => $this->circulationMenu($user),
            ],
            [
                'title' => 'Access Control',
                'items' => $this->accessMenu($user),
            ],
            [
                'title' => 'System',
                'items' => $this->systemMenu($user),
            ],
        ];

        // Remove empty groups
        $menuGroups = array_values(array_filter($menuGroups, fn ($g) => ! empty($g['items'])));

        return view('admin.components.sidebar', compact('menuGroups'));
    }

    /* ------------------------------------------------------------------ */
    /* Library group                                                       */
    /* ------------------------------------------------------------------ */
    private function libraryMenu($user): array
    {
        $items = [];

        // Dashboard
        if ($user->can('dashboard.view')) {
            $items[] = [
                'icon' => 'grid-icon',
                'name' => 'Dashboard',
                'path' => route('admin.dashboard'),
                'active' => request()->routeIs('admin.dashboard'),
            ];
        }

        // Catalog (Books, Authors, Categories, Publishers, E-book Files)
        $catalogChildren = [];

        if ($user->can('books.view')) {
            $catalogChildren[] = [
                'name'   => 'Books',
                'path'   => route('admin.books.index'),
                'active' => request()->routeIs('admin.books.*'),
            ];
        }
        if ($user->can('authors.view')) {
            $catalogChildren[] = [
                'name'   => 'Authors',
                'path'   => route('admin.authors.index'),
                'active' => request()->routeIs('admin.authors.*'),
            ];
        }
        if ($user->can('categories.view')) {
            $catalogChildren[] = [
                'name'   => 'Categories',
                'path'   => route('admin.categories.index'),
                'active' => request()->routeIs('admin.categories.*'),
            ];
        }
        if ($user->can('publishers.view')) {
            $catalogChildren[] = [
                'name'   => 'Publishers',
                'path'   => route('admin.publishers.index'),
                'active' => request()->routeIs('admin.publishers.*'),
            ];
        }
        if ($user->can('ebook-files.view')) {
            $catalogChildren[] = [
                'name'   => 'E-book Files',
                'path'   => route('admin.ebook-files.index'),
                'active' => request()->routeIs('admin.ebook-files.*'),
            ];
        }

        if (! empty($catalogChildren)) {
            $items[] = [
                'icon'     => 'book-icon',
                'name'     => 'Catalog',
                'subItems' => $catalogChildren,
            ];
        }

        return $items;
    }

    /* ------------------------------------------------------------------ */
    /* Circulation group                                                   */
    /* ------------------------------------------------------------------ */
    private function circulationMenu($user): array
    {
        $items = [];

        if ($user->can('borrowings.view')) {
            $items[] = [
                'icon'     => 'swap-icon',
                'name'     => 'Borrowings',
                'subItems' => array_values(array_filter([
                    $user->can('borrowings.view') ? [
                        'name'   => 'All Borrowings',
                        'path'   => route('admin.borrowings.index'),
                        'active' => request()->routeIs('admin.borrowings.index'),
                    ] : null,
                    $user->can('borrowings.approve') ? [
                        'name'   => 'Pending Approvals',
                        'path'   => route('admin.borrowings.pending'),
                        'active' => request()->routeIs('admin.borrowings.pending'),
                    ] : null,
                ])),
            ];
        }

        if ($user->can('reviews.view')) {
            $items[] = [
                'icon'   => 'star-icon',
                'name'   => 'Reviews',
                'path'   => route('admin.reviews.index'),
                'active' => request()->routeIs('admin.reviews.*'),
            ];
        }

        return $items;
    }

    /* ------------------------------------------------------------------ */
    /* Access Control group                                                */
    /* ------------------------------------------------------------------ */
    private function accessMenu($user): array
    {
        $children = [];

        if ($user->can('users.view')) {
            $children[] = [
                'name'   => 'Users',
                'path'   => route('admin.users.index'),
                'active' => request()->routeIs('admin.users.*'),
            ];
        }
        if ($user->can('roles.view')) {
            $children[] = [
                'name'   => 'Roles',
                'path'   => route('admin.roles.index'),
                'active' => request()->routeIs('admin.roles.*'),
            ];
        }
        if ($user->can('permissions.view')) {
            $children[] = [
                'name'   => 'Permissions',
                'path'   => route('admin.permissions.index'),
                'active' => request()->routeIs('admin.permissions.*'),
            ];
        }

        if (empty($children)) {
            return [];
        }

        return [[
            'icon'     => 'shield-icon',
            'name'     => 'Roles & Permissions',
            'subItems' => $children,
        ]];
    }

    /* ------------------------------------------------------------------ */
    /* System group                                                        */
    /* ------------------------------------------------------------------ */
    private function systemMenu($user): array
    {
        $items = [];

        if ($user->can('reports.view')) {
            $items[] = [
                'icon'   => 'chart-icon',
                'name'   => 'Reports',
                'path'   => route('admin.reports.index'),
                'active' => request()->routeIs('admin.reports.*'),
            ];
        }

        if ($user->can('audit-logs.view')) {
            $items[] = [
                'icon'   => 'list-icon',
                'name'   => 'Audit Logs',
                'path'   => route('admin.audit-logs.index'),
                'active' => request()->routeIs('admin.audit-logs.*'),
            ];
        }

        // Profile — always visible to logged-in admin
        $items[] = [
            'icon'   => 'user-circle-icon',
            'name'   => 'Profile',
            'path'   => route('admin.profile.edit'),
            'active' => request()->routeIs('admin.profile.*'),
        ];

        return $items;
    }
}