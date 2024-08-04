<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <title>Laravel</title>

</head>

<body class="w-full h-screen">

    <section class="w-full h-full">
        <x-navbar-guest />
        <x-hero />
    </section>

    <section class="second-section w-full h-auto min-h-full">
        <div>
            <x-shop :products="$products" />
        </div>

    </section>





    <script>
        function goToSecondSec() {
            const secondSec = document.querySelector('.second-section');
            if (secondSec) {
                secondSec.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
    </script>
</body>

</html>
