

<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-white rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 h-screen">
      <span class="sr-only">Open sidebar</span>
      <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
         <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
      </svg>
   </button>

   <aside id="default-sidebar" class="fixed top-0 left-0 z-0 bg-[#2A2A32] w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 py-24 " aria-label="Sidebar">
      <div class="h-full px-3 py-4 overflow-y-auto bg-[#2A2A32] dark:bg-[#2A2A32] dark:text-white text-white">
         <ul class="space-y-2 font-medium">
            <li>
               <a href="/dashboard/<?php if(isset($_SESSION['role'])): echo $_SESSION['role']; endif;?>" class="flex items-center p-2 text-white hover:text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 <?php if($page === 'dashboard/'.$_SESSION['role']): echo "bg-gray-700"; else: ""; endif;?>">
                  <i class="fa-solid fa-gauge"></i>
                  <span class="ml-3">Dashboard</span>
               </a>
            </li>
            <li>
               <a href="/dashboard/tickets" class="flex items-center p-2 text-white hover:text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 <?php if($page === 'dashboard/tickets'): echo "bg-gray-700"; else: ""; endif;?>">
                  <i class="fa-solid fa-ticket"></i>
                  <span class="flex-1 ml-3 whitespace-nowrap">Tickets</span>
               </a>
            </li>
            <li>
               <a href="/dashboard/payments" class="flex items-center p-2 text-white hover:text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 <?php if($page === 'dashboard/payments'): echo "bg-gray-700"; else: ""; endif;?>">
                  <i class="fa-solid fa-credit-card"></i>
                  <span class="flex-1 ml-3 whitespace-nowrap">Payments</span>
               </a>
            </li>
            <li>
               <a href="#" class="flex items-center p-2 text-white hover:text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <i class="fa-solid fa-users"></i>
                  <span class="flex-1 ml-3 whitespace-nowrap">Users</span>
               </a>
            </li>
            <li>
               <a href="#" class="signout flex items-center p-2 text-white hover:text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-white group-hover:text-gray-900 dark:group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
                  </svg>
                  <span class="flex-1 ml-3 whitespace-nowrap">Sign Out</span>
               </a>
            </li>
         </ul>
      </div>
   </aside>

   