<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="flex justify-center items-center h-screen">

        <div class="bg-white p-8 rounded-lg shadow-lg w-96">

            <h1 class="text-3xl font-bold mb-6 text-center">
                Login Admin
            </h1>

            @if ($errors->any())
                <div class="bg-red-100 text-red-600 p-3 mb-3 rounded">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">

                @csrf

                <input type="email" name="email" placeholder="Email" class="w-full border p-3 mb-3 rounded">

                <input type="password" name="password" placeholder="Password" class="w-full border p-3 mb-4 rounded">

                <button type="submit" class="bg-indigo-600 text-white w-full py-3 rounded">
                    Login
                </button>

            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-white dark:bg-slate-800 px-3 text-slate-500 font-medium">Atau masuk dengan</span>
                </div>
            </div>

            {{-- Tombol Google SSO --}}
            <a href="{{ route('auth.google') }}"
                class="w-full inline-flex items-center justify-center gap-3 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-100 font-semibold py-3 px-4 rounded-xl border border-slate-300 dark:border-slate-600 shadow-sm transition duration-200">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4"
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853"
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05"
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                    <path fill="#EA4335"
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                </svg>
                Continue with Google
            </a>

        </div>

    </div>

</body>

</html>
