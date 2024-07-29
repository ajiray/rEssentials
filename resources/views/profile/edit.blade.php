<x-app-layout>

    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 p-4 sm:p-8 sm:rounded-lg">
            @if (auth()->user()->usertype == 'user')
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800">
                    <i class="fa-solid fa-angles-left text-xl"></i>
                    <span>Back</span>
                </a>
            @elseif(auth()->user()->usertype == 'admin')
                <a href="{{ route('admindashboard') }}"
                    class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800">
                    <i class="fa-solid fa-angles-left text-xl"></i>
                    <span>Back</span>
                </a>
            @endif



            @include('profile.partials.update-profile-information-form')



            @include('profile.partials.update-password-form')


            @include('profile.partials.delete-user-form')


        </div>
    </div>
</x-app-layout>
