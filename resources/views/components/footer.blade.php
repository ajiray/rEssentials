<footer class="bg-velvet text-gray-200 py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center text-gray-200">
            <!-- Quick Links -->
            <div class="mb-6 md:mb-0">
                <h5 class="uppercase font-bold mb-4">Quick Links</h5>
                <ul class="list-none space-y-2">
                    <li><a href="#" class="hover:underline">Home</a></li>
                    <li><button onclick="goToSecondSec()" class="hover:underline">Shop</button></li>
                    <li><a href="{{ route('checkOrders') }}" class="hover:underline">Orders</a></li>
                    <li><a href="{{ route('profile.edit') }}" class="hover:underline">Profile</a></li>
                </ul>
            </div>
            <!-- Contact Info -->
            <div class="mb-6 md:mb-0">
                <h5 class="uppercase font-bold mb-4">Contact Us</h5>
                <p class="mb-2">Cavite</p>
                <p class="mb-2">Bacoor, Molino V</p>
                <p class="mb-2">Email: brhee0214@gmail.com</p>
                <p>Phone: (0927) 307 5924</p>
            </div>
            <!-- Social Media -->
            <div class="flex space-x-4">
                <a href="https://www.facebook.com/profile.php?id=61554777501734" target="_blank" class="text-gray-200 hover:text-gray-400">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.tiktok.com/@brheesclothingbou?lang=en" target="_blank" class="text-gray-200 hover:text-gray-400">
                    <i class="fab fa-tiktok"></i>
                </a>
            </div>
        </div>
        <div class="text-center mt-6 text-gray-200">
            <p>&copy; 2024 Brhee's Clothing Boutique. All rights reserved.</p>
        </div>
    </div>
</footer>


