<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'PetrogenAI - AI Assistant Platform'); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo e(asset('build/assets/app-CwC-9LoA.css')); ?>">
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900">
    <?php echo $__env->yieldContent('content'); ?>
    
    <script src="<?php echo e(asset('build/assets/app-RowJdFww.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\salih\OneDrive\Desktop\SYSMNT\AI\Ai Projects\PetrogenAI\PetrogenAi\resources\views/layouts/app.blade.php ENDPATH**/ ?>