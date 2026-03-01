<x-filament-panels::page.simple>
    <style>
        .g-btn-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
    </style>

    <div class="text-center">
        <h2 class="text-xl font-bold mb-4">Developer Login</h2>
        <p class="text-gray-500 text-sm mb-6">Please sign in with your Google account to access API tokens.</p>

        <div id="googleButton" class="g-btn-wrapper"></div>
        <div id="loginError" class="text-danger text-sm mt-2 hidden" style="color: red;"></div>
    </div>

    <!-- Google Identity Services -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.7.9/dist/axios.min.js"></script>
    <script>
        window.onload = function () {
            const clientId = '{{ config('services.google.client_id') }}';

            if (!clientId) {
                document.getElementById('loginError').innerText = 'Google Client ID is not configured on the server.';
                document.getElementById('loginError').classList.remove('hidden');
                return;
            }

            google.accounts.id.initialize({
                client_id: clientId,
                callback: handleCredentialResponse
            });

            google.accounts.id.renderButton(
                document.getElementById("googleButton"),
                { theme: "outline", size: "large", type: "standard", shape: "pill" }
            );
        };

        function handleCredentialResponse(response) {
            axios.post('/api/auth/google', {
                credential: response.credential
            }, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(res => {
                if (res.data && res.data.ok) {
                    window.location.href = '/developer';
                } else {
                    document.getElementById('loginError').innerText = 'Authentication failed. Please try again.';
                    document.getElementById('loginError').classList.remove('hidden');
                }
            }).catch(err => {
                document.getElementById('loginError').innerText = err.response?.data?.message || 'Authentication error. Please try again.';
                document.getElementById('loginError').classList.remove('hidden');
            });
        }
    </script>
</x-filament-panels::page.simple>