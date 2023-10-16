


<div id="marketing-banner" tabindex="-1" class="fixed z-50 w-[600px] p-4 -translate-x-1/2 bg-gray-800 border border-yellow-700 rounded-lg shadow-lg drop-shadow-lg lg:max-w-7xl left-1/2 top-[100px] dark:bg-gray-800 dark:border-yellow-700">

	<div class="flex flex-col md:flex-row justify-between">		
		<div class="flex flex-col items-start mb-3 mr-4 md:items-center md:flex-row md:mb-0">
			<a href="/" class="flex items-center mb-2 md:pr-4 md:mr-4 md:mb-0">
				<img src="<?=$data['logo']?>" class="h-auto w-24 mr-2" alt="Flowbite Logo">
			</a>
		</div>

		<div class="flex items-center flex-shrink-0">
			<a href="https://ditusi.co.id/auth/register" class="px-5 py-2 mr-2 text-xs font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" target="_blank">Sign up</a>
			<button data-dismiss-target="#marketing-banner" type="button" class="flex-shrink-0 inline-flex justify-center w-10 h-10 items-center text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-4xl p-1.5 dark:hover:bg-gray-600 dark:hover:text-white">
				<i class="fa-solid fa-xmark"></i>
				<span class="sr-only">Close banner</span>
			</button>
		</div>
	</div>

	<div class="grid grid-cols-1 justify-items-center py-12">
		<div class="col-span-full">
			<h1 class="text-white font-sans text-4xl text-center mb-4"><?=APP_NAME?></h1>
			<img src="<?=$cta_image?>" class="w-[550px]">
		</div>
	</div>
</div>