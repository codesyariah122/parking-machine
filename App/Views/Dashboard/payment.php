

<section class="content-header p-8 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 bg-[#2A2A32] dark:bg-[#2A2A32]">
	<div class="flex justify-center py-4">
		<div>
			<div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
				<span class="font-medium font-sans text-2xl"><?=$title?></span>
			</div>
		</div>
	</div>
</section>

<!-- Table -->

<section id="displaying" class="main-content py-12 bg-[#2A2A32] dark:bg-[#2A2A32]">
	<div class="flex justify-center p-2 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
		<div>
			<div class="p-12 mb-4 text-sm text-blue-800 rounded-lg dark:text-blue-400" role="alert">
				<?php require_once 'Contents/PaymentTable.php'?>
			</div>
		</div>
	</div>
</section>