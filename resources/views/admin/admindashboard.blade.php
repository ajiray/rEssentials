@extends('layouts.adminlayout')

@section('content')
    <div class="w-full h-auto py-20 flex justify-center items-center">
        <div class="flex justify-center items-center flex-col p-6 rounded-lg bg-green hover:bg-emerald-600 cursor-pointer text-white transition duration-300 ease-in-out transform hover:scale-105"
            onclick="my_modal_3.showModal()">
            <i class="fa-solid fa-cash-register text-4xl mb-2"></i>
            <p class="text-lg font-semibold">Sales Report</p>
        </div>


    </div>


    <x-sales :orders="$orders" :transactions="$transactions" :remainingStock="$remainingStock" />
@endsection
