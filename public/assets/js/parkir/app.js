$(document).ready(function () {
	checkParkingSlot("booth1", 1, 12);
	checkParkingSlot("booth2", 13, 24);

	$("#display-booth").on("click", ".ambil-tiket", function () {
		modal.show();
	});

	$("#display-booth").on("click", ".hide-modal", function () {
		modal.hide();
	});

	$("#defaultModal").on("click", ".input-data", function () {
		const vehicle_id = $("#vehicles").val();
		const selectedOption = $("#vehicles").find(":selected");
		const dataValue = selectedOption.data("value");

		if (vehicle_id !== "" || vehicle_id !== null) {
			showingParkingElement(dataValue);

			modal.hide();

			loadingOverlay.classList.remove("hidden");
			loadingOverlay.classList.add("block");

			takeATicket(vehicle_id);

			$("#vehicles").val("");
		}
	});

	$("#display-booth").on("click", ".slot-parkir", function (e) {
		e.preventDefault();
		const checkPayments = localStorage.getItem("payment")
			? JSON.parse(localStorage.getItem("payment"))
			: null;

		if (_.size(checkPayments) > 0) {
			const cancelPaymentData = checkPayments.data;

			Swal.fire({
				icon: "error",
				title: "Oops...",
				text: `Pembayaran terakhir untuk slot parkir : ${cancelPaymentData.slot_id}, dengan code payment : ${cancelPaymentData.barcode}. Belum terselesaikan, segera selesaikan di kasir !`,
			});
		} else {
			const loadingBtn = $(this)
				.closest(".bg-gray-400")
				.find(".loading-button");
			const textBtn = $(this)
				.closest(".bg-gray-400")
				.find(".text-button");
			const slotId = $(this)
				.closest(".bg-gray-400")
				.find(".slot-id")
				.val();
			const typeBooth = $(this)
				.closest(".bg-gray-400")
				.find(".both")
				.val();

			const ticketStorage = localStorage.getItem("ticket")
				? JSON.parse(localStorage.getItem("ticket"))
				: null;

			console.log(ticketStorage);

			if (ticketStorage !== null) {
				const ticketData = ticketStorage.data;

				const startParkingData = {
					slot_id: slotId,
					status: "NOT AVAILABLE",
					vehicle_id: ticketData[0].vehicle_id,
					barcode: ticketData[0].barcode,
				};

				startParking(startParkingData, typeBooth, loadingBtn, textBtn);

				parkingTicketModal.show();
			} else {
				showToast(
					`<i class="fa-solid fa-circle-exclamation"></i> Ambil ticket terlebih dahulu !!!`,
				);
			}
		}
	});

	$("#display-booth").on("click", ".closeTicketModal", function (e) {
		e.preventDefault();
		parkingTicketModal.hide();
		loadingOverlay.classList.remove("hidden");
		loadingOverlay.classList.add("block");
		setTimeout(() => {
			loadingOverlay.classList.remove("block");
			loadingOverlay.classList.add("hidden");
			location.reload();
		}, 2500);
	});

	$("#display-booth").on("click", ".cetakTicket", function (e) {
		e.preventDefault();
		const dataSavingPayment = localStorage.getItem("lastPayment")
			? JSON.parse(localStorage.getItem("lastPayment"))
			: null;

		const printTicketBody = $(".printTicketBody");

		let html = "";
		html += `
		<div class="struk">
		<div class="header">
		<h5 class="title">Struk&nbsp;Parkir</h5>
		</div>
		<div class="content">
		<div class="item">
		<span class="label">Kode Pembayaran:</span>
		<span class="value">${dataSavingPayment.barcode}</span>
		</div>
		<div class="item">
		<span class="label">Durasi:</span>
		<span class="value">${dataSavingPayment.duration}</span>
		</div>
		<div class="item">
		<span class="label">Kendaraan:</span>
		<span class="value">${dataSavingPayment.type}</span>
		</div>
		<div class="item">
		<span class="label">Slot:</span>
		<span class="value whitespace-pre-wrap">${dataSavingPayment.slot}</span>
		</div>
		<div class="item">
		<span class="label">Total:</span>
		<span class="value">Rp ${dataSavingPayment.total}</span>
		</div>
		</div>
		</div>
		`;
		$(".ticketModalBody").hide("slow").fadeOut(1000);

		printTicketBody.append(html);
		// Tunggu 500 milidetik untuk memastikan elemen sudah selesai di-render
		setTimeout(function () {
			generatePDF();
		}, 500);
		setTimeout(() => {
			parkingTicketModal.hide();
		}, 2500);
		loadingOverlay.classList.remove("hidden");
		loadingOverlay.classList.add("block");
		setTimeout(() => {
			loadingOverlay.classList.remove("block");
			loadingOverlay.classList.add("hidden");
			location.reload();
		}, 1500);
	});

	$("#display-booth").on("click", ".copyButton", function (e) {
		let kode = $(this).data("kode");
		let textToCopy = $(this)
			.closest(".card-slot")
			.find(".slot-status")
			.get(0).textContent;

		let tempTextArea = document.createElement("textarea");
		tempTextArea.value = textToCopy;
		document.body.appendChild(tempTextArea);
		tempTextArea.select();

		try {
			document.execCommand("copy");
			showToast(
				`<i class="fa-solid fa-check"></i> Kode bayar ${kode} berhasil di copy`,
			);
		} catch (err) {
			console.error("Gagal menyalin teks ke clipboard:", err);
		}

		document.body.removeChild(tempTextArea);
	});

	$("#display-booth").on("click", ".bayar", function (e) {
		e.preventDefault();
		const storageData = localStorage.getItem("payment")
			? JSON.parse(localStorage.getItem("payment"))
			: null;
		const paymentData = storageData.data;
		Swal.fire({
			title: "Submit payment code ?",
			input: "text",
			inputAttributes: {
				autocapitalize: "off",
			},
			showCancelButton: true,
			confirmButtonText: "Look up",
			showLoaderOnConfirm: true,
			preConfirm: (data) => {
				console.log(data);
			},
			allowOutsideClick: () => !Swal.isLoading(),
		}).then((result) => {
			if (result.isConfirmed) {
				if (result.value === paymentData.barcode) {
					payParking(paymentData, paymentData.booth);
				} else {
					Swal.fire(
						"Ooops",
						"Code bayar salah, silahkan teliti kembali!!",
						"error",
					);
				}
			}
		});
	});

	$("#display-booth").on("click", ".exit-parkir", function (e) {
		e.preventDefault();
		showToast(
			`<i class="fa-solid fa-circle-exclamation"></i> Segera lakukan pembayaran dikasir !!!`,
		);
	});
});
