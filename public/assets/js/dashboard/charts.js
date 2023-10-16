let myChart;
let myPieChartDaily;
let myPieChartMonthly;
let maxDataPoints = 10; // Batas maksimum data points yang ditampilkan di chart
let dataQueue = [];

function updateChartData(data) {
	const newData = {
		labels: data.dates,
		datasets: [
			{
				label: "Penghasilan Parkir",
				data: data.amounts,
				backgroundColor: "rgba(75, 192, 192, 0.2)",
				borderColor: "rgba(75, 192, 192, 1)",
				borderWidth: 1,
				pointRadius: 5,
				pointHoverRadius: 7,
			},
		],
	};

	myChart.data = newData;
	myChart.update();
}

const formatRupiah = (value) => {
	return parseFloat(value).toLocaleString("id-ID", {
		style: "currency",
		currency: "IDR",
		minimumFractionDigits: 2,
	});
};

const analyzeDataReport = () => {
	$.ajax({
		url: "http://parkir-simulasi.test/dashboard/lists/payments",
		type: "GET",
		dataType: "json",
		data: {},
	}).done(function (response) {
		if (response.success) {
			const analisisData = response.analysis;
			const mostlyVehicles = response.mostlyVehicles;
			const setUpReport = {
				today: analisisData.totalToday,
				oneDaysBefore: analisisData.totalOneDaysBefore,
				twoDaysBefore: analisisData.totalTwoDaysBefore,
			};
			const mostVehicles = {
				daily: mostlyVehicles.daily,
				monthly: mostlyVehicles.monthly,
			};

			const lineChartData = {
				amounts: response.analysis.amount,
				dates: response.analysis.date,
			};
			dataQueue.push(lineChartData);
			// setupLiveChart(lineChartData)
			setUpPieChart(mostlyVehicles);
			// updateChartData(lineChartData)
		}
	});
};

// Fungsi untuk mengatur chart bergerak
function setupLiveChart() {
	const dataLength = 10; // Batas panjang data yang ditampilkan

	// Hapus canvas yang ada sebelumnya
	const existingCanvas = document.getElementById("myChart");
	if (existingCanvas) {
		existingCanvas.parentNode.removeChild(existingCanvas);
	}

	// Buat elemen canvas baru
	const canvas = document.createElement("canvas");
	canvas.id = "myChart";
	const chartContainer = document.getElementById("chart-container");
	chartContainer.appendChild(canvas);

	// Inisialisasi chart dengan data kosong
	const ctx = canvas.getContext("2d");
	const liveChart = new Chart(ctx, {
		type: "line",
		data: {
			labels: [],
			datasets: [
				{
					label: "Biaya Parkir",
					data: [],
					backgroundColor: "rgba(75, 192, 192, 0.2)",
					borderColor: "rgba(75, 192, 192, 1)",
					borderWidth: 1,
					pointRadius: 5,
					pointHoverRadius: 7,
				},
			],
		},
		options: {
			animation: {
				duration: 0, // Nonaktifkan animasi agar perubahan data langsung terlihat
			},
			scales: {
				y: {
					ticks: {
						callback: function (value, index, values) {
							return formatRupiah(value);
						},
					},
				},
			},
			plugins: {
				legend: {
					display: false, // Sembunyikan legenda
				},
			},
		},
	});

	function addData(time, value) {
		// Tambahkan data baru ke dataQueue
		dataQueue.push({ x: time, y: value });

		// Jika dataQueue melebihi batas panjang yang diinginkan, hapus data pertama
		if (dataQueue.length > dataLength) {
			dataQueue.shift();
		}

		// Update chart dengan data terbaru dari dataQueue
		const labels = dataQueue.map((data) => data.x);
		const data = dataQueue.map((data) => data.y);

		liveChart.data.labels = labels;
		liveChart.data.datasets[0].data = data;
		liveChart.update(); // Perbarui chart
	}

	setInterval(fetchDataAndUpdateChart, 1000);

	function fetchDataAndUpdateChart() {
		$.ajax({
			url: "http://parkir-simulasi.test/dashboard/lists/payments",
			type: "GET",
			dataType: "json",
			data: {},
		}).done(function (response) {
			if (response.success) {
				const amounts = response.analysis.amount;
				const dates = response.analysis.date;

				if (amounts.length === dates.length) {
					// Bersihkan dataQueue saat mengambil data baru dari server
					dataQueue = [];

					for (let i = 0; i < amounts.length; i++) {
						addData(dates[i], parseFloat(amounts[i]));
					}
				}
			}
		});
	}

	// Contoh untuk mengupdate chart dengan data acak setiap 1 detik
	// setInterval(function() {
	// 	const time = new Date().toLocaleTimeString();
	// 	const value = Math.floor(Math.random() * 100);
	// 	addData(time, value);
	// }, 1000);
}

const setUpPieChart = (param) => {
	let dataPerDay = {
		labels: [
			`RODA DUA ${param.daily.rodaDua}%`,
			`RODA EMPAT ${param.daily.rodaEmpat}%`,
		],
		datasets: [
			{
				data: [param.daily.rodaDua, param.daily.rodaEmpat],
				backgroundColor: ["#FF6384", "#36A2EB"],
				borderWidth: 1,
			},
		],
	};

	// Data untuk diagram pie chart per bulan
	let dataPerMonth = {
		labels: [
			`RODA DUA ${param.monthly.rodaDua}%`,
			`RODA EMPAT ${param.monthly.rodaEmpat}%`,
		],
		datasets: [
			{
				data: [param.monthly.rodaDua, param.monthly.rodaEmpat],
				backgroundColor: ["#4287f5", "#c04bfa"],
				borderWidth: 1,
			},
		],
	};

	// Opsi konfigurasi diagram pie chart
	let options = {
		responsive: true,
		plugins: {
			tooltips: {
				enabled: true,
				callbacks: {
					label: (tooltipItem, data) => {
						const dataset = data.datasets[tooltipItem.datasetIndex];
						const total = dataset.data.reduce(
							(previousValue, currentValue) => previousValue + currentValue,
						);
						const currentValue = dataset.data[tooltipItem.index];
						const percentage = ((currentValue / total) * 100).toFixed(2);
						return `${data.labels[tooltipItem.index]}: ${percentage}%`;
					},
				},
			},
			datalabels: {
				formatter: (value, ctx) => {
					const dataset = ctx.chart.data.datasets[0];
					const total = dataset.data.reduce(
						(previousValue, currentValue) => previousValue + currentValue,
					);
					const currentValue = dataset.data[ctx.dataIndex];
					const percentage = ((currentValue / total) * 100).toFixed(2);
					return `${ctx.chart.data.labels[ctx.dataIndex]}: ${percentage}%`;
				},
				color: "#fff", // Warna teks label
				font: {
					weight: "bold",
				},
			},
		},
	};

	if (param.daily.rodaDua === 0 && param.daily.rodaEmpat === 0) {
		dataPerDay.labels = ["RODA DUA 0%", "RODA EMPAT 0%"];
		dataPerDay.datasets[0].data = [1, 1];
	}

	if (myPieChartDaily && myPieChartMonthly) {
		myPieChartDaily.destroy();
		myPieChartMonthly.destroy();
	}

	let ctxPerDay = document.getElementById("myPieChartDaily").getContext("2d");
	myPieChartDaily = new Chart(ctxPerDay, {
		type: "pie",
		data: dataPerDay,
		options: options,
	});

	let ctxPerMonth = document
		.getElementById("myPieChartMonthly")
		.getContext("2d");
	myPieChartMonthly = new Chart(ctxPerMonth, {
		type: "pie",
		data: dataPerMonth,
		options: options,
	});
};

function watchDataChanges() {
	let lastData = localStorage.getItem("lastData")
		? JSON.parse(localStorage.getItem("lastData"))
		: null;

	$.ajax({
		url: `/lists/payments`,
		type: "GET",
		dataType: "json",
		data: {},
		success: function (response) {
			if (JSON.stringify(response) !== JSON.stringify(lastData)) {
				const typeMessage =
					lastData.type === "start"
						? `baru saja parkir di ${lastData.data[0].slot_name} <i class="fa-solid fa-hourglass-start"></i>`
						: 'successfully finish payment <i class="fa-solid fa-check"></i>';

				lastData = response;

				localStorage.setItem("lastData", JSON.stringify(lastData));

				const toastMessage = `Payment code : ${lastData.data[0].barcode}, ${typeMessage}`;
				analyzeDataReport();
				showToast(toastMessage);
			}
		},
		error: function (xhr, status, error) {
			console.error("Error while watching data:", error);
		},
		complete: function (response) {
			setTimeout(watchDataChanges, 5000);
		},
	});
}

if (pagePath === "admin") {
	analyzeDataReport();
	watchDataChanges();
	setupLiveChart();
}
