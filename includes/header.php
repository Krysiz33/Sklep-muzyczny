<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Resonance'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        background: '#0a0a0b',
                        card: '#111113',
                        secondary: '#18181c',
                        primary: '#e8ff3d',
                        border: 'rgba(255,255,255,0.08)',
                        muted: '#1e1e22',
                    },
                    fontFamily: {
                        sans: ['Barlow', 'sans-serif'],
                        condensed: ['Barlow Condensed', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Barlow', sans-serif; background: #0a0a0b; color: #fff; }
        .muted { color: #888896; }
    </style>
</head>
<body class="bg-[#0a0a0b] text-white">

<!-- NAV -->
<nav class="fixed top-0 left-0 right-0 z-50 h-16 flex items-center px-8 lg:px-16 bg-[#0a0a0b]/96 backdrop-blur-sm border-b border-white/[0.08]">
    <a href="../index.php" class="text-white font-black tracking-[0.3em] text-lg mr-12 shrink-0 hover:text-[#e8ff3d] transition-colors" style="font-family:'Barlow Condensed',sans-serif">
        RESONANCE
    </a>
    <div class="hidden md:flex items-center gap-8 flex-1">
        <a href="#" class="text-[0.68rem] text-[#888896] hover:text-white transition-colors tracking-[0.18em] uppercase font-semibold">Products</a>
        <a href="#" class="text-[0.68rem] text-[#888896] hover:text-white transition-colors tracking-[0.18em] uppercase font-semibold">Guitars</a>
        <a href="#" class="text-[0.68rem] text-[#888896] hover:text-white transition-colors tracking-[0.18em] uppercase font-semibold">Synths</a>
        <a href="#" class="text-[0.68rem] text-[#888896] hover:text-white transition-colors tracking-[0.18em] uppercase font-semibold">Drums</a>
        <a href="#" class="text-[0.68rem] text-[#888896] hover:text-white transition-colors tracking-[0.18em] uppercase font-semibold">About</a>
    </div>
    <div class="flex items-center gap-2">
        <?php if (isLoggedIn()): ?>
            <span class="text-[#888896] text-sm"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <?php if (in_array($_SESSION['user_role'], array('admin', 'manager', 'dostawca'))): ?>
                <a href="../pages/dashboard.php" class="text-[0.68rem] text-[#888896] hover:text-white transition-colors tracking-[0.18em] uppercase font-semibold px-3">Dashboard</a>
            <?php endif; ?>
            <a href="../pages/logout.php" class="text-[0.68rem] text-[#888896] hover:text-white transition-colors tracking-[0.18em] uppercase font-semibold px-3">Wyloguj</a>
        <?php else: ?>
            <a href="../pages/login.php" class="p-2.5 text-[#888896] hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </a>
        <?php endif; ?>
    </div>
</nav>

<div class="pt-16">
