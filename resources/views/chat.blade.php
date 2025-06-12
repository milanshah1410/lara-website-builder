<x-app-layout>
    <head>
        <meta charset="UTF-8">
        <title>AI Web Builder</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4">
        <div class="w-full max-w-2xl text-center bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-bold text-center mb-6">AI Website Builder</h1>

            <form id="promptForm" class="space-y-4">
                <label for="prompt" class="block text-lg font-medium text-gray-700">Describe your website</label>
                <textarea
                    id="prompt"
                    name="prompt"
                    rows="6"
                    placeholder="e.g., A portfolio site with about, blog, and contact pages"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-y"
                    required></textarea>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition">
                    Generate
                </button>
            </form>

            <div id="responseBox" class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded text-sm text-gray-800 whitespace-pre-wrap"></div>
        </div>
    </div>

    <script>
        document.getElementById('promptForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const prompt = document.getElementById('prompt').value;
            const responseBox = document.getElementById('responseBox');

            responseBox.innerHTML = '<p class="text-blue-600 font-medium">⏳ Generating your website, please wait...</p>';

            try {
                const res = await fetch('/chat/prompt', {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    },
                    body: JSON.stringify({ prompt }),
                });

                const data = await res.json();

                if (data.project_id) {
                    responseBox.innerHTML = '<p class="text-green-600 font-medium">✅ Website generated! Redirecting...</p>';
                    setTimeout(() => {
                        window.location.href = `/preview-blade/${data.project_id}`;
                    }, 1000);
                } else {
                    responseBox.innerHTML = '<p class="text-red-600 font-medium">❌ Failed to generate website. Try again.</p>';
                }

            } catch (error) {
                responseBox.innerHTML = `<p class="text-red-600 font-medium">❌ Error: ${error.message}</p>`;
            }
        });
    </script>
</x-app-layout>
