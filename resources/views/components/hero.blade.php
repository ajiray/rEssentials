<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<!-- Hero Section -->

<style>
    .text-shadow-lg {
        text-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .font-serif {
        font-family: 'Playfair Display', serif;
    }
</style>
<div class="hero min-h-screen bg-blanc">
    <div class="hero-content text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-8xl font-bold text-center mb-6 sm:mb-8 text-black"
                data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                <span class="block font-serif text-gold text-shadow-lg mb-5 lg:mb-0"
                    style="font-size: 2em; letter-spacing: 2px;">Brhee's</span>
                <span class="block font-serif text-gold text-shadow-lg"
                    style="font-size: 1em; letter-spacing: 1px;">Clothing Boutique</span>
            </h1>
            <p class="text-base sm:text-lg md:text-xl text-center mb-8 sm:mb-12 text-black" data-aos="fade-up"
                data-aos-delay="300" data-aos-duration="1000">Discover the latest trends in fashion and shop our
                collection of clothing, accessories, and more.</p>
            <button
                class="btn btn-primary px-6 sm:px-8 text-base sm:text-lg bg-black text-blanc border-0 hover:bg-slate-700"
                onclick="goToSecondSec()" data-aos="fade-in" data-aos-duration="1000" data-aos-delay="800">Shop
                Now</button>
        </div>
    </div>
</div>


<script>
    AOS.init({
        easing: 'ease-in-out',
        once: true
    });
</script>
