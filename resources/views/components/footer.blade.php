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
                <p class="mb-2">123 E-commerce St.</p>
                <p class="mb-2">City, State 12345</p>
                <p class="mb-2">Email: info@example.com</p>
                <p>Phone: (123) 456-7890</p>
            </div>
            <!-- Social Media -->
            <div class="flex space-x-4">
                <a href="#" class="text-gray-200 hover:text-gray-400">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="text-gray-200 hover:text-gray-400">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="text-gray-200 hover:text-gray-400">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="text-gray-200 hover:text-gray-400">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div>
        <div class="text-center mt-6 text-gray-200">
            <p>&copy; 2024 R Essentials. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Include Font Awesome -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
