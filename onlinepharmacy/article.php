<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config.php');

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

$articles = [
    'boosting-immunity' => [
        'title' => 'Boosting Immunity',
        'category' => 'Nutrition',
        'image' => 'boostimmu.jpg',
        'intro' => 'Strong immunity comes from consistent daily habits, not one-time quick fixes. Your food, sleep, activity, and stress levels together determine how well your body can protect and repair itself.',
        'points' => [
            ['Eat whole foods daily', 'Add colorful fruits and vegetables, nuts, seeds, legumes, curd, and spices like ginger and turmeric. These support gut health and micronutrient intake.'],
            ['Prioritize sleep quality', 'Aim for 7 to 8 hours each night. Keep a fixed sleep schedule and avoid screens before bedtime to improve recovery and immune response.'],
            ['Move and manage stress', 'Regular walking, light workouts, and breathing exercises lower inflammation and help immune cells function better.']
        ],
        'tip' => 'If you frequently fall sick, consult your doctor for vitamin D, B12, and iron evaluation.'
    ],
    'mental-health-at-work' => [
        'title' => 'Mental Health at Work',
        'category' => 'Wellness',
        'image' => 'mentalhealth.png',
        'intro' => 'Work stress can quietly reduce focus, sleep, and motivation. Small structure changes in your day can protect mental health and improve productivity.',
        'points' => [
            ['Use 90-minute focus blocks', 'Work in deep-focus sessions with short breaks in between to reduce cognitive fatigue and maintain output quality.'],
            ['Set communication boundaries', 'Batch emails and chat responses at fixed times to avoid constant context switching.'],
            ['Build recovery rituals', 'Take short walks, hydrate, stretch, and keep a shutdown routine at the end of your workday.']
        ],
        'tip' => 'Persistent anxiety, poor sleep, or burnout signs are valid reasons to seek professional support.'
    ],
    'heart-healthy-yoga' => [
        'title' => 'Heart Healthy Yoga',
        'category' => 'Fitness',
        'image' => 'healthheartyoga.png',
        'intro' => 'Yoga supports heart health by improving circulation, breathing efficiency, and stress control. A short daily practice can make a measurable difference over time.',
        'points' => [
            ['Start with gentle mobility', 'Use cat-cow, child pose, and seated twists to warm up joints and breathing rhythm.'],
            ['Practice calming poses', 'Add bridge pose, legs-up-the-wall, and supine stretches to reduce stress hormones.'],
            ['Breathe with intention', 'Slow nasal breathing and longer exhalations support blood pressure and recovery.']
        ],
        'tip' => 'If you have a cardiac condition, follow your physician guidance before starting new routines.'
    ],
    'diabetes-management' => [
        'title' => 'Diabetes Management',
        'category' => 'Medical',
        'image' => 'diabitymanagement.png',
        'intro' => 'Good diabetes management is a daily system: planned meals, activity, medicines, and monitoring. Consistency helps avoid sudden spikes and long-term complications.',
        'points' => [
            ['Build balanced plates', 'Each meal should combine fiber, protein, and controlled portions of low-glycemic carbs.'],
            ['Track sugar patterns', 'Monitor fasting and post-meal values to identify what triggers spikes in your routine.'],
            ['Stay active every day', 'Even a 20 to 30 minute walk after meals can improve glucose utilization.']
        ],
        'tip' => 'Never adjust diabetes medicines on your own without clinician advice.'
    ],
    'skincare-for-summer' => [
        'title' => 'Skincare for Summer',
        'category' => 'Lifestyle',
        'image' => 'skincareforsummer.png',
        'intro' => 'Summer heat, sweat, and UV exposure can damage skin barrier function. A simple routine is often more effective than layering many products.',
        'points' => [
            ['Use broad-spectrum sunscreen', 'Apply SPF 30 or above every morning and reapply after sweating or sun exposure.'],
            ['Keep cleansing gentle', 'Choose a mild cleanser twice daily to remove oil and pollutants without over-drying.'],
            ['Hydrate inside and out', 'Use a lightweight moisturizer and increase water intake through the day.']
        ],
        'tip' => 'For persistent acne, pigmentation, or rashes, consult a dermatologist early.'
    ],
    'the-power-of-sleep' => [
        'title' => 'The Power of Sleep',
        'category' => 'Wellness',
        'image' => 'thepowerofsleep.png',
        'intro' => 'Sleep is your body repair window. Hormonal balance, memory consolidation, immunity, and muscle recovery all depend on sufficient high-quality sleep.',
        'points' => [
            ['Keep a fixed sleep time', 'Sleeping and waking at consistent times strengthens your circadian rhythm.'],
            ['Optimize your sleep environment', 'Dark, cool, and quiet rooms reduce night awakenings and improve deep sleep.'],
            ['Reduce late stimulants', 'Avoid caffeine late in the day and heavy meals close to bedtime.']
        ],
        'tip' => 'Chronic snoring or daytime sleepiness may need clinical sleep evaluation.'
    ],
    'superfoods-for-brain' => [
        'title' => 'Superfoods for Brain',
        'category' => 'Nutrition',
        'image' => 'superfoodforbrain.png',
        'intro' => 'Brain performance is highly nutrition-sensitive. Regular intake of omega fats, antioxidants, and quality proteins can support focus and memory.',
        'points' => [
            ['Include healthy fats', 'Walnuts, flaxseed, and fish support neuronal function and cognitive stability.'],
            ['Choose antioxidant-rich foods', 'Berries, leafy greens, and cocoa help reduce oxidative stress.'],
            ['Stabilize energy levels', 'Avoid high-sugar spikes; prefer balanced meals with protein and fiber.']
        ],
        'tip' => 'Hydration and sleep are as important as food for mental performance.'
    ],
    'dealing-with-allergies' => [
        'title' => 'Dealing with Allergies',
        'category' => 'Medical',
        'image' => 'dealwithallergie.png',
        'intro' => 'Allergy symptoms can be controlled better when you combine trigger management with timely treatment. Prevention reduces symptom intensity.',
        'points' => [
            ['Identify your triggers', 'Dust, pollen, pets, and seasonal changes are common causes. Track when symptoms flare.'],
            ['Improve indoor hygiene', 'Use clean bedding, vacuum regularly, and keep humidity controlled to reduce allergens.'],
            ['Use medicines correctly', 'Follow proper antihistamine timing and avoid overuse of nasal decongestants.']
        ],
        'tip' => 'Breathing difficulty or swelling needs urgent medical attention.'
    ],
    'post-workout-meals' => [
        'title' => 'Post-Workout Meals',
        'category' => 'Fitness',
        'image' => 'postworkout.png',
        'intro' => 'Your post-workout meal drives recovery quality. The right combination of protein, carbs, and fluids helps muscle repair and energy restoration.',
        'points' => [
            ['Eat within 45 to 60 minutes', 'This helps replenish glycogen and supports better muscle protein synthesis.'],
            ['Balance protein and carbs', 'Use options like eggs plus toast, yogurt plus fruit, or whey with oats.'],
            ['Rehydrate smartly', 'Replace fluid losses with water and electrolytes after intense sweat sessions.']
        ],
        'tip' => 'Keep post-workout meals light but nutrient-dense, not greasy or heavy.'
    ],
    'hydration-myths' => [
        'title' => 'Hydration Myths',
        'category' => 'Lifestyle',
        'image' => 'hydrationmyth.png',
        'intro' => 'Hydration needs are not one-size-fits-all. Climate, activity level, diet, and health status all change how much fluid your body needs.',
        'points' => [
            ['The 8-glass rule is a baseline', 'Some people need more or less depending on weather, workouts, and routine.'],
            ['Urine color is a useful signal', 'Pale yellow usually indicates adequate hydration for most adults.'],
            ['Fluids include more than water', 'Soups, fruits, and oral fluids can also contribute to daily hydration.']
        ],
        'tip' => 'Increase fluids during fever, diarrhea, and heavy exercise to avoid dehydration.'
    ]
];

if (!isset($articles[$slug])) {
    http_response_code(404);
}

$article = isset($articles[$slug]) ? $articles[$slug] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $article ? $article['title'] : 'Article Not Found'; ?> | BioPharma Insights</title>
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
        <?php if (!$article): ?>
        <div class="bg-white border border-slate-100 rounded-[2rem] p-10 text-center shadow-sm">
            <h1 class="text-3xl font-black text-slate-900 mb-4">Article Not Found</h1>
            <p class="text-slate-500 mb-6">The article you requested is not available.</p>
            <a href="insights.php" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-700 transition">
                <i class="fas fa-arrow-left"></i>
                Back to Latest Articles
            </a>
        </div>
        <?php else: ?>
        <article class="bg-white border border-slate-100 rounded-[2.5rem] shadow-sm overflow-hidden">
            <img src="images/<?php echo rawurlencode($article['image']); ?>" alt="<?php echo $article['title']; ?>" class="w-full h-72 object-cover">
            <div class="p-8 md:p-10">
                <span class="inline-block text-[10px] font-black text-emerald-700 bg-emerald-50 px-4 py-2 rounded-xl uppercase tracking-widest mb-5">
                    <?php echo $article['category']; ?>
                </span>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-6"><?php echo $article['title']; ?></h1>
                <p class="text-slate-600 leading-relaxed mb-8"><?php echo $article['intro']; ?></p>

                <?php foreach ($article['points'] as $index => $point): ?>
                <h2 class="text-2xl font-black text-slate-900 mb-3"><?php echo ($index + 1) . '. ' . $point[0]; ?></h2>
                <p class="text-slate-600 leading-relaxed mb-6"><?php echo $point[1]; ?></p>
                <?php endforeach; ?>

                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 text-sm text-emerald-900 font-medium">
                    Tip: <?php echo $article['tip']; ?>
                </div>

                <div class="mt-8">
                    <a href="insights.php" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-700 transition">
                        <i class="fas fa-arrow-left"></i>
                        Back to Latest Articles
                    </a>
                </div>
            </div>
        </article>
        <?php endif; ?>
    </main>

    <footer class="p-8 text-center bg-white border-t border-slate-100">
        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">&copy; 2026 BioPharma Insights</p>
    </footer>
</body>
</html>
