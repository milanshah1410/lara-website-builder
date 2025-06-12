<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\ProjectFile;
use Illuminate\Support\Facades\View;
use App\Helpers\BladeFileHelper;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');

    Route::post('chat/prompt', [ChatController::class, 'promptComple']);

    Route::get('/preview-blade/{project}/{view?}', function ($projectId, $view = 'pages.home') {
        $basePath = BladeFileHelper::getBasePath($projectId);
        //$basePath = BladeFileHelper::saveBladeFilesToDisk($projectId);
        View::addNamespace('project', $basePath);

        // Fix: Ensure view path has correct namespace structure
        if (!Str::contains($view, '.')) {
            $view = 'pages.' . $view;
        }

        try {
            return view("project::{$view}", ['project_id' => $projectId]);
        } catch (\Exception $e) {
            return response("<h1>Error rendering view:</h1><pre>{$e->getMessage()}</pre>", 500);
        }
    });

    //Old Routes
    Route::get('/projects/{project}/editor', function (App\Models\Project $project) {
        return view('project.editor', compact('project'));
    });

    Route::get('/preview/{project}/{page?}', function ($projectId, $page = 'index.html') {
        // 1. Load all files into a key => value map
        $files = ProjectFile::where('project_id', $projectId)
            ->get()
            ->keyBy('filename');

        // 2. Get the requested page
        $html = $files[$page]->content ?? "<h1>$page not found</h1>";

        // 3. Include support: Replace {{ include('header.html') }} with actual content
        $html = preg_replace_callback('/\{\{\s*include\(\'(.*?)\'\)\s*\}\}/', function ($matches) use ($files) {
            $filename = $matches[1];
            return $files[$filename]->content ?? "<!-- $filename not found -->";
        }, $html);

        // 4. Inject CSS before </head>
        $css = $files['styles.css']->content ?? '';
        if ($css && str_contains($html, '</head>')) {
            $html = str_replace('</head>', "<style>\n$css\n</style>\n</head>", $html);
        }

        // 5. Inject JS before </body>
        $js = $files['script.js']->content ?? '';
        if ($js && str_contains($html, '</body>')) {
            $html = str_replace('</body>', "<script>\n$js\n</script>\n</body>", $html);
        }

        return response($html);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
