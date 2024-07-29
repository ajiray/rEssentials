<div class="flex justify-between items-center shadow lg:px-5 lg:py-2">
    <div class="">
        <a href="#"><img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full max-w-20"></a>
    </div>
    <div class="text-center w-full hidden lg:block">
        <!-- Links placed in the middle part of the navbar -->
        <ul class="flex justify-center space-x-10">
            <li>
                <a href="{{ route('admindashboard') }}"
                    class="text-lg font-medium text-gray-600 hover:text-gray-900 {{ request()->routeIs('admindashboard') ? 'border-b-2 border-velvet' : '' }}">Home</a>
            </li>
            <li>
                <a href="{{ route('order') }}"
                    class="text-lg font-medium text-gray-600 hover:text-gray-900 {{ request()->routeIs('order') ? 'border-b-2 border-velvet' : '' }}">Orders</a>
            </li>
            <li>
                <a href="{{ route('product') }}"
                    class="text-lg font-medium text-gray-600 hover:text-gray-900 {{ request()->routeIs('product') ? 'border-b-2 border-velvet' : '' }}">Products</a>
            </li>
            <li>
                <a href="{{ route('inventory') }}"
                    class="text-lg font-medium text-gray-600 hover:text-gray-900 {{ request()->routeIs('inventory') ? 'border-b-2 border-velvet' : '' }}">Inventory</a>
            </li>
            <li>
                <a href="{{ route('newUser') }}"
                    class="text-lg font-medium text-gray-600 hover:text-gray-900 {{ request()->routeIs('newUser') ? 'border-b-2 border-velvet' : '' }}">User</a>
            </li>
        </ul>

    </div>

    <div class="flex-none hidden lg:block">
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                <div class="w-10 rounded-full">
                    <img alt="Tailwind CSS Navbar component"
                        src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" />
                </div>
            </div>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                <li>
                    <a href="{{ route('profile.edit') }}">Profile</a>
                </li>
                <li>
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>


    <i class="fa-solid fa-bars lg:hidden mr-3 text-xl cursor-pointer" onclick="showMenu()"></i>

    <div id="menu" class="hidden absolute inset-0 bg-white flex items-center justify-center z-50">

        <button class="btn btn-circle absolute right-5 top-7" onclick="showMenu()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="container mx-auto px-4">
            <div class="text-center mb-6">
                <div class="mb-8">
                    <img class="w-40 h-40 mx-auto rounded-full mb-4" alt="Tailwind CSS Navbar component"
                        src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" />
                </div>
                <ul class="grid grid-cols-2 gap-4">
                    <li class="h-32 bg-blue-200 rounded-lg">
                        <i class="fas fa-house text-gray-700 text-4xl mt-7"></i>
                        <a href="{{ route('admindashboard') }}"
                            class="block h-full text-lg font-medium text-gray-700">Home</a>
                    </li>
                    <li class="h-32 bg-green-200 rounded-lg">
                        <i class="fas fa-cart-shopping text-gray-700 text-4xl mt-7"></i>
                        <a href="{{ route('order') }}"
                            class="block h-full text-lg font-medium text-gray-700">Orders</a>
                    </li>
                    <li class="h-32 bg-yellow-200 rounded-lg">
                        <i class="fas fa-box text-gray-700 text-4xl mt-7"></i>
                        <a href="{{ route('product') }}"
                            class="block h-full text-lg font-medium text-gray-700">Products</a>
                    </li>
                    <li class="h-32 bg-purple-200 rounded-lg">
                        <i class="fas fa-warehouse text-gray-700 text-4xl mt-7"></i>
                        <a href="{{ route('inventory') }}"
                            class="block h-full text-lg font-medium text-gray-700">Inventory</a>
                    </li>
                    <li class="h-32 bg-pink-200 rounded-lg">
                        <i class="fas fa-user text-gray-700 text-4xl mt-7"></i>
                        <a href="{{ route('newUser') }}"
                            class="block h-full text-lg font-medium text-gray-700">User</a>
                    </li>
                    <li class="h-32 bg-red-200 rounded-lg">
                        <i class="fas fa-sign-out-alt text-gray-700 text-4xl mt-7"></i>
                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="block h-full text-lg font-medium text-gray-700">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>

</div>


<script>
    function showMenu() {
        var menu = document.getElementById("menu");
        menu.classList.toggle("hidden");
    }
</script>


</div>
