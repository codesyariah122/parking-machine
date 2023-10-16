<style type="text/css">
  .loader {
    border-top-color: #3498db;
    -webkit-animation: spinner 1.5s linear infinite;
    animation: spinner 1.5s linear infinite;
  }

  @-webkit-keyframes spinner {
    0% {
      -webkit-transform: rotate(0deg);
    }
    100% {
      -webkit-transform: rotate(360deg);
    }
  }

  @keyframes spinner {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
</style>
<div id="loading-overlay" wire:loading class="hidden fixed top-0 block left-0 right-0 bottom-0 w-full h-screen z-50 overflow-hidden bg-gray-700 opacity-95 flex flex-col items-center justify-center">
  <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-16 w-16 mb-4"></div>
  <h2 class="text-center text-white text-xl font-semibold"><?=$title?></h2>
  <p class="w-1/3 text-center text-white">Loading...</p>

</div>