
<header class="fixed top-0 left-0 right-0 z-50 bg-opacity-90 backdrop-blur-sm transition-all duration-300">
    <nav class="bg-white border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800 px-4 lg:px-6 py-2.5">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
            <a href="<?=BASE_PATH?>/public/" class="flex items-center">
                <span class="text-2xl font-bold text-white">Fact<span class="text-orange-500">apex</span></span>
            </a>
            <div class="flex items-center lg:order-2">
                <a href="<?=BASE_PATH?>/public/login" 
                   class="text-white bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:ring-orange-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 focus:outline-none transition-colors">
                    Login
                </a>
                <button data-collapse-toggle="mobile-menu-2" type="button" class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="mobile-menu-2" aria-expanded="false">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <div class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
                <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                    <li>
                        <a href="<?=BASE_PATH?>/public/" class="block py-2 pr-4 pl-3 text-white lg:p-0" aria-current="page">Inicio</a>
                    </li>
                    <!-- <li>
                        <a href="#features" class="block py-2 pr-4 pl-3 text-gray-400 hover:text-white lg:p-0 smooth-scroll">Características</a>
                    </li> -->
                    <li>
                        <a href="#contact" class="block py-2 pr-4 pl-3 text-gray-400 hover:text-white lg:p-0 smooth-scroll">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>