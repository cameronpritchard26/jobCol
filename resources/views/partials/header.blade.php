<header class="bg-white shadow">
    <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="/home" class="text-xl font-bold text-indigo-600">JobCol</a>

        @auth
            <form method="GET" action="/network" class="flex items-center">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search people..."
                    class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 w-44 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
            </form>

            <nav class="flex items-center gap-6">
                <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Jobs</a>
                <a href="{{ route('network.index') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Network</a>
                <a href="/learn-skill" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Skills</a>
                <a href="/messages" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Messages</a>
                <a href="{{ route('profile.show') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Profile</a>

                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="text-sm text-gray-600 hover:text-red-500 transition cursor-pointer">
                        Logout
                    </button>
                </form>
            </nav>
        @endauth
    </div>
</header>
