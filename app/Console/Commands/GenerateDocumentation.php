<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;

class GenerateDocumentation extends Command
{
  protected $signature = 'docs:generate {--output=laravel-documentation.pdf} {--simple : Generate simple documentation without styling}';
  protected $description = 'Generate project documentation for Controllers, Models, Migrations, and Views';

  public function handle()
  {
    $this->info('🚀 Generating Laravel project documentation...');

    try {
      // Collect documentation data
      $documentation = $this->collectDocumentationData();

      // Generate PDF
      $this->generatePDF($documentation);

      $this->info('✅ Documentation generated successfully!');
      return Command::SUCCESS;
    } catch (\Exception $e) {
      $this->error('❌ Error generating documentation: ' . $e->getMessage());
      $this->error('Stack trace: ' . $e->getTraceAsString());
      return Command::FAILURE;
    }
  }

  private function collectDocumentationData()
  {
    $this->info('📊 Collecting project data...');

    return [
      'project_info' => $this->getProjectInfo(),
      'controllers' => $this->getControllersData(),
      'models' => $this->getModelsData(),
      'migrations' => $this->getMigrationsData(),
      'views' => $this->getViewsData(),
    ];
  }

  private function getProjectInfo()
  {
    $composerPath = base_path('composer.json');
    $projectInfo = [
      'name' => 'Laravel Project',
      'description' => 'Laravel Application Documentation',
      'generated_at' => now()->format('Y-m-d H:i:s'),
    ];

    if (File::exists($composerPath)) {
      try {
        $content = File::get($composerPath);
        $composer = json_decode($content, true);
        if ($composer) {
          $projectInfo['name'] = $composer['name'] ?? 'Laravel Project';
          $projectInfo['description'] = $composer['description'] ?? 'Laravel Application Documentation';
          $projectInfo['laravel_version'] = $composer['require']['laravel/framework'] ?? 'N/A';
          $projectInfo['php_version'] = $composer['require']['php'] ?? 'N/A';
        }
      } catch (\Exception $e) {
        $this->warn('Could not parse composer.json: ' . $e->getMessage());
      }
    }

    return $projectInfo;
  }

  private function getControllersData()
  {
    $this->info('🎮 Processing Controllers...');

    $controllers = [];
    $controllerPath = app_path('Http/Controllers');

    if (File::isDirectory($controllerPath)) {
      $files = File::allFiles($controllerPath);

      foreach ($files as $file) {
        if ($file->getExtension() === 'php') {
          try {
            $relativePath = str_replace(app_path(), 'app', $file->getPathname());
            $content = $this->readFileContent($file->getPathname());

            // Extract methods from controller
            $methods = $this->extractMethods($content);

            $controllers[] = [
              'name' => $file->getBasename('.php'),
              'path' => $relativePath,
              'content' => $this->sanitizeContent($content),
              'methods' => $methods,
              'size' => $file->getSize(),
            ];
          } catch (\Exception $e) {
            $this->warn("Skipping controller {$file->getFilename()}: " . $e->getMessage());
          }
        }
      }
    }

    $this->info('   Found ' . count($controllers) . ' controllers');
    return $controllers;
  }

  private function getModelsData()
  {
    $this->info('🗃️  Processing Models...');

    $models = [];
    $modelPath = app_path('Models');

    if (File::isDirectory($modelPath)) {
      $files = File::allFiles($modelPath);

      foreach ($files as $file) {
        if ($file->getExtension() === 'php') {
          try {
            $relativePath = str_replace(app_path(), 'app', $file->getPathname());
            $content = $this->readFileContent($file->getPathname());

            // Extract model properties
            $properties = $this->extractModelProperties($content);

            $models[] = [
              'name' => $file->getBasename('.php'),
              'path' => $relativePath,
              'content' => $this->sanitizeContent($content),
              'properties' => $properties,
              'size' => $file->getSize(),
            ];
          } catch (\Exception $e) {
            $this->warn("Skipping model {$file->getFilename()}: " . $e->getMessage());
          }
        }
      }
    }

    $this->info('   Found ' . count($models) . ' models');
    return $models;
  }

  private function getMigrationsData()
  {
    $this->info('🗂️  Processing Migrations...');

    $migrations = [];
    $migrationPath = database_path('migrations');

    if (File::isDirectory($migrationPath)) {
      $files = File::allFiles($migrationPath);

      foreach ($files as $file) {
        if ($file->getExtension() === 'php') {
          try {
            $content = $this->readFileContent($file->getPathname());

            $migrations[] = [
              'name' => $file->getBasename('.php'),
              'filename' => $file->getFilename(),
              'content' => $this->sanitizeContent($content),
              'size' => $file->getSize(),
              'created_date' => $this->extractDateFromMigration($file->getFilename()),
            ];
          } catch (\Exception $e) {
            $this->warn("Skipping migration {$file->getFilename()}: " . $e->getMessage());
          }
        }
      }

      // Sort by filename (which includes timestamp)
      usort($migrations, function ($a, $b) {
        return strcmp($a['filename'], $b['filename']);
      });
    }

    $this->info('   Found ' . count($migrations) . ' migrations');
    return $migrations;
  }

  private function getViewsData()
  {
    $this->info('👁️  Processing Views...');

    $views = [];
    $viewsPath = resource_path('views');

    if (File::isDirectory($viewsPath)) {
      $files = File::allFiles($viewsPath);

      foreach ($files as $file) {
        if (str_contains($file->getFilename(), '.blade.php')) {
          try {
            $relativePath = str_replace(resource_path('views') . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $content = $this->readFileContent($file->getPathname());

            $views[] = [
              'name' => $file->getBasename('.blade.php'),
              'path' => $relativePath,
              'content' => $this->sanitizeContent($content),
              'size' => $file->getSize(),
              'type' => $this->getViewType($content),
            ];
          } catch (\Exception $e) {
            $this->warn("Skipping view {$file->getFilename()}: " . $e->getMessage());
          }
        }
      }
    }

    $this->info('   Found ' . count($views) . ' views');
    return $views;
  }

  /**
   * Read file content with safe encoding
   */
  private function readFileContent($filePath)
  {
    $content = file_get_contents($filePath);

    if ($content === false) {
      throw new \Exception("Cannot read file: {$filePath}");
    }

    // Convert to UTF-8 and remove problematic characters
    $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8//IGNORE');
    $content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $content);

    return $content;
  }

  /**
   * Sanitize content for PDF generation
   */
  private function sanitizeContent($content)
  {
    // Escape HTML characters
    $content = htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    // Limit content length to prevent memory issues
    if (strlen($content) > 30000) {
      $content = substr($content, 0, 30000) . "\n\n... [Content truncated for PDF generation] ...";
    }

    return $content;
  }

  private function extractMethods($content)
  {
    $methods = [];
    if (preg_match_all('/public function (\w+)\s*$$[^)]*$$/', $content, $matches)) {
      $methods = $matches[1];
    }
    return $methods;
  }

  private function extractModelProperties($content)
  {
    $properties = [];

    // Extract fillable
    if (preg_match('/protected \$fillable\s*=\s*\[(.*?)\]/s', $content, $matches)) {
      $fillable = str_replace(['"', "'", ' ', "\n", "\r"], '', $matches[1]);
      $properties['fillable'] = array_filter(explode(',', $fillable));
    }

    // Extract table name
    if (preg_match('/protected \$table\s*=\s*[\'"]([^\'"]+)[\'"]/', $content, $matches)) {
      $properties['table'] = $matches[1];
    }

    return $properties;
  }

  private function extractDateFromMigration($filename)
  {
    if (preg_match('/^(\d{4}_\d{2}_\d{2}_\d{6})_/', $filename, $matches)) {
      $timestamp = $matches[1];
      try {
        $date = \DateTime::createFromFormat('Y_m_d_His', $timestamp);
        return $date ? $date->format('Y-m-d H:i:s') : 'Unknown';
      } catch (\Exception $e) {
        return 'Unknown';
      }
    }

    return 'Unknown';
  }

  private function getViewType($content)
  {
    if (str_contains($content, '@extends')) {
      return 'Layout Child';
    } elseif (str_contains($content, '@yield') || str_contains($content, '@section')) {
      return 'Layout Master';
    } elseif (str_contains($content, '@component')) {
      return 'Component';
    } else {
      return 'Simple View';
    }
  }

  private function generatePDF($documentation)
  {
    $this->info('📄 Generating PDF...');

    try {
      // Use simple template if option is set
      $templateName = $this->option('simple') ? 'documentation.simple' : 'documentation.template';

      // Check if view exists
      if (!view()->exists($templateName)) {
        $this->error("❌ View {$templateName} not found!");
        $this->info("Please create the view file: resources/views/{$templateName}.blade.php");
        return;
      }

      $html = view($templateName, compact('documentation'))->render();
      
      // Sanitize HTML content to prevent encoding issues
      $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

      // Create PDF with safe configuration to avoid iconv/font issues
      $pdf = Pdf::loadHTML($html)
        ->setPaper('A4', 'portrait')
        ->setOptions([
          'defaultFont' => 'DejaVu Sans', // Use built-in safe font
          'isRemoteEnabled' => false,
          'isHtml5ParserEnabled' => true,
          'debugKeepTemp' => false,
          'debugCss' => false,
          'debugLayout' => false,
          'enable_font_subsetting' => false, // Disable font subsetting to avoid iconv issues
          'fontHeightRatio' => 1.1,
          'isPhpEnabled' => false,
          'enable_remote' => false,
          'dpi' => 96,
          'chroot' => realpath(base_path()),
        ]);

      // Ensure storage directory exists
      $storageDir = storage_path('app/public');
      if (!File::isDirectory($storageDir)) {
        File::makeDirectory($storageDir, 0755, true);
      }

      $outputPath = storage_path('app/public/' . $this->option('output'));
      $pdf->save($outputPath);

      $this->info("📁 PDF saved as: storage/app/public/{$this->option('output')}");
      $this->info("📊 File size: " . $this->formatBytes(filesize($outputPath)));
      $this->info("🔗 Access URL: " . url('storage/' . $this->option('output')));
    } catch (\Exception $e) {
      $this->error('Error generating PDF: ' . $e->getMessage());
      throw $e;
    }
  }

  private function formatBytes($size, $precision = 2)
  {
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
  }
}
