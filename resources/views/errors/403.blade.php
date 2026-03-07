<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>403 Forbidden</title>
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen bg-apple-bg text-apple-text antialiased">
    <main class="mx-auto flex min-h-screen max-w-3xl items-center px-6 py-12">
        <section class="w-full rounded-[2rem] border border-apple-border/60 bg-white p-8 shadow-[0_20px_80px_rgba(15,23,42,0.08)] sm:p-10">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-apple-blue">403</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">Developer panel access denied</h1>
            <p class="mt-4 max-w-2xl text-base leading-7 text-apple-muted">
                Signed in account does not have permission to access this area. If you used the wrong Google account, sign out and try again.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="/developer/login"
                    class="inline-flex items-center justify-center rounded-full bg-apple-blue px-6 py-3 text-sm font-semibold text-white transition hover:opacity-90">
                    Back to developer login
                </a>

                @auth
                    <button type="button" id="logoutButton"
                        class="inline-flex items-center justify-center rounded-full border border-apple-border px-6 py-3 text-sm font-semibold text-apple-text transition hover:border-apple-blue hover:text-apple-blue">
                        Sign out
                    </button>
                @endauth
            </div>

            <p id="logoutError" class="mt-4 hidden text-sm text-red-600"></p>
        </section>
    </main>

    @auth
        <script>
            document.getElementById('logoutButton')?.addEventListener('click', async () => {
                const errorNode = document.getElementById('logoutError');

                try {
                    const response = await fetch('/api/auth/logout', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                    });

                    if (!response.ok) {
                        throw new Error('Logout request failed.');
                    }

                    window.location.href = '/developer/login';
                } catch (error) {
                    errorNode.textContent = 'Sign out failed. Please refresh the page and try again.';
                    errorNode.classList.remove('hidden');
                }
            });
        </script>
    @endauth
</body>

</html>
