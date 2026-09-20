<?php

namespace App\Helpers;

class MenuHelper
{
    /**
     * Return the full menu structure, filtered by the current user's permissions.
     */
    public static function getMenuGroups(): array
    {
        $user = auth()->user();

        if (! $user) {
            return [];
        }

        $groups = [
            [
                'title' => 'Library',
                'items' => self::libraryMenu($user),
            ],
            [
                'title' => 'Circulation',
                'items' => self::circulationMenu($user),
            ],
            [
                'title' => 'Access Control',
                'items' => self::accessMenu($user),
            ],
            [
                'title' => 'System',
                'items' => self::systemMenu($user),
            ],
        ];

        // Drop empty groups
        return array_values(array_filter($groups, fn ($g) => ! empty($g['items'])));
    }

    /* ------------------------------------------------------------------ */
    /* Library                                                             */
    /* ------------------------------------------------------------------ */
    private static function libraryMenu($user): array
    {
        $items = [];

        // Dashboard
        if ($user->can('dashboard.view')) {
            $items[] = [
                'icon' => 'grid',
                'name' => 'Dashboard',
                'path' => '/admin/dashboard',
            ];
        }

        // Catalog (Books, Authors, Categories, Publishers, E-book Files)
        $catalog = [];

        if ($user->can('books.view')) {
            $catalog[] = ['name' => 'Books', 'path' => '/admin/books'];
        }
        if ($user->can('authors.view')) {
            $catalog[] = ['name' => 'Authors', 'path' => '/admin/authors'];
        }
        if ($user->can('categories.view')) {
            $catalog[] = ['name' => 'Categories', 'path' => '/admin/categories'];
        }
        if ($user->can('publishers.view')) {
            $catalog[] = ['name' => 'Publishers', 'path' => '/admin/publishers'];
        }
        if ($user->can('ebook-files.view')) {
            $catalog[] = ['name' => 'E-book Files', 'path' => '/admin/ebook-files'];
        }

        if (! empty($catalog)) {
            $items[] = [
                'icon'     => 'book',
                'name'     => 'Catalog',
                'subItems' => $catalog,
            ];
        }

        return $items;
    }

    /* ------------------------------------------------------------------ */
    /* Circulation                                                         */
    /* ------------------------------------------------------------------ */
    private static function circulationMenu($user): array
    {
        $items = [];

        if ($user->can('borrowings.view')) {
            $subItems = [
                ['name' => 'All Borrowings', 'path' => '/admin/borrowings'],
            ];

            if ($user->can('borrowings.approve')) {
                $subItems[] = ['name' => 'Pending Approvals', 'path' => '/admin/borrowings/pending'];
            }

            $items[] = [
                'icon'     => 'swap',
                'name'     => 'Borrowings',
                'subItems' => $subItems,
            ];
        }

        if ($user->can('reviews.view')) {
            $items[] = [
                'icon' => 'star',
                'name' => 'Reviews',
                'path' => '/admin/reviews',
            ];
        }

        return $items;
    }

    /* ------------------------------------------------------------------ */
    /* Access Control                                                      */
    /* ------------------------------------------------------------------ */
    private static function accessMenu($user): array
    {
        $children = [];

        if ($user->can('users.view')) {
            $children[] = ['name' => 'Users', 'path' => '/admin/users'];
        }
        if ($user->can('roles.view')) {
            $children[] = ['name' => 'Roles', 'path' => '/admin/roles'];
        }
        if ($user->can('permissions.view')) {
            $children[] = ['name' => 'Permissions', 'path' => '/admin/permissions'];
        }

        if (empty($children)) {
            return [];
        }

        return [[
            'icon'     => 'shield',
            'name'     => 'Roles & Permissions',
            'subItems' => $children,
        ]];
    }

    /* ------------------------------------------------------------------ */
    /* System                                                              */
    /* ------------------------------------------------------------------ */
    private static function systemMenu($user): array
    {
        $items = [];

        if ($user->can('reports.view')) {
            $items[] = [
                'icon' => 'chart',
                'name' => 'Reports',
                'path' => '/admin/reports',
            ];
        }

        if ($user->can('audit-logs.view')) {
            $items[] = [
                'icon' => 'list',
                'name' => 'Audit Logs',
                'path' => '/admin/audit-logs',
            ];
        }

        // Profile — always available
        $items[] = [
            'icon' => 'user-circle',
            'name' => 'Profile',
            'path' => '/admin/profile',
        ];

        return $items;
    }

    /* ------------------------------------------------------------------ */
    /* Icons                                                               */
    /* ------------------------------------------------------------------ */
    public static function getIconSvg(string $icon): string
    {
        $icons = [
            'grid' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',

            'book' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',

            'swap' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>',

            'star' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>',

            'shield' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',

            'chart' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>',

            'list' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>',

            'user-circle' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',

            'tag' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"/></svg>',

            'user' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',

            'building' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',

            'file' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',

            'key' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>',
        ];

        return $icons[$icon] ?? $icons['grid'];
    }
}