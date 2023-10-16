


<div class="relative overflow-x-auto shadow-md sm:rounded-lg w-full">
	<div class="col-span-full">
		<div id="toastContainer"></div>
	</div>

	<form class="mb-6">   
		<label for="search-data" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
		<div class="relative">
			<div class="absolute inset-y-11 left-0 flex items-center pl-3 pointer-events-none">
				<i class="fa-solid fa-magnifying-glass"></i>
			</div>

			<small class="text-red-600 italic">* Ketikan data yang ingin anda cari ...</small>
			<input type="search" id="search-data" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 h-10" placeholder="Filter data berdasarkan kode obat, jenis obat, nama obat dan kebutuhan per tahun ..." required>
		</div>
	</form>
	<table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
		<thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
			<tr>
				<th scope="col" class="p-4">
					<div class="flex items-center">
						<input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
						<label for="checkbox-all-search" class="sr-only">checkbox</label>
					</div>
				</th>
				<th scope="col" class="px-6 py-3">
					Payment Code
				</th>
				<th scope="col" class="px-6 py-3">
					Type
				</th>
				<th scope="col" class="px-6 py-3">
					Harga
				</th>
				<th scope="col" class="px-6 py-3">
					Duration
				</th>
				<th scope="col" class="px-6 py-3">
					Total
				</th>
				<th scope="col" class="px-6 py-3">
					Payment Date
				</th>
				<th scope="col" class="px-6 py-3">
					Action
				</th>
			</tr>
		</thead>
		<tbody id="payments"></tbody>
	</table>
</div>

<div class="flex justify-center py-6">
	<div>
		<nav aria-label="Page navigation example">
			<ul id="pagination" class="inline-flex -space-x-px text-sm bg-gray-900"></ul>
		</nav>
	</div>
</div>

<div class="flex justify-center space-x-4 py-12">
	<div>
		<div class="w-full max-w-md p-4 bg-gray-900 border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
			<div class="flex items-center justify-between mb-4">
				<h5 class="text-xl font-bold leading-none text-white dark:text-white">Daily Income</h5>
			</div>
			<div class="flow-root">
				<ul id="payments-daily" role="list" class="divide-y divide-gray-200 dark:divide-gray-700"></ul>
			</div>
		</div>
	</div>

	<div>
		<div class="w-full max-w-md p-4 bg-gray-900 border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
			<div class="flex items-center justify-between mb-4">
				<h5 class="text-xl font-bold leading-none text-white dark:text-white">Monthly Income</h5>
			</div>
			<div class="flow-root">
				<ul id="payments-total" role="list" class="divide-y divide-gray-200 dark:divide-gray-700"></ul>
			</div>
		</div>
	</div>
</div>