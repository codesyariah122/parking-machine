// Fungsi untuk mengatur chart bergerak
function setupLiveChart(data) {
  const dataLength = 10; // Batas panjang data yang ditampilkan
  const dataQueue = data; // Array untuk menyimpan data
  const maxDataPoints = 100; // Batas maksimum data points yang ditampilkan di chart

  // Inisialisasi chart dengan data kosong
  const ctx = document.getElementById('myChart').getContext('2d');
  const liveChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [],
      datasets: [{
        label: 'Live Data',
        data: [],
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1,
        pointRadius: 0, // Set pointRadius ke 0 agar tidak ada titik pada data
      }]
    },
    options: {
      animation: {
        duration: 0 // Nonaktifkan animasi agar perubahan data langsung terlihat
      },
      scales: {
        x: {
          display: false // Sembunyikan sumbu x karena data akan digeser dari kanan ke kiri
        },
        y: {
          ticks: {
            beginAtZero: true
          }
        }
      },
      plugins: {
        legend: {
          display: false // Sembunyikan legenda
        }
      }
    }
  });

  // Fungsi untuk menambahkan data baru ke dalam chart
  function addData(time, value) {
    if (dataQueue.length >= dataLength) {
      dataQueue.shift(); // Hapus data paling awal jika batas panjang tercapai
    }

    dataQueue.push({ x: time, y: value });

    const labels = dataQueue.map(data => data.x);
    const data = dataQueue.map(data => data.y);

    liveChart.data.labels = labels;
    liveChart.data.datasets[0].data = data;
    liveChart.update(); // Perbarui chart
  }

  // Contoh untuk mengupdate chart dengan data acak setiap 1 detik
  setInterval(function() {
    const time = new Date().toLocaleTimeString();
    const value = Math.floor(Math.random() * 100); // Data acak dari 0 hingga 100
    addData(time, value);
  }, 1000);
}


setupLiveChart()



const setUpChart = (lineChartData) => {
    const ctx = document.getElementById('myChart').getContext('2d');

    const data = {
        labels: lineChartData.dates,
        datasets: [{
            label: 'Penghasilan Parkir',
            data: lineChartData.amounts,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            pointRadius: 5, // Ukuran titik pada titik data
            pointHoverRadius: 7, // Ukuran titik pada titik data saat dihover
        }],
    };

    const config = {
     type: 'line',
     data: data,
     options: {
       animation: {
         duration: 0,
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
     },
    };

    if (myChart) {
        myChart.destroy();
    }

    myChart = new Chart(ctx, config);
};