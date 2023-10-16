const pagingStorage = localStorage.getItem("paging")
	? JSON.parse(localStorage.getItem("paging"))
	: null;
const aktifPage = pagingStorage.aktifPage;

const showingParkingElement = (type) => {
	$(".card-slot").find(".slot-status").show("slow").fadeIn(1000);
	$(".card-slot").find(".slot-parkir").show("slow").fadeIn(1000);
	$("#display-booth")
		.find(".marka")
		.removeClass("hidden")
		.show("slow")
		.slideDown(1000);

	const slotParkirElements = document.querySelectorAll(".slot-parkir");

	switch (type) {
		case "RODA DUA":
			$(".marka").addClass("motor");
			break;

		case "RODA EMPAT":
			$(".marka").addClass("car");
			break;

		default:
			$(".marka").css("cursor", "auto");
			break;
	}

	slotParkirElements.forEach((element) => {
		if (type === "RODA EMPAT") {
			element.classList.add("car");
			element.style.width = "70px";
			element.style.height = "70px";
		} else if (type === "RODA DUA") {
			element.classList.add("motor");
			element.style.width = "70px";
			element.style.height = "70px";
		} else {
			element.style.cursor = "auto";
		}
	});
};

const changeCursorType = (type) => {
	const slotParkirElements = document.querySelectorAll(".slot-parkir");

	switch (type) {
		case "RODA DUA":
			$(".marka").addClass("motor");
			$(".bayar").addClass("motor");
			break;

		case "RODA EMPAT":
			$(".marka").addClass("car");
			$(".bayar").addClass("car");
			break;

		default:
			$(".marka").css("cursor", "auto");
			$(".bayar").css("cursor", "auto");
			break;
	}

	slotParkirElements.forEach((element) => {
		if (type === "RODA EMPAT") {
			element.classList.add("car");
			element.style.width = "70px";
			element.style.height = "70px";
		} else if (type === "RODA DUA") {
			element.classList.add("motor");
			element.style.width = "70px";
			element.style.height = "70px";
		} else {
			element.classList.remove("car");
			element.classList.remove("motor");
			element.style.cursor = "auto";
		}
	});
};

const domMarka = (total) => {
	const slotMarka = $(".slot-marka");
	// Membuat array berisi elemen-elemen DOM baru
	const markaElements = [...Array(Math.ceil(total / 4))].map(
		(e, idx) => `<div class="h-24 bg-yellow-500 w-6 mb-10"></div>`,
	);

	// Menggabungkan elemen-elemen DOM menjadi satu teks HTML
	const combinedHtml = markaElements.join("");

	// Menyisipkan teks HTML ke dalam elemen .slot-marka
	slotMarka.html(combinedHtml);
};

const checkParkingSlot = (type, from, to) => {
	const endPoint = `http://parkir-simulasi.test/parkir/check-available?from=${from}&to=${to}`;
	loadingOverlay.classList.remove("hidden");
	loadingOverlay.classList.add("block");
	$.ajax({
		url: endPoint,
		type: "GET",
		dataType: "json",
		data: {},
	})
		.done(function (response) {
			let slots = [];

			const container = $(`.${type}`);

			container.empty();

			// domMarka(_.size(response.data))

			switch (type) {
				case "booth1":
					slots = response.data;

					domMarka(_.size(slots) + Math.ceil(_.size(slots) / 2));

					_.map(slots, (slot) => {
						let html = "";
						if (slot.status === "AVAILABLE") {
							html = `
						<div class="card-slot w-full p-4">
						<div class="bg-gray-400 rounded-lg p-4">
						<h3 class="text-xl font-bold">${slot.name}</h3>
						<span class="slot-status bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">${slot.status}</span>
						<input type="hidden" class="slot-id" value="${from}" />
						<input type="hidden" class="both" value="${type}" />

						<button type="button" class="slot-parkir w-full mt-4 focus:outline-none text-gray-700 font-sans font-bold bg-yellow-400 hover:bg-yellow-500 rounded-lg text-sm px-5 py-2.5 mb-2">

						<div class="loading-button hidden">
						<svg aria-hidden="true" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
						<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
						</svg>
						Loading...
						</div>
						<span class="text-button"><i class="fa-solid fa-square-parking"></i> Parkir</span>
						</button>
						</div>
						</div>
						`;
							container.append(html);
						} else {
							const vehicleIcon =
								slot.type === "RODA DUA"
									? "icon-motor"
									: "icon-car";

							const currentDateTime = new Date();

							const startedAt = new Date(slot.startedAt);

							const timeDifference = currentDateTime - startedAt;

							const timeDifferenceInHours = Math.ceil(
								timeDifference / (1000 * 60 * 60),
							);

							const harga = parseInt(
								slot.harga.replace(/\./g, ""),
								10,
							);

							const total = timeDifferenceInHours * harga;

							html = `
						<div class="card-slot w-full p-4">
						<div class="bg-gray-400 rounded-lg p-4">
						<h3 class="text-xl font-bold">${slot.name}</h3>

						<div class="flex justify-center">
						<div>
						<button class="copyButton text-gray-700 text-center text-2xl bg-transparent" data-kode="${slot.barcode}"><i class="fa-solid fa-clipboard"></i></button>
						</div>
						</div>
						<span class="slot-status bg-red-100 text-red-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">${slot.barcode}</span>

						<i id="${vehicleIcon}-${slot.barcode}" class="${vehicleIcon}"></i>
						
						<span id="countdown-startedAt-${slot.barcode}" class="countdown-startedAt bg-green-100 text-green-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400" data-slot="${slot.name}"></span>

						<button onClick="exitParking({barcode: '${slot.barcode}', slot_id: '${slot.slot_id}', vehicle_id: '${slot.vehicle_id}', total: '${total}', name: '${slot.name}', type: '${slot.type}', jam: '${timeDifferenceInHours} Jam', booth: '${type}'})" type="button" class="exit-slot-${slot.barcode} w-full mt-4 focus:outline-none text-white font-sans font-bold bg-red-400 hover:bg-red-500 rounded-lg text-sm px-5 py-2.5 mb-2">Exit <i class="fa-solid fa-right-from-bracket"></i>
						</button>

						<button type="button" class="slot-parkir-${slot.slot_id} exit-parkir hidden w-full mt-4 focus:outline-none text-white font-sans font-bold bg-red-700 hover:bg-red-500 rounded-lg text-sm px-5 py-2.5 mb-2"><i class="fa-solid fa-ban"></i> Exit Parkir
						</button>
						</div>
						</div>
						`;
							// Update the countdown immediately
							updateCountdown(
								slot.startedAt,
								container.find(
									`.countdown-startedAt[data-slot="${slot.name}"]`,
								)[0],
							);

							container.append(html);

							countdownInterval(
								slot.startedAt,
								container.find(
									`.countdown-startedAt[data-slot="${slot.name}"]`,
								)[0],
							);
						}
						from++;
					});
					break;

				case "booth2":
					slots = response.data;

					domMarka(_.size(slots) + Math.ceil(_.size(slots) / 2));

					_.map(slots, (slot) => {
						let html = "";

						if (slot.status === "AVAILABLE") {
							html = `
						<div class="card-slot w-full p-4">
						<div class="bg-gray-400 rounded-lg p-4">
						<h3 class="text-xl font-bold">${slot.name}</h3>
						<span class="slot-status bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">${slot.status}</span>
						<input type="hidden" class="slot-id" value="${from}" />
						<input type="hidden" class="both" value="${type}" />

						<button type="button" class="slot-parkir w-full mt-4 focus:outline-none text-gray-700 font-sans font-bold bg-yellow-400 hover:bg-yellow-500 rounded-lg text-sm px-5 py-2.5 mb-2">

						<div class="loading-button hidden">
						<svg aria-hidden="true" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
						<path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
						</svg>
						Loading...
						</div>
						<span class="text-button"><i class="fa-solid fa-square-parking"></i> Parkir</span>
						</button>
						</div>
						</div>
						`;
							container.append(html);
						} else {
							const vehicleIcon =
								slot.type === "RODA DUA"
									? "icon-motor"
									: "icon-car";
							const currentDateTime = new Date();

							const startedAt = new Date(slot.startedAt);

							const timeDifference = currentDateTime - startedAt;

							const timeDifferenceInHours = Math.ceil(
								timeDifference / (1000 * 60 * 60),
							);

							const harga = parseInt(
								slot.harga.replace(/\./g, ""),
								10,
							);

							const total = timeDifferenceInHours * harga;

							html = `
						<div class="card-slot w-full p-4">
						<div class="bg-gray-400 rounded-lg p-4">
						<h3 class="text-xl font-bold">${slot.name}</h3>

						<div class="flex justify-center">
						<div>
						<button class="copyButton text-gray-700 text-center text-2xl bg-transparent" data-kode="${slot.barcode}"><i class="fa-solid fa-clipboard"></i></button>
						</div>
						</div>
						<span class="slot-status bg-red-100 text-red-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400">${slot.barcode}</span>

						<i id="${vehicleIcon}-${slot.barcode}" class="${vehicleIcon}"></i>

						<span id="countdown-startedAt-${slot.barcode}" class="w-full countdown-startedAt bg-green-100 text-green-800 text-sm font-medium mr-0 px-0.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400" data-slot="${slot.name}"></span>

						<button onClick="exitParking({barcode: '${slot.barcode}', slot_id: '${slot.slot_id}', vehicle_id: '${slot.vehicle_id}', total: '${total}', name: '${slot.name}', type: '${slot.type}', jam: '${timeDifferenceInHours} Jam', booth: '${type}'})" type="button" class="exit-slot-${slot.barcode} w-full mt-4 focus:outline-none text-white font-sans font-bold bg-red-400 hover:bg-red-500 rounded-lg text-sm px-5 py-2.5 mb-2">Exit <i class="fa-solid fa-right-from-bracket"></i>
						</button>

						<button type="button" class="slot-parkir-${slot.slot_id} exit-parkir hidden w-full mt-4 focus:outline-none text-white font-sans font-bold bg-red-700 hover:bg-red-500 rounded-lg text-sm px-5 py-2.5 mb-2"><i class="fa-solid fa-ban"></i> Exit Parkir
						</button>
						</div>
						</div>
						`;
							// Update the countdown immediately
							updateCountdown(
								slot.startedAt,
								container.find(
									`.countdown-startedAt[data-slot="${slot.name}"]`,
								)[0],
							);

							container.append(html);

							countdownInterval(
								slot.startedAt,
								container.find(
									`.countdown-startedAt[data-slot="${slot.name}"]`,
								)[0],
							);
						}
						from++;
					});
					break;

				default:
					slots = [];
			}
		})
		.fail((err) => {
			Swal.fire({
				icon: "error",
				title: "Oops...",
				text: err.message,
			});
		})
		.always(() => {
			setTimeout(() => {
				loadingOverlay.classList.remove("block");
				loadingOverlay.classList.add("hidden");
			}, 500);
		});
};

const takeATicket = (vehicle_id) => {
	const endPoint = `http://parkir-simulasi.test/parkir/take-ticket`;

	$.ajax({
		url: endPoint,
		type: "POST",
		dataType: "json",
		data: { vehicle_id: vehicle_id },
	})
		.done(function (response) {
			if (response.success) {
				const data = response.data;
				showToast(
					`<i class="fa-solid fa-ticket"></i> ${response.message}`,
				);
				saveTicket(data);
				// $('#parkingTicketModal').removeClass('hidden')
				// $('#parkingTicketModal').addClass('block')
			}
		})
		.fail((err) => {
			console.error(err);
		})
		.always(() => {
			setTimeout(() => {
				loadingOverlay.classList.remove("block");
				loadingOverlay.classList.add("hidden");
			}, 1500);
		});
};

const startParking = (data, booth, loading, textBtn) => {
	loading.removeClass("hidden");
	textBtn.addClass("hidden");
	const ticketModalBody = $(".ticketModalBody");
	const endPoint = "http://parkir-simulasi.test/start-parking";

	$.ajax({
		url: endPoint,
		type: "POST",
		dataType: "json",
		data: {
			slot_id: data.slot_id,
			status: data.status,
			vehicle_id: data.vehicle_id,
			barcode: data.barcode,
		},
	})
		.done(function (response) {
			let html = "";
			if (response.success) {
				const data = response.data;

				localStorage.setItem(
					"lastData",
					JSON.stringify({ data: data, type: "start" }),
				);

				showToast(
					`<i class="fa-solid fa-check-to-slot"></i> ${response.message}`,
				);

				switch (booth) {
					case "booth1":
						checkParkingSlot("booth1", 1, 12);
						break;

					case "booth2":
						checkParkingSlot("booth2", 13, 24);
						break;
				}

				localStorage.removeItem("ticket");
				html += `
			<div class="flex justify-center">
			<div class="bg-gradient-to-r from-blue-500 to-purple-500  p-8 rounded-lg shadow-lg w-screen">
			<h1 class="text-2xl font-bold mb-4">Tiket Parkir</h1>
			<div class="mb-4">
			<p class="text-sm text-gray-900">Nomor Tiket:</p>
			<p class="text-lg font-semibold">${data[0].barcode}</p>
			</div>
			<div class="mb-4">
			<p class="text-sm text-gray-900">Jenis Kendaraan:</p>
			<p class="text-lg font-semibold">${data[0].type}</p>
			</div>
			<div class="mb-4">
			<p class="text-sm text-gray-900">Waktu Masuk:</p>
			<p class="text-lg font-semibold">${data[0].startedAt}</p>
			</div>
			
			<div class="mb-4">
			<p class="text-sm text-gray-900">Biaya Parkir:</p>
			<p class="text-lg font-semibold">Rp ${data[0].harga} / Jam</p>
			</div>
			<button class="closeTicketModal block w-full bg-blue-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Ambil Tiket</button>
			</div>
			</div>
			`;
				ticketModalBody.append(html);
				// getAllData('tickets', aktifPage)
			}
		})
		.fail((err) => {
			console.error(err);
		})
		.always(() => {
			setTimeout(() => {
				loading.addClass("hidden");
				textBtn.removeClass("hidden");
			}, 1500);
		});
};

function showToast(message) {
	const toastContainer = document.getElementById("toastContainer");

	// Create toast element
	const toast = document.createElement("div");
	toast.classList.add("toast");
	toast.innerHTML = message;

	// Add toast to container
	toastContainer.appendChild(toast);

	// Show toast
	toast.classList.add("show");

	// Auto hide after 3 seconds
	setTimeout(() => {
		toast.classList.add("hide");
		setTimeout(() => {
			// Remove toast from container
			toastContainer.removeChild(toast);
		}, 300);
	}, 3000);
}

function saveTicket(data) {
	localStorage.setItem("ticket", JSON.stringify({ data: data }));
}

function savePayment(data) {
	localStorage.setItem("payment", JSON.stringify({ data: data }));
}

function renderBarcode(barcode) {
	JsBarcode("#barcode", barcode);
}

function updateCountdown(startedAt, countdownElement) {
	const countdownDate = new Date(startedAt).getTime(); // Konversi startedAt menjadi timestamp
	const now = new Date().getTime(); // Waktu saat ini dalam timestamp
	const distance = now - countdownDate;

	// Perhitungan waktu berdasarkan selisih
	const days = Math.floor(distance / (1000 * 60 * 60 * 24));
	const hours = Math.floor(
		(distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60),
	);
	const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	const seconds = Math.floor((distance % (1000 * 60)) / 1000);

	// Membuat string waktu yang sesuai dengan format
	const countdownString = `${days}d ${hours}h ${minutes}m ${seconds}s`;

	// Menampilkan string waktu di elemen countdownElement
	if (countdownElement) {
		countdownElement.textContent = countdownString;
	}

	if (distance <= 0) {
		clearInterval(countdownInterval);
	}
}

// Update the countdown every second
const countdownInterval = (startedAt, element) => {
	setInterval(() => updateCountdown(startedAt, element), 1000);
};

const formatIdr = (angka) => {
	const formatRupiah = new Intl.NumberFormat("id-ID", {
		style: "currency",
		currency: "IDR",
	}).format(angka);

	return formatRupiah;
};

const swalWithCustomButton = Swal.mixin({
	customClass: {
		confirmButton:
			"bg-green-100 text-green-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400",
		cancelButton:
			"bg-red-100 text-red-800 text-lg font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-red-400 border border-red-400",
	},
	buttonsStyling: false,
});

const exitParking = (data) => {
	const checkPaymentReady = localStorage.getItem("payment")
		? JSON.parse(localStorage.getItem("payment"))
		: null;

	if (_.size(checkPaymentReady) > 0) {
		if (checkPaymentReady.data.barcode === data.barcode) {
			swalWithCustomButton
				.fire({
					title: `Anda ingin keluar dari ${data.name} ?`,
					text: `Total Biaya ${data.type} - Durasi / ${
						data.jam
					} : ${formatIdr(data.total)}`,
					icon: "warning",
					showCancelButton: true,
					confirmButtonText: "Yes, exit now!",
					cancelButtonText: "No, cancel!",
					reverseButtons: true,
				})
				.then((result) => {
					if (result.isConfirmed) {
						const iconClass =
							data.type === "RODA DUA"
								? "icon-motor"
								: "icon-car";
						// $('.slot-status').hide().fadeOut(1000)
						$(`#countdown-startedAt-${data.barcode}`)
							.hide()
							.fadeOut(1000);
						$(`#${iconClass}-${data.barcode}`).hide().fadeOut(1000);
						$(`.exit-slot-${data.barcode}`).hide().fadeOut(1000);
						$(`.slot-parkir-${data.slot_id}`)
							.show("slow")
							.fadeIn(1000);
						$("#ambil-tiket-parkir").hide().fadeOut(1000);
						$("#bayar").show("slow").fadeIn(1000);

						changeCursorType(data.type);

						const paymentData = {
							barcode: data.barcode,
							slot_id: data.slot_id,
							vehicle_id: data.vehicle_id,
							booth: data.booth,
							type: data.type,
						};

						savePayment(paymentData);

						swalWithCustomButton.fire(
							`Exit ${data.name}`,
							`Bayar di pintu kasir sejumlah : ${formatIdr(
								data.total,
							)}`,
							"success",
						);
					} else if (result.dismiss === Swal.DismissReason.cancel) {
						swalWithCustomButton.fire(
							"Cancelled",
							"Your imaginary file is safe :)",
							"error",
						);
					}
				});
		} else {
			swalWithCustomButton.fire(
				"Error",
				`Pembayaran di slot : ${checkPaymentReady.data.slot_id} dengan code pembayaran : ${checkPaymentReady.data.barcode} sebelumnya belum terselesaikan`,
				"error",
			);
		}
	} else {
		swalWithCustomButton
			.fire({
				title: `Anda ingin keluar dari ${data.name} ?`,
				text: `Total Biaya ${data.type} - Durasi / ${
					data.jam
				} : ${formatIdr(data.total)}`,
				icon: "warning",
				showCancelButton: true,
				confirmButtonText: "Yes, exit now!",
				cancelButtonText: "No, cancel!",
				reverseButtons: true,
			})
			.then((result) => {
				if (result.isConfirmed) {
					const iconClass =
						data.type === "RODA DUA" ? "icon-motor" : "icon-car";
					// $('.slot-status').hide().fadeOut(1000)
					$(`#countdown-startedAt-${data.barcode}`)
						.hide()
						.fadeOut(1000);
					$(`#${iconClass}-${data.barcode}`).hide().fadeOut(1000);
					$(`.exit-slot-${data.barcode}`).hide().fadeOut(1000);
					$(`.slot-parkir-${data.slot_id}`).show("slow").fadeIn(1000);
					$("#ambil-tiket-parkir").hide().fadeOut(1000);
					$("#bayar").show("slow").fadeIn(1000);

					changeCursorType(data.type);

					const paymentData = {
						barcode: data.barcode,
						slot_id: data.slot_id,
						vehicle_id: data.vehicle_id,
						booth: data.booth,
						type: data.type,
					};

					savePayment(paymentData);

					swalWithCustomButton.fire(
						`Exit ${data.name}`,
						`Bayar di pintu kasir sejumlah : ${formatIdr(
							data.total,
						)}`,
						"success",
					);
				} else if (result.dismiss === Swal.DismissReason.cancel) {
					swalWithCustomButton.fire(
						"Cancelled",
						"Your imaginary file is safe :)",
						"error",
					);
				}
			});
	}
};

const payParking = (data, booth) => {
	loadingOverlay.classList.remove("hidden");
	loadingOverlay.classList.add("block");
	const ticketModalBody = $(".ticketModalBody");
	const endPoint = "http://parkir-simulasi.test/end-parking";

	$.ajax({
		url: endPoint,
		type: "POST",
		dataType: "json",
		data: {
			barcode: data.barcode,
			slot_id: data.slot_id,
			vehicle_id: data.vehicle_id,
		},
	})
		.done(function (response) {
			if (response.success) {
				parkingTicketModal.show();
				let html = "";
				const data = response.data;

				localStorage.setItem(
					"lastData",
					JSON.stringify({ data: data }),
				);

				changeCursorType("");

				showToast(response.message);

				$("#ambil-tiket-parkir").show("slow").fadeIn(1000);
				$("#bayar").hide().fadeOut(1000);

				switch (booth) {
					case "booth1":
						checkParkingSlot("booth1", 1, 12);
						break;

					case "booth2":
						checkParkingSlot("booth2", 13, 24);
						break;
				}
				showingParkingElement("booth1");
				localStorage.removeItem("payment");

				html += `
			<div class="flex justify-center">
			<div class="bg-gradient-to-r from-blue-500 to-purple-500  p-8 rounded-lg shadow-lg w-screen">
			<h1 class="text-2xl font-bold mb-4">Tiket Parkir</h1>
			<div class="mb-4">
			<p class="text-sm text-gray-900">Nomor Tiket:</p>
			<p class="text-lg font-semibold">${data[0].barcode}</p>
			</div>
			<div class="mb-4">
			<p class="text-sm text-gray-900">Jenis Kendaraan:</p>
			<p class="text-lg font-semibold">${data[0].type}</p>
			</div>
			<div class="mb-4">
			<p class="text-sm text-gray-900">Slot Parkir:</p>
			<p class="text-lg font-semibold">${data[0].slot_id}</p>
			</div>
			<div class="mb-4">
			<p class="text-sm text-gray-900">Durasi:</p>
			<p class="text-lg font-semibold">${data[0].duration}</p>
			</div>
			<div class="mb-4">
			<p class="text-sm text-gray-900">Total Biaya Parkir:</p>
			<p class="text-lg font-semibold">Rp ${data[0].paymentAmount}</p>
			</div>
			<button class="cetakTicket block w-full bg-blue-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cetak Struk</button>
			</div>
			</div>
			`;
				ticketModalBody.append(html);

				const savingLastPayment = {
					barcode: data[0].barcode,
					type: data[0].type,
					slot: data[0].slot_id,
					duration: data[0].duration,
					total: data[0].paymentAmount,
				};

				localStorage.setItem(
					"lastPayment",
					JSON.stringify(savingLastPayment),
				);
			}
		})
		.fail((err) => {
			Swal.fire({
				icon: "error",
				title: "Oops...",
				text: err.message,
			});
		})
		.always(() => {
			setTimeout(() => {
				loadingOverlay.classList.remove("block");
				loadingOverlay.classList.add("hidden");
			}, 1500);
		});
};

function generatePDF() {
	container = $("#strukContainer");

	getCanvas().then(function (canvas) {
		let img = canvas.toDataURL("image/png"),
			doc = new jsPDF({
				unit: "px",
				format: [mmToPx(30), mmToPx(45)],
			});
		doc.addImage(img, "JPEG", 20, 20);

		const pdfWidth = mmToPx(150);
		const pdfHeight = mmToPx(150);

		const pdfIframe = `<iframe width="${pdfWidth}px" height="${pdfHeight}px" src="${doc.output(
			"datauristring",
		)}"></iframe>`;
		let pdfWindow = window.open("");
		pdfWindow.document.write(pdfIframe);
		pdfWindow.document.title = "struk-parkir.pdf";

		const pageCount = doc.internal.getNumberOfPages();

		for (let i = 0; i < pageCount; i++) {
			doc.setPage(i);
			doc.setFontSize(8);
			doc.setTextColor(128);
			doc.text(
				20,
				doc.internal.pageSize.getHeight() - 10,
				`Page ${i + 1} of ${pageCount}`,
			);
		}

		container.width(cache_width);
	});
}

function mmToPx(mm) {
	return mm * 3.7795275591; // 1 mm = 3.7795275591 px (piksel)
}

function getCanvas() {
	const customWidth = mmToPx(30); // Lebar struk 80mm dalam piksel
	const customHeight = mmToPx(45); // Tinggi struk 150mm dalam piksel

	container.width(customWidth).height(customHeight).css("max-width", "none");

	return html2canvas(container, {
		imageTimeout: 500,
		removeContainer: true,
	});
}
