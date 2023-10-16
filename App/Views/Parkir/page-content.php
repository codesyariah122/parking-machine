

<section id="display-booth" class="grid grid-cols-1 w-full py-36 mb-36">
	<div class="col-span-full">
		<div id="toastContainer"></div>
		<?php require_once 'Contents/parking-ticket.php'?>
	</div>
	<div class="col-span-full">
		<div class="flex justify-between space-x-24">
			<!-- Slot parkir -->
			<div>
				<div class="grid grid-cols-4 booth1"></div>
			</div>

			<!-- Tambahkan elemen efek jalan raya vertikal di sini -->
			<div class="w-1/64 grid grid-cols-1 justify-items-center marka gap-y-12 py-6">
				<div class="col-span-full slot-marka"></div>
			</div>

			<div>
				<div class="grid grid-cols-4 booth2"></div>
			</div>
		</div>
	</div>

	<div class="col-span-full py-12">
		<div class="flex justify-center mx-auto z-50">
			<div class="w-[250px] h-36">
				<div id="ambil-tiket-parkir" class="bg-gray-600 rounded-lg p-6">
					<h3 class="text-xl text-center font-bold">Ambil Tiket</h3>
					<button type="button" class="ambil-tiket w-full mt-4 focus:outline-none text-gray-700 font-sans font-bold bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 rounded-lg text-sm px-5 py-2.5 mb-2 dark:focus:ring-yellow-900">Ambil</button>
				</div>

				<div id="bayar" class="hidden bayar bg-gray-600 rounded-lg p-6">
					<h3 class="text-xl text-center font-bold">Bayar Sekarang !!</h3>
					<button type="button" class="w-full mt-4 focus:outline-none text-gray-700 font-sans font-bold bg-green-400 hover:bg-green-500 focus:ring-4 focus:ring-green-300 rounded-lg text-sm px-5 py-2.5 mb-2 dark:focus:ring-green-900">Bayar Disini</button>
				</div>

				<!-- Main modal -->
				<div id="defaultModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
					<div class="relative w-full max-w-2xl max-h-full">
						<!-- Modal content -->
						<div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
							<!-- Modal header -->
							<div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
								<h3 class="text-xl font-semibold text-gray-900 dark:text-white">
									Input Vehicle
								</h3>
								<button type="button" class="hide-modal text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="defaultModal">
									<svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
										<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
									</svg>
									<span class="sr-only">Close modal</span>
								</button>
							</div>
							<!-- Modal body -->
							<div class="p-6 space-y-6">
								<label for="vehicles" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Kendaraan</label>
								<select id="vehicles" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
									<option value=''>Pilih Jenis</option>
									<?php foreach($vehicles as $vehicle): ?>
										<option value="<?=strtoupper($vehicle['id'])?>"
											data-value="<?=strtoupper($vehicle['type'])?>"><?=strtoupper($vehicle['type'])?></option>
										<?php endforeach;?>
									</select>
								</div>
								<!-- Modal footer -->
								<div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
									<button data-modal-hide="defaultModal" type="button" class="input-data text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I accept</button>
									<button data-modal-hide="defaultModal" type="button" class="hide-modal text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Decline</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</section>