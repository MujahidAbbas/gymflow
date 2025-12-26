#!/usr/bin/env php
<?php

/**
 * Apply TenantScope to all models with parent_id
 *
 * This script adds the TenantScope to models that have parent_id
 */
$modelsToUpdate = [
    'Attendance',
    'Category',
    'Event',
    'Expense',
    'GymClass',
    'Health',
    'Invoice',
    'Locker',
    'MembershipPlan',
    'NoticeBoard',
    'Product',
    'ProductCategory',
    'SubscriptionPlan',
    'Trainer',
    'Type',
    'Workout',
];

$basePath = __DIR__.'/../app/Models/';

foreach ($modelsToUpdate as $model) {
    $filePath = $basePath.$model.'.php';

    if (! file_exists($filePath)) {
        echo "⚠️  Model not found: {$model}.php\n";

        continue;
    }

    $content = file_get_contents($filePath);

    // Check if it already has TenantScope
    if (strpos($content, 'TenantScope') !== false) {
        echo "✓ {$model} already has TenantScope\n";

        continue;
    }

    // Check if it has a boot method
    if (preg_match('/protected static function boot\(\)/i', $content)) {
        // Has boot method - add scope inside it
        $content = preg_replace(
            '/(protected static function boot\(\)\s*\{\s*parent::boot\(\);)/i',
            "$1\n\n        // Apply tenant scoping for data isolation\n        static::addGlobalScope(new \\App\\Models\\Scopes\\TenantScope);",
            $content
        );
        echo "✓ Added TenantScope to existing boot() in {$model}\n";
    } else {
        // No boot method - add one
        $bootMethod = "\n    /**\n     * Boot the model\n     */\n    protected static function boot()\n    {\n        parent::boot();\n\n        // Apply tenant scoping for data isolation\n        static::addGlobalScope(new \\App\\Models\\Scopes\\TenantScope);\n    }\n";

        // Find a good insertion point (after fillable or casts)
        if (preg_match('/(protected \$fillable = \[.*?\];)/s', $content, $matches, PREG_OFFSET_CAPTURE)) {
            $insertPos = $matches[0][1] + strlen($matches[0][0]);
            $content = substr_replace($content, $bootMethod, $insertPos, 0);
            echo "✓ Added TenantScope with new boot() in {$model}\n";
        } else {
            echo "⚠️  Could not find insertion point in {$model}\n";

            continue;
        }
    }

    file_put_contents($filePath, $content);
}

echo "\n✅ TenantScope application complete!\n";
