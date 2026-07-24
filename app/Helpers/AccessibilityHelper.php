<?php

namespace App\Helpers;

class AccessibilityHelper
{
    /**
     * Get ARIA label for common actions
     */
    public static function getAriaLabel(string $action, string $context = ''): string
    {
        $labels = [
            'toggle_menu' => 'Toggle navigation menu',
            'close_menu' => 'Close navigation menu',
            'search' => 'Search',
            'filter' => 'Filter results',
            'sort' => 'Sort results',
            'delete' => 'Delete',
            'edit' => 'Edit',
            'view' => 'View details',
            'download' => 'Download',
            'share' => 'Share',
            'bookmark' => 'Bookmark',
            'apply' => 'Apply for job',
            'register' => 'Register for event',
            'notification' => 'Notifications',
            'settings' => 'Settings',
            'profile' => 'Profile',
            'logout' => 'Logout',
            'login' => 'Login',
        ];

        $label = $labels[$action] ?? $action;
        
        return $context ? "{$label} - {$context}" : $label;
    }

    /**
     * Get ARIA role for interactive elements
     */
    public static function getAriaRole(string $element): string
    {
        $roles = [
            'button' => 'button',
            'link' => 'link',
            'navigation' => 'navigation',
            'main' => 'main',
            'complementary' => 'complementary',
            'banner' => 'banner',
            'contentinfo' => 'contentinfo',
            'search' => 'search',
            'dialog' => 'dialog',
            'alert' => 'alert',
            'status' => 'status',
            'tablist' => 'tablist',
            'tab' => 'tab',
            'tabpanel' => 'tabpanel',
            'menu' => 'menu',
            'menuitem' => 'menuitem',
        ];

        return $roles[$element] ?? '';
    }

    /**
     * Check if color contrast meets WCAG AA standards
     * This is a simplified check - for production, use a proper contrast checker
     */
    public static function checkContrast(string $foreground, string $background): bool
    {
        // Simplified contrast check
        // In production, use a proper color contrast library
        $lightColors = ['#ffffff', '#f8fafc', '#f1f5f9', '#e2e8f0'];
        $darkColors = ['#0f172a', '#1e293b', '#334155', '#475569'];
        
        $isLightFg = in_array(strtolower($foreground), $lightColors);
        $isDarkBg = in_array(strtolower($background), $darkColors);
        
        // Light foreground on dark background or dark foreground on light background = good contrast
        return ($isLightFg && $isDarkBg) || (!$isLightFg && !$isDarkBg);
    }
}
