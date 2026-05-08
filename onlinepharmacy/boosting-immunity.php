<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boosting Immunity | BioPharma Insights</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .glass-nav { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <header class="glass-nav border-b border-slate-200 sticky top-0 z-40 px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <div class="bg-emerald-600 p-2 rounded-lg text-white shadow-lg">
                <i class="fas fa-leaf text-sm"></i>
            </div>
            <a href="index.php" class="text-xl font-extrabold text-slate-800 tracking-tight">BioPharma</a>
        </div>
        <a href="insights.php" class="text-sm font-bold text-slate-600 hover:text-emerald-600 transition">Back to Insights</a>
    </header>

    <main class="max-w-4xl mx-auto w-full p-6 py-10 flex-1">
        <article class="bg-white border border-slate-100 rounded-[2.5rem] shadow-sm overflow-hidden">
            <img src="images/boostimmu.jpg" alt="Boosting Immunity" class="w-full h-72 object-cover">
            <div class="p-8 md:p-10">
                <span class="inline-block text-[10px] font-black text-emerald-700 bg-emerald-50 px-4 py-2 rounded-xl uppercase tracking-widest mb-5">Nutrition</span>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-6">Boosting Immunity: 5 Everyday Habits That Actually Work</h1>
                <p class="text-slate-600 leading-relaxed mb-6">
                    Building stronger immunity is less about one magic food and more about daily consistency. Your immune system relies on quality sleep,
                    balanced nutrition, hydration, stress control, and regular movement to function at its best. When these habits are aligned,
                    your body responds better to seasonal infections and recovers faster.
                </p>

                <h2 class="text-2xl font-black text-slate-900 mb-3">1. Add Immune-Friendly Foods Daily</h2>
                <p class="text-slate-600 leading-relaxed mb-5">
                    Include vitamin C-rich foods like citrus fruits, amla, and bell peppers, plus zinc sources such as seeds, nuts, and legumes.
                    Garlic, ginger, turmeric, and curd can also support gut and immune health when used regularly in meals.
                </p>

                <h2 class="text-2xl font-black text-slate-900 mb-3">2. Sleep 7 to 8 Hours Consistently</h2>
                <p class="text-slate-600 leading-relaxed mb-5">
                    Lack of sleep reduces the efficiency of immune cells. Set a fixed bedtime, avoid screens for at least 45 minutes before sleep,
                    and keep your room cool and dark to improve recovery quality.
                </p>

                <h2 class="text-2xl font-black text-slate-900 mb-3">3. Stay Hydrated Through the Day</h2>
                <p class="text-slate-600 leading-relaxed mb-5">
                    Water supports nutrient transport and helps your body remove waste. Aim for steady hydration across the day instead of drinking
                    large amounts at once. Add coconut water or lemon water when needed.
                </p>

                <h2 class="text-2xl font-black text-slate-900 mb-3">4. Move Your Body Every Day</h2>
                <p class="text-slate-600 leading-relaxed mb-5">
                    A brisk 25 to 30 minute walk, light strength training, or yoga can reduce inflammation and improve circulation, both of which
                    help your immune response. Avoid overtraining during fatigue.
                </p>

                <h2 class="text-2xl font-black text-slate-900 mb-3">5. Manage Stress Proactively</h2>
                <p class="text-slate-600 leading-relaxed mb-6">
                    Chronic stress weakens defense mechanisms. Try simple methods: deep breathing for 5 minutes, journaling, or short digital breaks
                    through the day. Small routines can significantly reduce stress load.
                </p>

                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 text-sm text-emerald-900 font-medium">
                    Tip: If you get frequent infections, discuss vitamin D, B12, and iron levels with your doctor and avoid self-medicating repeatedly.
                </div>

                <div class="mt-8">
                    <a href="insights.php" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-700 transition">
                        <i class="fas fa-arrow-left"></i>
                        Back to Latest Articles
                    </a>
                </div>
            </div>
        </article>
    </main>

    <footer class="p-8 text-center bg-white border-t border-slate-100">
        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">&copy; 2026 BioPharma Insights</p>
    </footer>
</body>
</html>
