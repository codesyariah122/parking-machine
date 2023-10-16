const saveLogin = (data) => {
	localStorage.setItem("token", JSON.stringify({ token: data }));
};

const getTokenLogin = (key) => {
	return localStorage.getItem(key)
		? JSON.parse(localStorage.getItem(key))
		: null;
};

const removeToken = (key) => {
	return localStorage.removeItem(key);
};

const checkSessionLogin = () => {
	const storage = getTokenLogin("token");

	$.ajax({
		url: "/check/session-login",
		type: "GET",
		dataType: "json",
		data: { token: storage.token },
	}).done(function (response) {
		if (response.error) {
			Swal.fire({
				icon: "error",
				title: "Oops...",
				text: response.message,
			});

			setTimeout(() => {
				removeToken("token");
				location.replace(`/?error=expired_session`);
			}, 1000);
		}
	});
};

const Login = (data) => {
	loadingLoginBtn.removeClass("hidden");
	loginTextBtn.addClass("hidden");

	setTimeout(() => {
		loadingOverlay.classList.add("block");
		loadingOverlay.classList.remove("hidden");
	}, 1000);

	$.ajax({
		url: "/auth/login",
		type: "POST",
		dataType: "json",
		data: { email: data.email, password: data.password },
	})
		.done(function (response) {
			if (response.error) {
				Swal.fire({
					icon: "error",
					title: "Oops...",
					text: response.message,
				});
			}
			if (response.success) {
				saveLogin(response.token);
				setTimeout(() => {
					location.replace(`/dashboard/${response.data.role}`);
				}, 1500);
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
				loadingLoginBtn.addClass("hidden");
				loginTextBtn.removeClass("hidden");
			}, 1500);
		});
};

const Logout = (token) => {
	loadingOverlay.classList.add("block");
	loadingOverlay.classList.remove("hidden");
	$.ajax({
		url: "/auth/logout",
		type: "POST",
		dataType: "json",
		data: { token: token },
	})
		.done(function (response) {
			if (response.success) {
				removeToken("token");
				Swal.fire({
					position: "top-end",
					icon: "success",
					title: `Anda akan keluar dari Dashboard ${response.data.role}`,
					showConfirmButton: false,
					timer: 1500,
				});
				setTimeout(() => {
					location.replace(`/login?logout=success`);
				}, 1500);
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

const formatIdr = (angka) => {
	const formatRupiah = new Intl.NumberFormat("id-ID", {
		style: "currency",
		currency: "IDR",
	}).format(angka);

	return formatRupiah;
};

function formatDateToIndonesian(dateString) {
	// Parsing tanggal dengan format 'YYYY-MM-DD HH:mm:ss'
	const date = new Date(dateString);

	// Mendapatkan informasi tanggal, bulan, dan tahun
	const day = date.getDate();
	const month = date.getMonth() + 1; // Bulan dalam JavaScript dimulai dari 0, maka ditambahkan 1
	const year = date.getFullYear();

	// Mendapatkan informasi jam, menit, dan detik
	const hours = date.getHours();
	const minutes = date.getMinutes();
	const seconds = date.getSeconds();

	// Membuat format tanggal sesuai format Indonesia (DD/MM/YYYY HH:mm:ss)
	const formattedDate = `${day.toString().padStart(2, "0")}/${month
		.toString()
		.padStart(2, "0")}/${year} ${hours
		.toString()
		.padStart(2, "0")}:${minutes.toString().padStart(2, "0")}:${seconds
		.toString()
		.padStart(2, "0")}`;

	return formattedDate;
}

const setUpPagination = (data) => {
	pagination.empty();
	paging.totalData = data.totalData;
	paging.countPage = data.countPage;
	paging.totalPage = data.totalPage;
	paging.aktifPage = data.aktifPage;

	localStorage.setItem("paging", JSON.stringify(paging));

	const prevEl = document.createElement("li");
	const nextEl = document.createElement("li");
	prevEl.innerHTML = `
	<li><a href="#" class="page-link flex items-center justify-center px-3 h-8 ml-0 leading-tight border  rounded-l-lg  dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white ${
		paging.aktifPage > 1
			? "bg-gray-900 border-blue-300 text-white cursor-pointer"
			: "bg-gray-700 border-gray-300 text-white cursor-not-allowed"
	}" data-num="${
		paging.aktifPage > 1 ? paging.aktifPage - 1 : paging.aktifPage - 1
	}"><i class="fa-solid fa-angle-left"></i>&nbsp;Previous</a></li>
	`;
	nextEl.innerHTML = `
	<li><a href="#" class="page-link flex items-center justify-center px-3 h-8 ml-0 leading-tight border  rounded-r-lg  dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white ${
		paging.aktifPage < paging.totalPage
			? "bg-gray-900 border-blue-300 text-white cursor-pointer"
			: "bg-gray-700 border-gray-300 text-white cursor-not-allowed"
	}" data-num="${
		paging.aktifPage < paging.totalPage ? paging.aktifPage + 1 : null
	}">Next&nbsp;<i class="fa-solid fa-angle-right"></i></a></li>
	`;

	pagination.append(prevEl);

	// Batasi pagination hanya sampai 10 halaman ke depan dan ke belakang dari aktifPage
	const startPage = Math.max(1, paging.aktifPage - 4);
	const endPage = Math.min(paging.totalPage, paging.aktifPage + 4);

	for (let i = startPage; i <= endPage; i++) {
		let pageLink = $(
			`<li><a href="#" class="page-link flex items-center justify-center px-3 h-8 leading-tight border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 dark:hover:text-white ${
				i == paging.aktifPage
					? "bg-blue-800 text-gray-50 cursor-not-allowed font-bold"
					: "bg-gray-900 text-white cursor-pointer"
			}" data-num="${i}">${i}</a></li>`,
		);
		if (i === paging.aktifPage) {
			pageLink
				.find("a")
				.addClass(
					"text-white border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white",
				);
		}
		pagination.append(pageLink);
	}

	pagination.append(nextEl);
};

const getAllData = (type, page = 1, keyword = "") => {
	const endPoint = `http://parkir-simulasi.test/dashboard/lists/${type}?page=${page}${
		keyword ? "&keyword=" + keyword : ""
	}`;

	let domDataHTML = "";
	let dailyAnalyzeHTML = "";
	let totalyAnalyzeHTML = "";
	let mostlyVehiclesHTML = "";

	$.ajax({
		url: endPoint,
		type: "GET",
		dataType: "json",
		data: {},
	}).done(function (response) {
		if (response.success) {
			const lists = response;

			switch (type) {
				case "tickets":
					const tickets = lists?.data;
					const mostlyVehicles = lists.mostVehicles;

					tickets.forEach((ticket) => {
						const currentTime = new Date();
						const givenTime = new Date(ticket.startedAt);
						const timeDifferenceMs = currentTime - givenTime;
						const hoursDifference = Math.floor(
							timeDifferenceMs / (1000 * 60 * 60),
						);
						const numberWithCommas = ticket.harga.replace(
							/\./g,
							"",
						);
						const angka = parseInt(numberWithCommas);
						const totalPrice = hoursDifference * angka;

						domDataHTML += `
					<tr class="bg-gray-900 dark:bg-gray-900 border-b dark:border-gray-700 hover:bg-gray-600 dark:hover:bg-gray-600 text-white">

					<th scope="row" class="px-6 py-4 font-medium whitespace-nowrap dark:text-white barcode-copy">
					<div class="flex justify-center space-x-4">
					<div>
					<span class="data-barcode-copy">${ticket.barcode}</span>
					</div>
					<div>
					<button class="copyButton text-gray-200 text-center text-2xl bg-transparent" data-kode="${ticket.barcode}"><i class="fa-solid fa-clipboard"></i></button>
					</div>
					</div>
					</th>
					<td class="px-6 py-4">
					${ticket.type}
					</td>
					<td class="px-6 py-4">
					Rp. ${ticket.harga}
					</td>
					<td class="px-6 py-4 font-bold">
					${ticket.name}
					</td>
					<td class="px-6 py-4">
					<span id="countdown-startedAt-${ticket.barcode}" class="countdown-startedAt-${ticket.barcode} bg-green-100 block text-green-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400" data-slot="${ticket.type}"></span>
					</td>
					<td class="px-6 py-4">
					Rp. ${totalPrice}
					</td>
					</tr>
					`;
					});

					mostlyVehiclesHTML += `
				<li class="py-3 sm:py-4">
				<div class="flex items-center space-x-4">
				<div class="flex-shrink-0">
				<i class="fa-solid fa-chart-column"></i>
				</div>
				<div class="flex-1 min-w-0">
				<p class="text-sm font-medium text-white truncate dark:text-white">
				Most Vehicles Today
				</p>
				<p class="text-sm text-gray-500 truncate dark:text-gray-400">
				${mostlyVehicles}
				</p>
				</div>
				</li>
				`;
					break;

				case "payments":
					const payments = lists?.data;
					const analysis = lists?.analysis;
					payments.map((payment) => {
						domDataHTML += `
					<tr class="bg-gray-900 dark:bg-gray-900 border-b dark:border-gray-700 hover:bg-gray-600 dark:hover:bg-gray-600 text-white">
					<td class="w-4 p-4">
					<div class="flex items-center">
					<input id="checkbox-table-search-1" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
					<label for="checkbox-table-search-1" class="sr-only">checkbox</label>
					</div>
					</td>
					<th scope="row" class="px-6 py-4 font-medium whitespace-nowrap dark:text-white barcode-copy">
					<div class="flex justify-center space-x-4">
					<div>
					<span class="data-barcode-copy">${payment.barcode}</span>
					</div>
					<div>
					<button class="copyButton text-gray-200 text-center text-2xl bg-transparent" data-kode="${
						payment.barcode
					}"><i class="fa-solid fa-clipboard"></i></button>
					</div>
					</div>
					</th>
					<td class="px-6 py-4">
					${payment.type}
					</td>
					<td class="px-6 py-4">
					${payment.harga}
					</td>
					<td class="px-6 py-4">
					<span class="bg-green-100 block text-green-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">
					${payment.duration}
					</span>
					</td>
					<td class="px-6 py-4">
					Rp. ${payment.paymentAmount}
					</td>
					<td class="px-6 py-4">
					${formatDateToIndonesian(payment.paymentDate)}
					</td>
					<td class="flex items-center px-6 py-4 space-x-3">
					<a href="#" class="font-medium text-lg text-blue-600 dark:text-blue-500 hover:underline"><i class="fa-solid fa-pen-to-square"></i></a>
					<a href="#" class="font-medium text-lg text-red-600 dark:text-red-500 hover:underline"><i class="fa-solid fa-trash"></i></a>
					</td>
					</tr>
					`;
					});

					dailyAnalyzeHTML += `
				<li class="py-3 sm:py-4">
				<div class="flex items-center space-x-4">
				<div class="flex-shrink-0">
				<i class="fa-solid fa-chart-column"></i>
				</div>
				<div class="flex-1 min-w-0">
				<p class="text-sm font-medium text-white truncate dark:text-white">
				Income Today
				</p>
				<p class="text-sm text-gray-500 truncate dark:text-gray-400">
				${analysis.totalToday}
				</p>
				</div>
				<div class="inline-flex items-center text-base font-semibold text-white dark:text-white">
				Rp. ${analysis.totalToday}
				</div>
				</div>
				</li>
				<li class="py-3 sm:py-4">
				<div class="flex items-center space-x-4">
				<div class="flex-shrink-0">
				<i class="fa-solid fa-chart-line"></i>
				</div>
				<div class="flex-1 min-w-0">
				<p class="text-sm font-medium text-white truncate dark:text-white">
				Most Vehicles
				</p>
				<p class="text-sm text-gray-500 truncate dark:text-gray-400">
				${analysis.totalTypeToday}
				</p>
				</div>
				<div class="inline-flex items-center text-base font-semibold text-white dark:text-white">
				${analysis.totalTypeToday}
				</div>
				</div>
				</li>
				`;
					totalyAnalyzeHTML += `
				<li class="py-3 sm:py-4">
				<div class="flex items-center space-x-4">
				<div class="flex-shrink-0">
				<i class="fa-solid fa-chart-column"></i>
				</div>
				<div class="flex-1 min-w-0">
				<p class="text-sm font-medium text-white truncate dark:text-white">
				Total Income
				</p>
				<p class="text-sm text-gray-500 truncate dark:text-gray-400">
				${analysis.finalTotal}
				</p>
				</div>
				<div class="inline-flex items-center text-base font-semibold text-white dark:text-white">
				Rp. ${analysis.finalTotal}
				</div>
				</div>
				</li>
				<li class="py-3 sm:py-4">
				<div class="flex items-center space-x-4">
				<div class="flex-shrink-0">
				<i class="fa-solid fa-chart-line"></i>
				</div>
				<div class="flex-1 min-w-0">
				<p class="text-sm font-medium text-white truncate dark:text-white">
				Total Most Vehicles
				</p>
				<p class="text-sm text-gray-500 truncate dark:text-gray-400">
				${analysis.finalTotalType}
				</p>
				</div>
				<div class="inline-flex items-center text-base font-semibold text-white dark:text-white">
				${analysis.finalTotalType}
				</div>
				</div>
				</li>
				`;
					break;

				default:
					domDataHTML += "";
					dailyAnalyzeHTML += "";
					totalyAnalyzeHTML += "";
					mostlyVehiclesHTML += "";
			}

			// Append data to element dom
			domDataLists.html(domDataHTML);
			dailyDataAnalyze.html(dailyAnalyzeHTML);
			totalyDataAnalyze.html(totalyAnalyzeHTML);
			mostVehiclesCard.html(mostlyVehiclesHTML);
			// Panggil fungsi setCountdownData untuk memulai countdown timer
			if (pagePath === "tickets")
				setCountdownData(
					lists?.data.map((ticket) => ({
						time: ticket.startedAt,
						barcode: ticket.barcode,
					})),
				);

			// pagination
			setUpPagination(lists);
		} else {
			domDataLists.html(`
				<tr class="bg-gray-900 dark:bg-gray-900 border-b dark:border-gray-700 hover:bg-gray-600 dark:hover:bg-gray-600 text-white align-middle">
				<th colspan="12" class="text-center">No Data Here</th>
				</tr>
			`);
		}
	});
};

const searchData = (param, type) => {
	let domDataHTML = "";
	endPoint = `http://parkir-simulasi.test/dashboard/lists/${type}?keyword=${param.data}`;

	prepareData = {
		keyword: param.data,
	};

	$.ajax({
		url: endPoint,
		type: "GET",
		dataType: "json",
		startTime: new Date().getTime(),
		data: {},
	}).done(function (response) {
		if (response.success) {
			const lists = response;

			switch (type) {
				case "tickets":
					const tickets = lists?.data;
					tickets.forEach((ticket) => {
						const currentTime = new Date();
						const givenTime = new Date(ticket.startedAt);
						const timeDifferenceMs = currentTime - givenTime;
						const hoursDifference = Math.floor(
							timeDifferenceMs / (1000 * 60 * 60),
						);
						const numberWithCommas = ticket.harga.replace(
							/\./g,
							"",
						);
						const angka = parseInt(numberWithCommas);
						const totalPrice = hoursDifference * angka;

						domDataHTML += `
					<tr class="bg-gray-900 dark:bg-gray-900 border-b dark:border-gray-700 hover:bg-gray-600 dark:hover:bg-gray-600 text-white">

					<th scope="row" class="px-6 py-4 font-medium whitespace-nowrap dark:text-white barcode-copy">
					<div class="flex justify-center space-x-4">
					<div>
					<span class="data-barcode-copy">${ticket.barcode}</span>
					</div>
					<div>
					<button class="copyButton text-gray-200 text-center text-2xl bg-transparent" data-kode="${ticket.barcode}"><i class="fa-solid fa-clipboard"></i></button>
					</div>
					</div>
					</th>
					<td class="px-6 py-4">
					${ticket.type}
					</td>
					<td class="px-6 py-4">
					Rp. ${ticket.harga}
					</td>
					<td class="px-6 py-4">
					${ticket.name}
					</td>
					<td class="px-6 py-4">
					<span id="countdown-startedAt-${ticket.barcode}" class="countdown-startedAt-${ticket.barcode} bg-green-100 block text-green-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400" data-slot="${ticket.type}"></span>
					</td>
					<td class="px-6 py-4">
						Rp. ${totalPrice}
					</td>
					</tr>
					`;
					});
					break;

				case "payments":
					const payments = lists?.data;
					payments.map((payment) => {
						domDataHTML += `
					<tr class="bg-gray-900 dark:bg-gray-900 border-b dark:border-gray-700 hover:bg-gray-600 dark:hover:bg-gray-600 text-white">
					<td class="w-4 p-4">
					<div class="flex items-center">
					<input id="checkbox-table-search-1" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
					<label for="checkbox-table-search-1" class="sr-only">checkbox</label>
					</div>
					</td>
					<th scope="row" class="px-6 py-4 font-medium whitespace-nowrap dark:text-white barcode-copy">
					<div class="flex justify-center space-x-4">
					<div>
					<span class="data-barcode-copy">${payment.barcode}</span>
					</div>
					<div>
					<button class="copyButton text-gray-200 text-center text-2xl bg-transparent" data-kode="${
						payment.barcode
					}"><i class="fa-solid fa-clipboard"></i></button>
					</div>
					</div>
					</th>
					<td class="px-6 py-4">
					${payment.type}
					</td>
					<td class="px-6 py-4">
					${payment.harga}
					</td>
					<td class="px-6 py-4">
					<span class="bg-green-100 block text-green-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">
					${payment.duration}
					</span>
					</td>
					<td class="px-6 py-4">
					Rp. ${payment.paymentAmount}
					</td>
					<td class="px-6 py-4">
					${formatDateToIndonesian(payment.paymentDate)}
					</td>
					<td class="flex items-center px-6 py-4 space-x-3">
					<a href="#" class="font-medium text-lg text-blue-600 dark:text-blue-500 hover:underline"><i class="fa-solid fa-pen-to-square"></i></a>
					<a href="#" class="font-medium text-lg text-red-600 dark:text-red-500 hover:underline"><i class="fa-solid fa-trash"></i></a>
					</td>
					</tr>
					`;
					});
					break;

				default:
					domDataHTML += "";
			}

			// Append data to element dom
			domDataLists.html(domDataHTML);

			// Panggil fungsi setCountdownData untuk memulai countdown timer
			if (pagePath === "tickets")
				setCountdownData(
					lists?.data.map((ticket) => ({
						time: ticket.startedAt,
						barcode: ticket.barcode,
					})),
				);

			// pagination
			setUpPagination(lists);
		} else {
			domDataLists.html(`
				<tr class="bg-gray-900 dark:bg-gray-900 border-b dark:border-gray-700 hover:bg-gray-600 dark:hover:bg-gray-600 text-white align-middle">
				<th colspan="12" class="text-center">No Data Here</th>
				</tr>
			`);
		}
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

// "long polling"  Watch method for realtime data
function watchDataChanges() {
	let lastData = localStorage.getItem("lastData")
		? JSON.parse(localStorage.getItem("lastData"))
		: null;

	if (pagePath !== "admin") {
		$.ajax({
			url: `http://parkir-simulasi.test/dashboard/lists/${pagePath}`,
			type: "GET",
			dataType: "json",
			data: {},
			success: function (response) {
				if (JSON.stringify(response) !== JSON.stringify(lastData)) {
					const typeMessage =
						lastData && lastData.type === "start"
							? `baru saja parkir di ${lastData.data[0].slot_name} <i class="fa-solid fa-hourglass-start"></i>`
							: 'successfully finish payment <i class="fa-solid fa-check"></i>';
					// Jika ada perubahan, panggil method
					getAllData(pagePath, 1, "");
					// Update data terakhir dengan data baru
					lastData = response;

					localStorage.setItem("lastData", JSON.stringify(lastData));

					const toastMessage = `Payment code : ${lastData.data[0].barcode}, ${typeMessage}`;

					showToast(toastMessage);
				}
			},
			error: function (xhr, status, error) {
				console.error("Error while watching data:", error);
			},
			complete: function (response) {
				// Menjalankan ulang pada tiap proccess nya
				setTimeout(watchDataChanges, 5000);
			},
		});
	}
}

function updateCountdownTimer(startedAt, ticketBarcode) {
	const countdownElement = $(`#countdown-startedAt-${ticketBarcode}`);
	const startedAtTime = new Date(startedAt).getTime();

	function updateTimer() {
		const now = new Date().getTime();
		const distance = now - startedAtTime;

		if (distance <= 0) {
			// If the countdown has ended, display 'Expired'
			countdownElement.textContent = "Expired";
			return;
		}

		// Calculate the days, hours, minutes, and seconds remaining
		const days = Math.floor(distance / (1000 * 60 * 60 * 24));
		const hours = Math.floor(
			(distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60),
		);
		const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		const seconds = Math.floor((distance % (1000 * 60)) / 1000);

		// Format the countdown and display it
		countdownElement[0].textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
	}

	// Update the countdown every second
	updateTimer();
	setInterval(updateTimer, 1000);
}

const setCountdownData = (params) => {
	params.map((param) => {
		updateCountdownTimer(param.time, param.barcode);
	});
};

watchDataChanges();
