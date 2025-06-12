<?php

namespace App\Http\Controllers;

use App\Helpers\BladeFileHelper;
use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{

    public function prompt(Request $request)
    {
        try {
            $prompt = $request->input('prompt');
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that builds websites.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);


            return response()->json($response->choices[0]->message->content);
        } catch (\OpenAI\Exceptions\ErrorException $e) {
            if (str_contains($e->getMessage(), 'You exceeded your current quota')) {
                return response()->json(['error' => 'Quota exceeded. Please check your OpenAI plan.'], 429);
            }
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function index()
    {
        return view('chat'); // or whatever view you want to show
    }

    public function promptComple(Request $request)
    {
        $prompt = $request->input('prompt');
        $projectName = $request->input('project_name', 'Untitled Project');

        // Step 1: Create or update the project
        $project = Project::updateOrCreate(
            ['user_id' => Auth::id()],
            ['name' => $projectName]
        );

        // Step 2: Make request to OpenAI API
        $response = Http::timeout(60)
            ->withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini', // or 'gpt-4' if needed
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => <<<EOT
                                        You are a Laravel Blade website generator with user authentication.

                                        Generate modular Laravel Blade templates that include:
                                        - Layouts: `layouts/app.blade.php`
                                        - Partials: `partials/header.blade.php`, `partials/footer.blade.php`, etc.
                                        - Pages: `pages/home.blade.php`, `pages/about.blade.php`, etc.
                                        - Auth pages:
                                        - `auth/login.blade.php`
                                        - `auth/register.blade.php`
                                        - `auth/dashboard.blade.php`
                                        
                                        Authentication Features:
                                        - Login form with fields: email, password.
                                        - Register form with fields: name, email, password, confirm password.
                                        - Dashboard page shown after login.
                                        - Use Laravel `@csrf`, validation messages, and session messages.

                                        Navigation Bar (in `partials/header.blade.php`):
                                        - Use Laravel's `url()` helper to link routes.
                                        - Show “Login” and “Register” if not logged in.
                                        - Show “Dashboard” and “Logout” if logged in.
                                        - Use Blade’s `@auth` and `@guest` directives.

                                        Styling:
                                        - Use Tailwind CSS utility classes.
                                        - Make forms responsive and clean.
                                        - Include `styles.css` and `script.js`.

                                        Return modular Blade view files using Laravel syntax like:
                                        - @extends('project::layouts.app')
                                        - @section('content')
                                        - @include('project::partials.header')
                                        - @include('project::partials.footer')
                                        
                                        Output format:
                                        Use proper code blocks and filenames like:
                                        ```blade filename=auth/login.blade.php
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <input type="email" name="email" required>
                                            <input type="password" name="password" required>
                                            <button type="submit">Login</button>
                                        </form>

                                        For navigation links, use Laravel's `url()` helper and pass the `project_id` like this:
                                        ```blade
                                        <a href="{{ url('preview-blade/{project_id}/home') }}">Home</a>
                                        <a href="{{ url('preview-blade/{project_id}/about') }}">About</a>
                                        <a href="{{ url('preview-blade/{project_id}/services') }}">Services</a>
                                        <a href="{{ url('preview-blade/{project_id}/contact') }}">Contact</a>

                                        Follow this structure:
                                        - Layout file: `layouts/app.blade.php`
                                        - Partial components: `partials/header.blade.php`, `partials/footer.blade.php`, etc.
                                        - Pages: `pages/home.blade.php`, `pages/about.blade.php`, etc.

                                        Use Blade-specific features like `@yield`, `@section`, and `@include` properly.

                                        Also return:
                                        - A `styles.css` file using Tailwind CSS with utility classes.
                                        - A `script.js` file for optional interactivity (e.g., navbar toggle, modal).

                                        Use proper code blocks with filenames like this:

                                        ```blade filename=layouts/app.blade.php
                                        {{-- Layout HTML --}}
                                        <!DOCTYPE html>
                                        <html lang="en">
                                        <head>
                                            <meta charset="UTF-8">
                                            <title>My Site</title>
                                            <link rel="stylesheet" href="{{ asset('generated/{project_id}/styles.css') }}">
                                            <script src="https://cdn.tailwindcss.com"></script>
                                        </head>
                                        <body>
                                            @include('project::partials.header')

                                            <main>
                                                @yield('content')
                                            </main>

                                            @include('project::partials.footer')
                                            <script src="{{ asset('generated/{project_id}/script.js') }}"></script>
                                        </body>
                                        </html>
                                        @extends('project::layouts.app')

                                        @section('content')
                                            <h1 class="text-3xl font-bold">Welcome to My Website</h1>
                                        @endsection
                                        blade

                                        <header class="bg-gray-800 text-white p-4">
                                            <nav>
                                                <a href="/" class="mr-4">Home</a>
                                                <a href="/about">About</a>
                                            </nav>
                                        </header>

                                        @tailwind base;
                                        @tailwind components;
                                        @tailwind utilities;

                                        /* Add any custom styles below */
                                        body {
                                            font-family: 'Inter', sans-serif;
                                        }
                                        // Example: Toggle mobile menu
                                        document.addEventListener("DOMContentLoaded", function () {
                                            console.log('JS loaded');
                                        });
                                        ### 📌 Notes

                                        - Replace `{project_id}` dynamically in your Blade rendering logic (or in your controller before sending the prompt).
                                        - This setup ensures that:
                                        - Your AI returns structured Blade templates.
                                        - Tailwind CSS and JS are included properly.
                                        - Layouts and components are modular and reusable.
                                        - Dummy content is provided for testing.

                                        Result:
                                        Using this prompt with OpenAI, the AI will generate:
                                        - Properly structured Laravel auth pages
                                        - Navigation logic using Blade (`@auth`, `@guest`)
                                        - Clean Tailwind-styled UI
                                        - Output in Blade format, ready to save to `.blade.php` files

                                        EOT
                    ],


                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]);

        // Handle API failure
        if ($response->failed()) {
            return response()->json([
                'error' => $response->json(),
            ], 500);
        }

        // Step 3: Parse the AI response
        $aiOutput = $response['choices'][0]['message']['content'];

        $pattern = '/```(html|blade|php|css|js)(?: filename=(.*?))?\n(.*?)```/s';
        preg_match_all($pattern, $aiOutput, $matches, PREG_SET_ORDER);

        $htmlCounter = 0;

        foreach ($matches as $match) {
            $extension = $match[1];                  // blade, html, css, js, php
            $filename  = trim($match[2] ?? '');      // from code block filename=
            $content   = trim($match[3]);            // file content

            // Auto-generate a fallback filename if not provided
            if (!$filename) {
                $filename = match ($extension) {
                    'blade', 'html' => $htmlCounter++ === 0 ? 'index.blade.php' : "page{$htmlCounter}.blade.php",
                    'css' => 'styles.css',
                    'js' => 'script.js',
                    'php' => 'functions.php',
                    default => "file_{$htmlCounter}.txt",
                };
            }

            // Save file to DB
            ProjectFile::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'filename'   => $filename,
                ],
                [
                    'content'    => $content,
                ]
            );
        }

        // Optional: Save matches JSON for debugging
        ProjectFile::updateOrCreate(
            [
                'project_id' => $project->id,
                'filename'   => 'matches.json',
            ],
            [
                'content'    => json_encode($matches, JSON_PRETTY_PRINT),
            ]
        );

        BladeFileHelper::saveBladeFilesToDisk($project->id);

        return response()->json([
            'message'     => 'All files saved successfully.',
            'project_id'  => $project->id,
            'ai_output'   => $aiOutput,
        ]);
    }
}
