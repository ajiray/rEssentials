<link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">

<div class="navbar bg-base-100 shadow relative xl:px-5">
    <div class="flex-1">
        <a><img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full max-w-20"></a>
    </div>
    <div class="flex-none hidden md:block">
        <ul class="menu menu-horizontal px-1">
            <li>
                <a href="{{ route('login') }}" class="py-3 px-4 block text-gray-700 hover:bg-gray-100 text-lg">Login</a>
            </li>
            <li>
                <a href="{{ route('register') }}" class="py-3 px-4 block text-gray-700 hover:bg-gray-100 text-lg">Sign
                    Up</a>
            </li>

        </ul>
    </div>
    <div class="flex-none md:hidden">
        <a class="btn btn-ghost text-xl" onclick="toggleUserAuth()"><i class="fa-solid fa-user"></i></a>
    </div>

    <div id="userAuth" class="absolute right-5 top-12 bg-white shadow-lg rounded-md hidden">
        <ul class="menu menu-vertical">
            <li>
                <a href="{{ route('login') }}" class="py-3 px-4 block text-gray-700 hover:bg-gray-100">Login</a>
            </li>
            <li>
                <a href="{{ route('register') }}" class="py-3 px-4 block text-gray-700 hover:bg-gray-100">Sign Up</a>
            </li>

        </ul>
    </div>

</div>

<script>
    function toggleUserAuth() {
        var userAuthMenu = document.getElementById('userAuth');
        userAuthMenu.classList.toggle('hidden');
    }
</script>
