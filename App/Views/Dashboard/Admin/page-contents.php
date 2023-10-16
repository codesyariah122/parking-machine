
<section class="content-header p-8 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
	<div class="col-span-full">
		<div id="toastContainer"></div>
	</div>
	<div class="flex justify-center">
		<div>
			<h1 class="text-4xl font-bold">
				Welcome <?php if(isset($_SESSION['name'])): echo $_SESSION['name']; endif;?>
			</h1>
		</div>
	</div>

	<div class="flex justify-center py-6">
		<div>
			<div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
				<span class="font-medium">You <?=$_SESSION['status'] === 1 ? 'Onlie' : 'Offline'; ?>!</span> as Administrator.
			</div>
		</div>
	</div>
</section>

<section class="content-dashboard mt-6 p-8 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 bg-[#2A2A32] dark:bg-[#2A2A32]">
	<div class="flex justify-center">
		<div>
			<canvas id="myChart" width="800" height="400" class="bg-gray-50 text-gray-800"></canvas>
		</div>

		<div id="chart-container"  class="bg-gray-50 text-gray-800 w-[800px] h-[400px]">
		</div>

	</div>


	<div class="flex justify-center space-x-12 py-12 mb-12">
		<div>
			<h2 class="px-24 text-2xl font-sans font-bold">Daily Most Vehicles</h2>
			<canvas id="myPieChartDaily" class="bg-gray-50 text-gray-800"></canvas>
		</div>
		<div>
			<h2 class="px-24 text-2xl font-sans font-bold">Monthly Most Vehicles</h2>
			<canvas id="myPieChartMonthly" class="bg-gray-50 text-gray-800"></canvas>
		</div>
	</div>
</section>

