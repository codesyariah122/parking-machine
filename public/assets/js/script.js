/**
 * @author: Puji Ermanto <pujiermanto@gmail.com>
 * Desc: File ini menampung seluruh nilai variable agar serbaguna ketika di gunakan di bagian script lainnya.
 * */

// Run consume data
let url = new URL(window.location.href),
  path = url.pathname,
  pagePath = path.split("/")[2];

// Initialisasi variable
let loading = document.querySelector("#loading");
let loadingOverlay = document.querySelector("#loading-overlay");
const diplayBooth = $("#display-booth");

let loadingLoginBtn = $("#loading-login"),
  loginTextBtn = $(".login-textBtn");

if (loading) {
  setTimeout(() => {
    loading.classList.remove("block");
    loading.classList.add("hidden");
  }, 1500);
}

let domDataLists = $(`#${pagePath}`),
  dailyDataAnalyze = $(`#${pagePath}-daily`),
  totalyDataAnalyze = $(`#${pagePath}-total`),
  mostVehiclesCard = $("#mostly-vehicles"),
  pagination = $("#pagination"),
  paging = {};
let container = $("#strukContainer");
let cache_width = container.width();
let a4 = [600.28, 841.89];

const options = {
  placement: "center",
  backdrop: "dynamic",
  backdropClasses:
    "bg-gray-900 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40",
  closable: true,
  onHide: () => {
    console.log("hide modal");
    $("#vehicles").val("Pilih Jenis");
  },
  onShow: () => {
    console.log("modal is shown");
  },
  onToggle: () => {
    console.log("modal has been toggled");
  },
};
const $targetEl = document.getElementById("defaultModal");
const $ticketModal = document.getElementById("parkingTicketModal");
const modal = new Modal($targetEl, options);
const parkingTicketModal = new Modal($ticketModal, options);
