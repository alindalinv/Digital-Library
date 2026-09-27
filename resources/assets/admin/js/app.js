import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import ApexCharts from 'apexcharts';

// Flatpickr
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';

// FullCalendar
import { Calendar } from '@fullcalendar/core';


// ============================================================
// Global objects
// ============================================================

window.Alpine = Alpine;


// ============================================================
// Alpine plugins
// ============================================================

Alpine.plugin(collapse);


// ============================================================
// Theme Store
// ============================================================

Alpine.store('theme', {

    theme: 'light',

    init() {

        const savedTheme =
            localStorage.getItem('theme');

        if (
            savedTheme === 'dark' ||
            savedTheme === 'light'
        ) {

            this.theme = savedTheme;

        } else {

            this.theme =
                window.matchMedia(
                    '(prefers-color-scheme: dark)'
                ).matches
                    ? 'dark'
                    : 'light';
        }

        this.apply();
    },


    toggle() {

        this.theme =
            this.theme === 'dark'
                ? 'light'
                : 'dark';

        localStorage.setItem(
            'theme',
            this.theme
        );

        this.apply();
    },


    apply() {

        const isDark =
            this.theme === 'dark';


        // HTML <html class="dark">
        document.documentElement.classList.toggle(
            'dark',
            isDark
        );


        // Optional body class
        document.body.classList.toggle(
            'dark',
            isDark
        );


        // Only add bg-gray-900 in dark mode
        document.body.classList.toggle(
            'bg-gray-900',
            isDark
        );
    }
});


// ============================================================
// Sidebar Store
// ============================================================

Alpine.store('sidebar', {

    breakpoint: 1280,

    isExpanded: false,
    isMobileOpen: false,
    isHovered: false,


    init() {

        this.syncWithViewport();

        window.addEventListener(
            'resize',
            this.handleResize.bind(this),
            {
                passive: true
            }
        );
    },


    isDesktop() {

        return window.innerWidth >= this.breakpoint;
    },


    syncWithViewport() {

        if (this.isDesktop()) {

            const savedState =
                localStorage.getItem(
                    'sidebarExpanded'
                );


            this.isExpanded =
                savedState === null
                    ? true
                    : savedState === 'true';


            this.isMobileOpen = false;

        } else {

            this.isExpanded = false;

            this.isMobileOpen = false;

            this.isHovered = false;
        }
    },


    handleResize() {

        this.syncWithViewport();
    },


    toggleExpanded() {

        if (!this.isDesktop()) {
            return;
        }


        this.isExpanded =
            !this.isExpanded;


        this.isHovered = false;


        localStorage.setItem(
            'sidebarExpanded',
            this.isExpanded
        );
    },


    toggleMobileOpen() {

        if (this.isDesktop()) {
            return;
        }


        this.isMobileOpen =
            !this.isMobileOpen;
    },


    setMobileOpen(value) {

        this.isMobileOpen =
            Boolean(value);
    },


    setHovered(value) {

        if (
            this.isDesktop() &&
            !this.isExpanded
        ) {

            this.isHovered =
                Boolean(value);
        }
    }
});


// ============================================================
// Initialize stores
// ============================================================

Alpine.store('theme').init();

Alpine.store('sidebar').init();


// ============================================================
// Start Alpine
// ============================================================

Alpine.start();