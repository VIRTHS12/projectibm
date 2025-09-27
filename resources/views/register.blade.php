<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Create an Account</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-slate-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full px-3 py-2 border rounded-lg" required autofocus>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-slate-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-slate-700">Password</label>
                <input type="password" name="password" id="password" class="w-full px-3 py-2 border rounded-lg"
                    required>
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block text-slate-700">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                Register
            </button>
        </form>
        <p class="text-center text-sm text-slate-600 mt-4">
            Already have an account?
            <a href="/login" class="font-medium text-indigo-600 hover:text-indigo-500">Login here</a>
        </p>
    </div>
</body>

</html>
