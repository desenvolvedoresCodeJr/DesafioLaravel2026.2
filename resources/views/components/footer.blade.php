<footer class="bg-[#b0b4e2] py-8 px-8 mt-10">
    <div class="max-w-7xl mx-auto flex items-center justify-between">

        <div class="flex gap-6">
            <a href="#" class="border-2 border-black rounded-full w-10 h-10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="3" width="18" height="18" rx="5" />
                    <circle cx="12" cy="12" r="4" />
                    <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" />
                </svg>
            </a>
            <a href="#" class="border-2 border-black rounded-full w-10 h-10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 9l5 3-5 3V9z" />
                    <rect x="2" y="5" width="20" height="14" rx="4" fill="none" stroke="currentColor" stroke-width="1.5" />
                </svg>
            </a>
            <a href="#" class="border-2 border-black rounded-full w-10 h-10 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13.5 21v-7.5h2.5l.5-3h-3V8.6c0-.9.3-1.5 1.6-1.5h1.5V4.4c-.3 0-1.2-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8v2.4H8v3h2.7V21h2.8z" />
                </svg>
            </a>
        </div>

        <a href="{{ route('dashboard') }}" class="font-bold text-lg text-black">HOME</a>

        <img src="{{ asset('images/logoBranca.png') }}" alt="Logo Micro Point" class="h-16 w-auto bg-white rounded-lg">
    </div>
</footer>