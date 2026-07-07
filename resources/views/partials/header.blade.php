<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">

        <a href="/" class="text-2xl font-bold text-blue-600">
            DentalCare
        </a>

        <nav class="space-x-6 hidden md:block">
            <a href="/" class="text-gray-700 hover:text-blue-600">Home</a>
            <a href="#" class="text-gray-700 hover:text-blue-600">Services</a>
            <a href="#" class="text-gray-700 hover:text-blue-600">Doctors</a>
            <a href="#" class="text-gray-700 hover:text-blue-600">Contact</a>
        </nav>

        <div class="space-x-4">
            @auth
                <a href="/dashboard" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">
                    Login
                </a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Register
                </a>
            @endauth
        </div>
    </div>
</header>
