<template>
    <div class="admin-layout" :class="{ 'dark': isDarkMode }">
        <!-- Admin Header -->
        <header class="admin-header">
            <div class="admin-header-content">
                <div class="admin-brand">
                    <Link :href="route('home')" class="brand-link">
                        <h1 class="brand-title">{{ appName }}</h1>
                    </Link>
                    <span class="admin-badge">Admin</span>
                </div>
                
                <nav class="admin-nav">
                    <Link 
                        :href="route('admin.dashboard')" 
                        class="nav-link"
                        :class="{ 'active': $page.url.startsWith('/admin') && $page.url === '/admin' }"
                    >
                        Dashboard
                    </Link>
                    <Link 
                        :href="route('admin.posts.index')" 
                        class="nav-link"
                        :class="{ 'active': $page.url.startsWith('/admin/posts') }"
                    >
                        Posts
                    </Link>
                    <Link 
                        :href="route('admin.categories.index')" 
                        class="nav-link"
                        :class="{ 'active': $page.url.startsWith('/admin/categories') }"
                    >
                        Categories
                    </Link>
                </nav>
                
                <div class="admin-actions">
                    <Link :href="route('home')" class="view-site-link">
                        View Site
                    </Link>
                    <button @click="toggleTheme" class="theme-toggle" :title="isDarkMode ? 'Switch to light mode' : 'Switch to dark mode'">
                        {{ isDarkMode ? '☀️' : '🌙' }}
                    </button>
                </div>
            </div>
        </header>

        <!-- Admin Content -->
        <main class="admin-main">
            <div class="admin-container">
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { useTheme } from '@/composables/useTheme'
import { computed } from 'vue'

// Theme
const { isDarkMode, toggleTheme } = useTheme()

// App name
const page = usePage()
const appName = computed(() => page.props.app?.name || 'Shiny Starborne')
</script>

<style scoped>
.admin-layout {
    min-height: 100vh;
    background-color: #f8fafc;
    color: #1f2937;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    transition: background-color 0.2s, color 0.2s;
}

.admin-layout.dark {
    background-color: #111827;
    color: #f9fafb;
}

/* Header */
.admin-header {
    background-color: white;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.admin-layout.dark .admin-header {
    background-color: #1f2937;
    border-bottom-color: #374151;
}

.admin-header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    height: 4rem;
}

.admin-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.brand-link {
    text-decoration: none;
    color: inherit;
}

.brand-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
    color: #1f2937;
}

.admin-layout.dark .brand-title {
    color: #f9fafb;
}

.admin-badge {
    background-color: #dc2626;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Navigation */
.admin-nav {
    display: flex;
    gap: 2rem;
}

.nav-link {
    text-decoration: none;
    color: #6b7280;
    font-weight: 500;
    padding: 0.5rem 0;
    border-bottom: 2px solid transparent;
    transition: color 0.2s, border-color 0.2s;
}

.nav-link:hover {
    color: #374151;
    border-bottom-color: #d1d5db;
}

.nav-link.active {
    color: #dc2626;
    border-bottom-color: #dc2626;
}

.admin-layout.dark .nav-link {
    color: #9ca3af;
}

.admin-layout.dark .nav-link:hover {
    color: #d1d5db;
    border-bottom-color: #4b5563;
}

.admin-layout.dark .nav-link.active {
    color: #f87171;
    border-bottom-color: #f87171;
}

/* Actions */
.admin-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.view-site-link {
    text-decoration: none;
    color: #6b7280;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    transition: all 0.2s;
}

.view-site-link:hover {
    background-color: #f3f4f6;
    border-color: #9ca3af;
}

.admin-layout.dark .view-site-link {
    color: #9ca3af;
    border-color: #4b5563;
}

.admin-layout.dark .view-site-link:hover {
    background-color: #374151;
    border-color: #6b7280;
}

.theme-toggle {
    background: none;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.5rem;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
}

.theme-toggle:hover {
    background-color: #f3f4f6;
    border-color: #9ca3af;
}

.admin-layout.dark .theme-toggle {
    border-color: #4b5563;
    color: #f9fafb;
}

.admin-layout.dark .theme-toggle:hover {
    background-color: #374151;
    border-color: #6b7280;
}

/* Main content */
.admin-main {
    padding: 2rem 1rem;
}

.admin-container {
    max-width: 1200px;
    margin: 0 auto;
}

/* Responsive */
@media (max-width: 768px) {
    .admin-header-content {
        flex-direction: column;
        height: auto;
        padding: 1rem;
        gap: 1rem;
    }
    
    .admin-brand {
        align-self: flex-start;
    }
    
    .admin-nav {
        gap: 1.5rem;
    }
    
    .admin-actions {
        align-self: flex-end;
    }
}
</style>