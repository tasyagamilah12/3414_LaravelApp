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

            <input
                type="email"
                name="email"
                placeholder="Email"
                class="w-full border p-3 mb-3 rounded"
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                class="w-full border p-3 mb-4 rounded"
            >

            <button
                type="submit"
                class="bg-indigo-600 text-white w-full py-3 rounded"
            >
                Login
            </button>

        </form>

    </div>

</div>

</body>
</html>