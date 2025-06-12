<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\ProjectFile;
use Illuminate\Support\Facades\View;

class BladeFileHelper
{
    public static function getBasePath($projectId)
    {
        return storage_path("framework/views/generated/{$projectId}");
    }

    public static function saveBladeFilesToDisk($projectId)
    {
        $files = ProjectFile::where('project_id', $projectId)->get();

        $basePath = storage_path("framework/views/generated/{$projectId}");
        File::ensureDirectoryExists($basePath);

        foreach ($files as $file) {
            $filename = $file->filename;
            $content = $file->content;
            $content = str_replace('{project_id}', $projectId, $content);

            // 1. Save Blade templates (.blade.php)
            if (Str::endsWith($filename, '.blade.php')) {
                $fullPath = $basePath . '/' . $filename;
                File::ensureDirectoryExists(dirname($fullPath));
                File::put($fullPath, $content);
            }

            // 2. Save CSS files (public path)
            elseif (Str::endsWith($filename, '.css')) {
                $cssPath = public_path("generated/{$projectId}/" . $filename);
                File::ensureDirectoryExists(dirname($cssPath));
                File::put($cssPath, $content);
            }

            // 3. Save JS files (optional)
            elseif (Str::endsWith($filename, '.js')) {
                $jsPath = public_path("generated/{$projectId}/" . $filename);
                File::ensureDirectoryExists(dirname($jsPath));
                File::put($jsPath, $content);
            }
        }
        
        return $basePath;
    }
}
