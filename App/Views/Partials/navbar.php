

<nav class="bg-[#43434a] fixed w-full z-20 top-0 left-0 border-b border-[#43434a] dark:border-[#43434a] shadow-lg drop-shadow-lg">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="/" class="flex items-center">
      <img src="<?=$logo?>" class="w-14 h-auto mr-3" alt="Parking-Machine-Logo">
      <span class="font-mono text-white self-center text-2xl font-semibold whitespace-nowrap dark:text-white"><?=APP_NAME?></span>
    </a>
    <div class="flex md:order-2">
      <a href="<?=$page === 'home' ? '/parkir' : '/'?>" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center mr-3 md:mr-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <?=$page === 'home' ? "Get Started": "Back"; ?>
      </a>
      <a href="/parkir" data-collapse-toggle="navbar-sticky" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-sticky" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
        </svg>
      </a>
    </div>
  </div>
</nav>

