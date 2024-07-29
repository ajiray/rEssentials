@extends('layouts.adminlayout')

@section('content')
    @if (session('success'))
        <div id="success-message"
            class="bg-green border border-emerald-400 text-velvet px-4 py-3 rounded absolute mt-14 left-1/2 transform -translate-x-1/2 -translate-y-1/2 max-w-[50%] mb-4 text-center mx-auto">
            {{ session('success') }}
        </div>
    @endif
    <div class="flex w-full h-auto justify-center flex-col lg:flex-row pb-20 lg:pb-0">
        <!-- Signup Form Section -->
        <div class="w-full lg:w-[40%] bg-white p-6 rounded-lg shadow-lg mt-32 border-2 border-gray-200 mr-8">
            <h1 class="text-2xl font-bold mb-6 text-center">Sign-Up</h1>
            <form action="{{ route('admin.signup') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input type="text" name="name" id="name"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                </div>

                <div class="mb-4">
                    <label for="userType" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                    <select name="userType" id="userType"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required>
                        <option value="inventoryManager">Inventory Manager</option>
                        <option value="productManager">Product Manager</option>
                        <option value="orderManager">Order Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="inline-flex items-center justify-center w-full px-4 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-velvet bg-gold hover:bg-velvet hover:text-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold transition ease-in-out duration-300">
                        Sign Up
                    </button>
                </div>
            </form>
        </div>


        <div class="bg-white p-6 rounded-lg shadow-lg mt-32 border-2 border-gray-200 w-full lg:w-[40%]">
            <h2 class="text-2xl font-bold mb-4 text-center">Manage Users</h2>

            <!-- Search Form -->
            <form action="{{ route('newUser') }}" method="GET" class="mb-4">
                <div class="flex items-center">
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="Search by Name, Email, or Phone Number"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <button type="submit"
                        class="ml-2 border-transparent rounded-md shadow-sm text-base font-medium text-velvet bg-gold hover:bg-velvet hover:text-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold transition ease-in-out duration-300 py-2 px-4">
                        Search
                    </button>
                    @if (!empty($search))
                        <a href="{{ route('newUser') }}"
                            class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            <div class="text-gray-700">
                @foreach ($users as $user)
                    <div
                        class="mb-4 border-b border-gray-300 py-2 flex justify-between lg:items-center sm:flex-row flex-col">
                        <div>
                            <p><strong>Name:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Phone Number:</strong> {{ $user->phone_number }}</p>
                        </div>

                        @if ($user->usertype === 'banned')
                            <form action="{{ route('admin.unbanUser', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-green hover:bg-emerald-600 text-white py-2 px-4 w-full rounded-md mt-3 lg:mt-0">
                                    Unban
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.banUser', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 w-full rounded-md mt-3 lg:mt-0">
                                    Ban
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach

                @if ($users->isEmpty())
                    <p class="text-center text-gray-600">No users found.</p>
                @endif
            </div>
        </div>


    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.transition = 'opacity 1s ease-out';
                    successMessage.style.opacity = '0';
                    setTimeout(function() {
                        successMessage.remove();
                    }, 1000); // 1 second delay to remove from DOM after fade-out
                }, 3000); // 3 seconds delay before starting the fade-out
            }
        });
    </script>
@endsection
