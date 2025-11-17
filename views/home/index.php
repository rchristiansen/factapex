<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FactApex - Soluciones de Factoring</title>
    <link href="<?=BASE_PATH?>/public/assets/css/output.css" rel="stylesheet">
    <link href="<?=BASE_PATH?>/public/assets/css/animations.css" rel="stylesheet">
</head>
<body class="bg-[linear-gradient(to_bottom_right,#101E32,#101828,#102236)] pt-16">
    <?php include __DIR__ . '/../components/header.php'; ?>

    <!-- Hero Section -->
    <section class="relative">
        <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
            <div class="mr-auto place-self-center lg:col-span-7">
                <h1 class="flex items-center mb-4 opacity-0 animate-slide-center">
                    <span class="text-4xl font-extrabold tracking-tight md:text-5xl xl:text-6xl text-white">Fact</span>
                    <span class="text-4xl font-extrabold tracking-tight md:text-5xl xl:text-6xl text-orange-500">apex</span>
                </h1>
                <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400 opacity-0 animate-fade-up delay-200">
                    Soluciones financieras que impulsan el crecimiento de tu empresa.
                </p>
                <!-- <div class="flex gap-4 opacity-0 animate-scale delay-400">
                    <a href="<?=BASE_PATH?>/register" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg">
                        Empieza Ahora
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <a href="<?=BASE_PATH?>/contact" class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-white border border-gray-600 rounded-lg hover:bg-gray-800">
                        Contáctanos
                    </a>
                </div> -->
            </div>
            <div class="hidden lg:mt-0 lg:col-span-5 lg:flex opacity-0 animate-scale delay-500">
                <img src="<?=BASE_PATH?>/public/assets/img/dashboard-home.svg" alt="Hero image">
            </div>                
        </div>
    </section>

    <!-- Feature Section -->
    <!-- <section class="relative">
        <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
            <div class="max-w-screen-md mb-8 lg:mb-16">
                <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-white opacity-0 animate-slide-center">
                    Diseñado para empresas modernas
                </h2>
                <p class="text-gray-400 sm:text-xl opacity-0 animate-fade-up delay-200">
                    Aquí en Factapex, nos enfocamos en mercados donde la tecnología, la innovación y el capital pueden desbloquear el valor a largo plazo.
                </p>
            </div>
        </div>
    </section> -->

    <!-- Contact Section -->
    <section id="contact" class="relative py-16">
        <div class="max-w-screen-xl px-4 mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left side - Contact Info -->
                <div class="opacity-0 animate-slide-center">
                    <h2 class="text-3xl font-bold text-white mb-6">Contáctanos</h2>
                    <p class="text-gray-400 mb-8">Estamos aquí para ayudarte. Contáctanos para obtener más información sobre nuestros servicios de factoring.</p>
                    
                    <div class="space-y-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="p-4 bg-orange-500/10 rounded-lg">
                                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold">Teléfono</h3>
                                <p class="text-gray-400">+56 2 2345 6789</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="p-4 bg-orange-500/10 rounded-lg">
                                    <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold">Email</h3>
                                <p class="text-gray-400">contacto@factapex.cl</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right side - Contact Form -->
                <div class="opacity-0 animate-fade-up delay-200">
                    <form id="contactForm" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Nombre</label>
                                <input type="text" class="w-full px-4 py-3 text-gray-300 bg-gray-700/40 border border-gray-700 rounded-lg  focus:ring-2 focus:ring-orange-500 focus:border-transparent" required placeholder="Nombre">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                                <input type="email" class="w-full px-4 py-3 text-gray-300 bg-gray-700/40 border border-gray-700 rounded-lg  focus:ring-2 focus:ring-orange-500 focus:border-transparent" required placeholder="Email">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Mensaje</label>
                            <textarea rows="4" class="w-full px-4 py-3 text-gray-300 bg-gray-700/40 border border-gray-700 rounded-lg  focus:ring-2 focus:ring-orange-500 focus:border-transparent" required placeholder="mensaje"></textarea>
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-orange-500 hover:bg-orange-600  font-medium rounded-lg transition-colors">
                            Enviar mensaje
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../components/footer.php'; ?>
    
    <script src="<?=BASE_PATH?>/public/assets/js/flowbite.js"></script>
    <script src="<?=BASE_PATH?>/public/assets/js/smooth-scroll.js"></script>
    <script src="<?=BASE_PATH?>/public/assets/js/header.js"></script>
</body>
</html>