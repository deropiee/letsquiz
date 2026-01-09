<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
    @vite(['resources/css/app.css', 'resources/css/portfolio.css', 'resources/js/app.js', 'resources/js/portfolio.js'])
</head>
<body class="min-h-screen relative" style="background-color: #000000;">
    <!-- Navigation Menu -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-center">
                <div class="nav-menu glass-nav bg-white/10 backdrop-blur-md rounded-full px-4 md:px-8 py-2 md:py-3 border border-white/20">
                    <ul class="flex items-center gap-3 md:gap-8 text-xs md:text-sm font-medium">
                        <li><a href="#home" class="nav-link text-white/70 hover:text-white transition-colors">Home</a></li>
                        <li><a href="#over-mij" class="nav-link text-white/70 hover:text-white transition-colors">Over Mij</a></li>
                        <li><a href="#skills" class="nav-link text-white/70 hover:text-white transition-colors">Skills</a></li>
                        <li><a href="#projecten" class="nav-link text-white/70 hover:text-white transition-colors">Projecten</a></li>
                        <li><a href="#ervaring" class="nav-link text-white/70 hover:text-white transition-colors">Ervaring</a></li>
                        <li><a href="#contact" class="nav-link text-white/70 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Animated Blue Circles Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="blob-circle absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500 rounded-full mix-blend-screen filter blur-3xl opacity-20 animate-blob"></div>
        <div class="blob-circle absolute top-1/3 right-1/4 w-96 h-96 bg-cyan-500 rounded-full mix-blend-screen filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="blob-circle absolute bottom-1/4 left-1/3 w-96 h-96 bg-indigo-500 rounded-full mix-blend-screen filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <div class="container mx-auto px-4 py-16 relative z-10">
        <div class="max-w-6xl mx-auto">

            <!-- Hero Section -->
            <div id="home" class="hero-section text-center mb-12 md:mb-20 animate-fade-in pt-24 md:pt-20">
                <div class="mb-4 md:mb-6">
                    <div class="hero-avatar w-24 h-24 md:w-32 md:h-32 mx-auto bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-5xl md:text-6xl mb-4 md:mb-6 shadow-2xl">
                        <span class="float-icon">😎</span>
                    </div>
                </div>
                <h1 class="hero-title text-5xl md:text-7xl font-extrabold text-white mb-4 drop-shadow-2xl">
                    <span class="gradient-text">Yasin Ün</span>
                </h1>
                <p class="text-2xl md:text-3xl text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 font-bold typewriter">
                    Software Developer Student
                </p>
                <div class="flex flex-wrap justify-center gap-2 md:gap-4 text-gray-300 text-xs md:text-base px-2">
                    <span class="info-badge flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full border border-white/10 hover:border-blue-500/50 hover:bg-blue-500/10 transition-all cursor-default">📍 Benthuizen, Nederland</span>
                    <span class="info-badge flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full border border-white/10 hover:border-purple-500/50 hover:bg-purple-500/10 transition-all cursor-default">📧 yasinun39610@gmail.com</span>
                    <span class="info-badge flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full border border-white/10 hover:border-cyan-500/50 hover:bg-cyan-500/10 transition-all cursor-default">📱 +31 06 39263934</span>
                </div>
                <div class="scroll-indicator mt-12">
                    <div class="mouse"></div>
                    <p class="text-gray-500 text-sm mt-2">Scroll naar beneden</p>
                </div>
            </div>

            <!-- Over Mij Section -->
            <div id="over-mij" class="reveal-section mb-12 md:mb-20 scroll-mt-20 md:scroll-mt-24">
                <h2 class="section-title text-2xl md:text-4xl font-bold text-white mb-6 md:mb-8 text-center">Over Mij</h2>
                <div class="glass-card bg-white/5 backdrop-blur-sm rounded-2xl p-4 md:p-8 border border-white/10 hover:border-blue-500/30 transition-all duration-500">
                    <p class="reveal-child text-gray-300 text-base md:text-lg leading-relaxed mb-4">
                        Hi, Ik ben Yasin, ben 19 jaar en studeer Software Development aan ROC Aventus. Ik ben eigenlijk altijd bezig met code, of het nu gaat om het bouwen van vette websites of het maken van games.
                    </p>
                    <p class="reveal-child text-gray-300 text-base md:text-lg leading-relaxed">
                        Ik hou ervan om dingen te maken die niet alleen goed werken, maar die er ook gewoon strak uitzien voor de gebruiker. Mijn doel? Een all-round full-stack developer worden die toffe, nieuwe dingen bouwt.
                    </p>
                </div>
            </div>

            <!-- Skills Section -->
            <div id="skills" class="reveal-section mb-12 md:mb-20 scroll-mt-20 md:scroll-mt-24">
                <h2 class="section-title text-2xl md:text-4xl font-bold text-white mb-6 md:mb-8 text-center">Vaardigheden</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                    <!-- Frontend -->
                    <div class="skill-card reveal-child glass-card bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-white/10 hover:border-blue-500/50 transition-all duration-300 group">
                        <div class="skill-icon text-4xl mb-4 group-hover:scale-110 transition-transform">🎨</div>
                        <h3 class="text-xl font-bold text-blue-400 mb-4">Frontend</h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="skill-tag px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-sm hover:bg-blue-500/40 transition-all cursor-default">HTML/CSS</span>
                            <span class="skill-tag px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-sm hover:bg-blue-500/40 transition-all cursor-default">JavaScript</span>
                            <span class="skill-tag px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-sm hover:bg-blue-500/40 transition-all cursor-default">Tailwind CSS</span>
                            <span class="skill-tag px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-sm hover:bg-blue-500/40 transition-all cursor-default">Figma</span>
                        </div>
                        <div class="skill-bar mt-4 h-1 bg-white/10 rounded-full overflow-hidden">
                            <div class="skill-progress h-full bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full" style="width: 0%" data-width="85%"></div>
                        </div>
                    </div>

                    <!-- Backend -->
                    <div class="skill-card reveal-child glass-card bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-white/10 hover:border-green-500/50 transition-all duration-300 group">
                        <div class="skill-icon text-4xl mb-4 group-hover:scale-110 transition-transform">⚙️</div>
                        <h3 class="text-xl font-bold text-green-400 mb-4">Backend</h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="skill-tag px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm hover:bg-green-500/40 transition-all cursor-default">PHP</span>
                            <span class="skill-tag px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm hover:bg-green-500/40 transition-all cursor-default">Laravel</span>
                            <span class="skill-tag px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm hover:bg-green-500/40 transition-all cursor-default">MySQL</span>
                            <span class="skill-tag px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm hover:bg-green-500/40 transition-all cursor-default">Django-python</span>
                        </div>
                        <div class="skill-bar mt-4 h-1 bg-white/10 rounded-full overflow-hidden">
                            <div class="skill-progress h-full bg-gradient-to-r from-green-500 to-teal-400 rounded-full" style="width: 0%" data-width="80%"></div>
                        </div>
                    </div>

                    <!-- Tools & Other -->
                    <div class="skill-card reveal-child glass-card bg-white/5 backdrop-blur-sm rounded-xl p-6 border border-white/10 hover:border-purple-500/50 transition-all duration-300 group">
                        <div class="skill-icon text-4xl mb-4 group-hover:scale-110 transition-transform">🛠️</div>
                        <h3 class="text-xl font-bold text-purple-400 mb-4">Tools & Other</h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="skill-tag px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm hover:bg-purple-500/40 transition-all cursor-default">Git/GitHub</span>
                            <span class="skill-tag px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm hover:bg-purple-500/40 transition-all cursor-default">Unity</span>
                            <span class="skill-tag px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm hover:bg-purple-500/40 transition-all cursor-default">C#</span>
                            <span class="skill-tag px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm hover:bg-purple-500/40 transition-all cursor-default">Python</span>
                            <span class="skill-tag px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm hover:bg-purple-500/40 transition-all cursor-default">VS Code</span>
                        </div>
                        <div class="skill-bar mt-4 h-1 bg-white/10 rounded-full overflow-hidden">
                            <div class="skill-progress h-full bg-gradient-to-r from-purple-500 to-pink-400 rounded-full" style="width: 0%" data-width="75%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projecten Section - Scrollytelling -->
            <div id="projecten" class="mb-6 md:mb-8 scroll-mt-20 md:scroll-mt-24">
                <h2 class="text-2xl md:text-4xl font-bold text-white mb-4 text-center">Mijn Projecten</h2>
                <p class="text-center text-gray-400 text-xs md:text-sm">Scroll om te ontdekken ↓</p>
            </div>

            <!-- Sticky Cards Container -->
            <div class="relative" style="min-height: 310vh;">

                <!-- Project 1 -->
                <div class="sticky top-[5vh] z-10 flex items-start justify-center px-2 md:px-8 pt-4">
                    <div class="project-card blue-card relative overflow-hidden w-full max-w-4xl min-h-[60vh] md:min-h-[70vh] bg-gradient-to-br from-blue-900/90 to-blue-800/70 backdrop-blur-md rounded-3xl p-4 md:p-12 border border-blue-500/30 shadow-2xl shadow-blue-500/20 cursor-pointer" onclick="window.location.href='{{ route('home') }}'">
                        <div class="shimmer-effect absolute inset-0 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10 text-center mb-6">
                            <span class="float-icon inline-block text-6xl md:text-8xl">🤔</span>
                        </div>
                        <h3 class="relative z-10 text-3xl md:text-5xl font-bold text-white text-center mb-4">LetsQuiz</h3>
                        <p class="relative z-10 text-lg md:text-xl text-gray-300 text-center mb-4">Een interactieve quiz applicatie gemaakt met Laravel</p>

                        <!-- Test Account Info -->
                        <div class="relative z-10 flex flex-wrap justify-center gap-3 mb-6">
                            <div class="flex items-center gap-2 px-4 py-2 bg-blue-500/20 rounded-full border border-blue-400/30">
                                <span class="text-blue-300 text-sm">🔐 Test Account:</span>
                                <code class="text-white text-sm font-mono bg-white/10 px-2 py-0.5 rounded">a@a.com</code>
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 bg-blue-500/20 rounded-full border border-blue-400/30">
                                <span class="text-blue-300 text-sm">🔑 Wachtwoord:</span>
                                <code class="text-white text-sm font-mono bg-white/10 px-2 py-0.5 rounded">12345678</code>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-3 bg-blue-500/10 rounded-xl">
                                <div class="text-2xl mb-1">🎮</div>
                                <p class="text-xs text-blue-300">Quiz Games</p>
                            </div>
                            <div class="text-center p-3 bg-blue-500/10 rounded-xl">
                                <div class="text-2xl mb-1">🎰</div>
                                <p class="text-xs text-blue-300">Wheelspin</p>
                            </div>
                            <div class="text-center p-3 bg-blue-500/10 rounded-xl">
                                <div class="text-2xl mb-1">💇‍♂️</div>
                                <p class="text-xs text-blue-300">Cosmetics</p>
                            </div>
                            <div class="text-center p-3 bg-blue-500/10 rounded-xl">
                                <div class="text-2xl mb-1">💎</div>
                                <p class="text-xs text-blue-300">Gems</p>
                            </div>
                        </div>

                        <div class="relative z-10 flex flex-wrap justify-center gap-3 mb-4">
                            <span class="px-4 py-2 bg-blue-500/20 text-blue-300 rounded-full text-sm">Laravel</span>
                            <span class="px-4 py-2 bg-blue-500/20 text-blue-300 rounded-full text-sm">Tailwind CSS</span>
                            <span class="px-4 py-2 bg-blue-500/20 text-blue-300 rounded-full text-sm">MySQL</span>
                            <span class="px-4 py-2 bg-blue-500/20 text-blue-300 rounded-full text-sm">Blade</span>
                            <span class="px-4 py-2 bg-blue-500/20 text-blue-300 rounded-full text-sm">Javascript</span>
                        </div>

                        <p class="relative z-10 text-sm text-gray-400 text-center mb-6">📚 School groepsproject 1 - consortiumberoepsonderwijs</p>

                        <!-- What I learned -->
                        <div class="relative z-10 bg-white/5 rounded-xl p-4 mb-6">
                            <p class="text-sm text-gray-400 text-center">💡 <span class="text-white">Geleerd:</span> MVC architectuur, database relaties, authenticatie, Blade templating, team samenwerking met Git</p>
                        </div>

                        <div class="relative z-10 text-center">
                            <a href="{{ route('home') }}" class="pulse-cta inline-flex items-center gap-2 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg">
                                Bekijk Project →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Project 2 -->
                <div class="sticky top-[7vh] z-20 flex items-start justify-center px-2 md:px-8 pt-4">
                    <div class="project-card cyan-card relative overflow-hidden w-full max-w-4xl min-h-[60vh] md:min-h-[70vh] bg-gradient-to-br from-cyan-900/90 to-sky-800/70 backdrop-blur-md rounded-3xl p-4 md:p-12 border border-cyan-500/30 shadow-2xl shadow-cyan-500/20 cursor-pointer" onclick="window.location.href='{{ route('weather') }}'">
                        <div class="shimmer-effect absolute inset-0 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10 text-center mb-6">
                            <span class="float-icon inline-block text-6xl md:text-8xl">🌦️</span>
                        </div>
                        <h3 class="relative z-10 text-3xl md:text-5xl font-bold text-white text-center mb-4">Weer App</h3>
                        <p class="relative z-10 text-lg md:text-xl text-gray-300 text-center mb-6">Een Laravel-ReactJS weather app met live zoeken en 3-daagse forecast</p>

                        <!-- Features -->
                        <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-3 bg-cyan-500/10 rounded-xl">
                                <div class="text-2xl mb-1">🔎</div>
                                <p class="text-xs text-cyan-300">Search</p>
                            </div>
                            <div class="text-center p-3 bg-cyan-500/10 rounded-xl">
                                <div class="text-2xl mb-1">📅</div>
                                <p class="text-xs text-cyan-300">3-Day Forecast</p>
                            </div>
                            <div class="text-center p-3 bg-cyan-500/10 rounded-xl">
                                <div class="text-2xl mb-1">🌐</div>
                                <p class="text-xs text-cyan-300">API</p>
                            </div>
                            <div class="text-center p-3 bg-cyan-500/10 rounded-xl">
                                <div class="text-2xl mb-1">⚡</div>
                                <p class="text-xs text-cyan-300">Vite</p>
                            </div>
                        </div>

                        <div class="relative z-10 flex flex-wrap justify-center gap-3 mb-4">
                            <span class="px-4 py-2 bg-cyan-500/20 text-cyan-300 rounded-full text-sm">React</span>
                            <span class="px-4 py-2 bg-cyan-500/20 text-cyan-300 rounded-full text-sm">Laravel</span>
                            <span class="px-4 py-2 bg-cyan-500/20 text-cyan-300 rounded-full text-sm">API's</span>
                            <span class="px-4 py-2 bg-cyan-500/20 text-cyan-300 rounded-full text-sm">Tailwind CSS</span>
                        </div>

                        <!-- What I learned -->
                        <div class="relative z-10 bg-white/5 rounded-xl p-4 mb-6">
                            <p class="text-sm text-gray-400 text-center">💡 <span class="text-white">Geleerd:</span> React state/hooks, API fetch, environment variables, Vite bundling</p>
                        </div>

                        <div class="relative z-10 text-center">
                            <a href="{{ route('weather') }}" class="pulse-cta inline-flex items-center gap-2 px-8 py-4 bg-cyan-600 hover:bg-cyan-700 text-white text-lg font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg">
                                Bekijk Project →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Project 3 -->
                <div class="sticky top-[9vh] z-30 flex items-start justify-center px-2 md:px-8 pt-4">
                    <div class="project-card orange-card relative overflow-hidden w-full max-w-4xl min-h-[60vh] md:min-h-[70vh] bg-gradient-to-br from-orange-900/90 to-red-800/70 backdrop-blur-md rounded-3xl p-4 md:p-12 border border-orange-500/30 shadow-2xl shadow-orange-500/20 cursor-pointer" onclick="window.open('https://halalmf.itch.io/big-randy', '_blank')">
                        <div class="shimmer-effect absolute inset-0 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10 text-center mb-6">
                            <span class="float-icon inline-block text-6xl md:text-8xl">👻</span>
                        </div>
                        <h3 class="relative z-10 text-3xl md:text-5xl font-bold text-white text-center mb-4">Big Randy</h3>
                        <p class="relative z-10 text-lg md:text-xl text-gray-300 text-center mb-6">Een Unity horror spel waar je uit Big Randy's huis moet ontsnappen</p>

                        <!-- Features -->
                        <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-3 bg-orange-500/10 rounded-xl">
                                <div class="text-2xl mb-1">👾</div>
                                <p class="text-xs text-orange-300">AI Enemy</p>
                            </div>
                            <div class="text-center p-3 bg-orange-500/10 rounded-xl">
                                <div class="text-2xl mb-1">🔒</div>
                                <p class="text-xs text-orange-300">Puzzels</p>
                            </div>
                            <div class="text-center p-3 bg-orange-500/10 rounded-xl">
                                <div class="text-2xl mb-1">🌲</div>
                                <p class="text-xs text-orange-300">Level Design</p>
                            </div>
                            <div class="text-center p-3 bg-orange-500/10 rounded-xl">
                                <div class="text-2xl mb-1">🧊</div>
                                <p class="text-xs text-orange-300">3D modeling</p>
                            </div>
                        </div>

                        <div class="relative z-10 flex flex-wrap justify-center gap-3 mb-4">
                            <span class="px-4 py-2 bg-orange-500/20 text-orange-300 rounded-full text-sm">Unity</span>
                            <span class="px-4 py-2 bg-orange-500/20 text-orange-300 rounded-full text-sm">C#</span>
                            <span class="px-4 py-2 bg-orange-500/20 text-orange-300 rounded-full text-sm">Horror</span>
                            <span class="px-4 py-2 bg-orange-500/20 text-orange-300 rounded-full text-sm">3D</span>
                        </div>

                        <p class="relative z-10 text-sm text-gray-400 text-center mb-4">📚 School project 2</p>

                        <!-- What I learned -->
                        <div class="relative z-10 bg-white/5 rounded-xl p-4 mb-4">
                            <p class="text-sm text-gray-400 text-center">💡 <span class="text-white">Geleerd:</span> Unity game development, AI pathfinding, 3D modeling basics, sound integration, game physics</p>
                        </div>

                        <p class="relative z-10 text-sm text-white/80 text-center mb-6 bg-white/10 inline-block mx-auto px-4 py-2 rounded-full">🔑 Wachtwoord: 1234</p>

                        <div class="relative z-10 text-center">
                            <a href="https://halalmf.itch.io/big-randy" target="_blank" class="pulse-cta inline-flex items-center gap-2 px-8 py-4 bg-orange-600 hover:bg-orange-700 text-white text-lg font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg">
                                Speel Nu →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Project 4 -->
                <div class="sticky top-[11vh] z-40 flex items-start justify-center px-2 md:px-8 pt-4">
                    <div class="project-card green-card relative overflow-hidden w-full max-w-4xl min-h-[60vh] md:min-h-[70vh] bg-gradient-to-br from-green-900/90 to-teal-800/70 backdrop-blur-md rounded-3xl p-4 md:p-12 border border-green-500/30 shadow-2xl shadow-green-500/20 cursor-pointer" onclick="window.open('https://halalmf.itch.io/reality-check', '_blank')">
                        <div class="shimmer-effect absolute inset-0 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10 text-center mb-6">
                            <span class="float-icon inline-block text-6xl md:text-8xl">🤖</span>
                        </div>
                        <h3 class="relative z-10 text-3xl md:text-5xl font-bold text-white text-center mb-4">Reality Check</h3>
                        <p class="relative z-10 text-lg md:text-xl text-gray-300 text-center mb-6">Een Unity spel waar je deepfake moet kunnen herkennen</p>

                        <!-- Features -->
                        <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="text-center p-3 bg-green-500/10 rounded-xl">
                                <div class="text-2xl mb-1">🧠</div>
                                <p class="text-xs text-green-300">AI Detection</p>
                            </div>
                            <div class="text-center p-3 bg-green-500/10 rounded-xl">
                                <div class="text-2xl mb-1">🎯</div>
                                <p class="text-xs text-green-300">Mini Games</p>
                            </div>
                            <div class="text-center p-3 bg-green-500/10 rounded-xl">
                                <div class="text-2xl mb-1">📰</div>
                                <p class="text-xs text-green-300">Real News</p>
                            </div>
                            <div class="text-center p-3 bg-green-500/10 rounded-xl">
                                <div class="text-2xl mb-1">🏅</div>
                                <p class="text-xs text-green-300">Hackathon</p>
                            </div>
                        </div>

                        <div class="relative z-10 flex flex-wrap justify-center gap-3 mb-4">
                            <span class="px-4 py-2 bg-green-500/20 text-green-300 rounded-full text-sm">Unity</span>
                            <span class="px-4 py-2 bg-green-500/20 text-green-300 rounded-full text-sm">C#</span>
                            <span class="px-4 py-2 bg-green-500/20 text-green-300 rounded-full text-sm">AI/Deepfake</span>
                            <span class="px-4 py-2 bg-green-500/20 text-green-300 rounded-full text-sm">Educational</span>
                        </div>

                        <p class="relative z-10 text-sm text-gray-400 text-center mb-4">🏆 Hackathon Aventus - Competitie Project</p>

                        <!-- What I learned -->
                        <div class="relative z-10 bg-white/5 rounded-xl p-4 mb-4">
                            <p class="text-sm text-gray-400 text-center">💡 <span class="text-white">Geleerd:</span> Werken onder tijdsdruk, creatief probleem oplossen, AI integratie, presenteren aan jury</p>
                        </div>

                        <p class="relative z-10 text-sm text-white/80 text-center mb-6 bg-white/10 inline-block mx-auto px-4 py-2 rounded-full">🔑 Wachtwoord: 1234</p>

                        <div class="relative z-10 text-center">
                            <a href="https://halalmf.itch.io/reality-check" target="_blank" class="pulse-cta inline-flex items-center gap-2 px-8 py-4 bg-green-600 hover:bg-green-700 text-white text-lg font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg">
                                Speel Nu →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Project 5 -->
                <div class="sticky top-[13vh] z-50 flex items-start justify-center px-2 md:px-8 pt-4">
                    <div class="project-card purple-card relative overflow-hidden w-full max-w-4xl min-h-[60vh] md:min-h-[70vh] bg-gradient-to-br from-purple-900/90 to-pink-800/70 backdrop-blur-md rounded-3xl p-4 md:p-12 border border-purple-500/30 shadow-2xl shadow-purple-500/20 cursor-pointer" onclick="window.open('https://github.com/yasinun', '_blank')">
                        <div class="shimmer-effect absolute inset-0 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10 text-center mb-6">
                            <span class="float-icon inline-block text-6xl md:text-8xl">💻</span>
                        </div>
                        <h3 class="relative z-10 text-3xl md:text-5xl font-bold text-white text-center mb-4">Mijn GitHub</h3>
                        <p class="relative z-10 text-lg md:text-xl text-gray-300 text-center mb-6">Mijn code repositories, school contributies zijn niet zichtbaar 😞</p>

                        <!-- GitHub Stats -->
                        <div class="relative z-10 flex flex-wrap justify-center gap-4 mb-6">
                            <div class="text-center p-3 bg-purple-500/10 rounded-xl min-w-[100px]">
                                <div class="text-2xl mb-1">📁</div>
                                <p class="text-xs text-purple-300">Repositories</p>
                            </div>
                            <div class="text-center p-3 bg-purple-500/10 rounded-xl min-w-[100px]">
                                <div class="text-2xl mb-1">🔥</div>
                                <p class="text-xs text-purple-300">Contributions</p>
                            </div>
                        </div>

                        <div class="relative z-10 flex flex-wrap justify-center gap-3 mb-6">
                            <span class="px-4 py-2 bg-purple-500/20 text-purple-300 rounded-full text-sm">Repositories</span>
                            <span class="px-4 py-2 bg-purple-500/20 text-purple-300 rounded-full text-sm">Code</span>
                            <span class="px-4 py-2 bg-purple-500/20 text-purple-300 rounded-full text-sm">Version Control</span>
                        </div>

                        <!-- Info box -->
                        <div class="relative z-10 bg-white/5 rounded-xl p-4 mb-6">
                            <p class="text-sm text-gray-400 text-center">💡 <span class="text-white">Technologieën:</span> PHP, JavaScript, C#, Python, HTML/CSS, en meer...</p>
                        </div>

                        <div class="relative z-10 text-center">
                            <a href="https://github.com/yasinun" target="_blank" class="pulse-cta inline-flex items-center gap-2 px-8 py-4 bg-purple-600 hover:bg-purple-700 text-white text-lg font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg">
                                Bekijk GitHub →
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Opleiding & Werkervaring Section -->
            <div id="ervaring" class="reveal-section mb-12 md:mb-20 mt-12 md:mt-20 scroll-mt-20 md:scroll-mt-24">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12">

                    <!-- Opleiding Column -->
                    <div>
                        <h2 class="section-title-plain text-3xl md:text-4xl font-bold text-white mb-8 text-center lg:text-left">🎓 Opleiding</h2>
                        <div class="timeline-container space-y-6 relative">
                            <div class="timeline-line absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-blue-500 to-green-500"></div>

                            <!-- Software Developer -->
                            <div class="timeline-item reveal-child glass-card bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:border-blue-500/30 transition-all duration-500 ml-8 relative group">
                                <div class="timeline-dot absolute -left-[1.75rem] top-6 w-3 h-3 bg-blue-500 rounded-full border-2 border-black group-hover:scale-125 transition-transform"></div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-blue-500/20 text-blue-400 text-xs rounded-full">Huidig</span>
                                </div>
                                <h3 class="text-lg font-bold text-white mb-1">Software Developer</h3>
                                <p class="text-blue-400 text-sm mb-2">ROC Aventus | Aug 2023 - Heden</p>
                                <p class="text-gray-300 text-sm">
                                    Opleiding Software Developer met focus op webdevelopment, backend programmeren, databases en softwarearchitectuur.
                                </p>
                            </div>
                            <!-- MAVO Diploma -->
                            <div class="timeline-item reveal-child glass-card bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:border-green-500/30 transition-all duration-500 ml-8 relative group">
                                <div class="timeline-dot absolute -left-[1.75rem] top-6 w-3 h-3 bg-green-500 rounded-full border-2 border-black group-hover:scale-125 transition-transform"></div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Afgerond</span>
                                </div>
                                <h3 class="text-lg font-bold text-white mb-1">MAVO Diploma</h3>
                                <p class="text-green-400 text-sm mb-2">Het Stormink | Aug 2019 - Jun 2023</p>
                                <p class="text-gray-300 text-sm">
                                    MAVO diploma behaald met succes.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Werkervaring Column -->
                    <div>
                        <h2 class="section-title-plain text-3xl md:text-4xl font-bold text-white mb-8 text-center lg:text-left">💼 Werkervaring</h2>
                        <div class="timeline-container space-y-6 relative">
                            <div class="timeline-line absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-orange-500 to-purple-500"></div>

                            <!-- Stage/Werk 1 -->
                            <div class="timeline-item reveal-child glass-card bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:border-orange-500/30 transition-all duration-500 ml-8 relative group">
                                <div class="timeline-dot absolute -left-[1.75rem] top-6 w-3 h-3 bg-orange-500 rounded-full border-2 border-black group-hover:scale-125 transition-transform"></div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-orange-500/20 text-orange-400 text-xs rounded-full">Stage</span>
                                </div>
                                <h3 class="text-lg font-bold text-white mb-1">Front-end</h3>
                                <p class="text-orange-400 text-sm mb-2">Highbiza | Aug 2024 - Jan 2025</p>
                                <p class="text-gray-300 text-sm">
                                    Django-python stage gericht op front-end development en content vulling.
                                </p>
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <span class="px-2 py-1 bg-orange-500/10 text-orange-300 text-xs rounded-full">HTML/CSS</span>
                                    <span class="px-2 py-1 bg-orange-500/10 text-orange-300 text-xs rounded-full">Django</span>
                                </div>
                            </div>

                            <!-- Bijbaan/Werk 2 -->
                            <div class="timeline-item reveal-child glass-card bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:border-purple-500/30 transition-all duration-500 ml-8 relative group">
                                <div class="timeline-dot absolute -left-[1.75rem] top-6 w-3 h-3 bg-purple-500 rounded-full border-2 border-black group-hover:scale-125 transition-transform"></div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-purple-500/20 text-purple-400 text-xs rounded-full">Bijbaan</span>
                                </div>
                                <h3 class="text-lg font-bold text-white mb-1">Bezorger</h3>
                                <p class="text-purple-400 text-sm mb-2">Flink | Jan 2023 - Jan 2026</p>
                                <p class="text-gray-300 text-sm">
                                    Bezorgen van boodschappen en pakketten.
                                </p>
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <span class="px-2 py-1 bg-purple-500/10 text-purple-300 text-xs rounded-full">Klantvriendelijk</span>
                                    <span class="px-2 py-1 bg-purple-500/10 text-purple-300 text-xs rounded-full">Betrouwbaar</span>
                                </div>
                            </div>

                            <!-- parttime -->
                            <div class="timeline-item reveal-child glass-card bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:border-cyan-500/30 transition-all duration-500 ml-8 relative group">
                                <div class="timeline-dot absolute -left-[1.75rem] top-6 w-3 h-3 bg-cyan-500 rounded-full border-2 border-black group-hover:scale-125 transition-transform"></div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 bg-cyan-500/20 text-cyan-400 text-xs rounded-full">Part-time</span>
                                </div>
                                <h3 class="text-lg font-bold text-white mb-1">Software developer</h3>
                                <p class="text-cyan-400 text-sm mb-2">RCyberSecurity | Nov 2025 - Heden</p>
                                <p class="text-gray-300 text-sm">
                                    Ontwikkelen van beveiligingssoftware en tools voor klanten.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Contact Section -->
            <div id="contact" class="reveal-section text-center scroll-mt-20 md:scroll-mt-24">
                <h2 class="section-title text-2xl md:text-4xl font-bold text-white mb-6 md:mb-8">Contact</h2>
                <div class="contact-card glass-card bg-gradient-to-r from-blue-600/20 to-purple-600/20 backdrop-blur-sm rounded-2xl p-6 md:p-12 border border-white/10 hover:border-purple-500/30 transition-all duration-500 relative overflow-hidden">
                    <div class="shimmer-effect absolute inset-0 rounded-2xl pointer-events-none"></div>
                    <p class="reveal-child relative z-10 text-gray-300 text-base md:text-lg mb-6 md:mb-8">
                        Geïnteresseerd in samenwerking of heb je vragen? Neem gerust contact met me op!
                    </p>
                    <div class="reveal-child relative z-10 flex flex-col md:flex-row flex-wrap justify-center gap-3 md:gap-6">
                        <a href="mailto:yasinun39610@gmail.com" class="contact-btn group inline-flex items-center justify-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm md:text-base rounded-lg transition-all transform hover:scale-105 hover:shadow-lg hover:shadow-blue-500/25">
                            <span class="group-hover:animate-bounce">📧</span> Email me
                        </a>
                        <a href="https://github.com/yasinun" target="_blank" class="contact-btn group inline-flex items-center justify-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-purple-600 hover:bg-purple-700 text-white text-sm md:text-base rounded-lg transition-all transform hover:scale-105 hover:shadow-lg hover:shadow-purple-500/25">
                            <span class="group-hover:animate-bounce">💻</span> GitHub
                        </a>
                        <a href="https://linkedin.com/in/yasinun" target="_blank" class="contact-btn group inline-flex items-center justify-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-blue-700 hover:bg-blue-800 text-white text-sm md:text-base rounded-lg transition-all transform hover:scale-105 hover:shadow-lg hover:shadow-blue-500/25">
                            <span class="group-hover:animate-bounce">🔗</span> LinkedIn
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-16 text-center text-gray-500 text-sm reveal-section">
                <p class="hover:text-white transition-colors cursor-default">© 2025 Yasin Ün. Alle rechten voorbehouden.</p>
                <div class="mt-4 flex justify-center gap-4">
                    <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                    <span class="w-2 h-2 bg-purple-500 rounded-full animate-pulse animation-delay-2000"></span>
                    <span class="w-2 h-2 bg-cyan-500 rounded-full animate-pulse animation-delay-4000"></span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
