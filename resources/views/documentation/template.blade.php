<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $documentation['project_info']['name'] ?? 'Laravel Project' }} - Documentation</title>
    <style>
      body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        margin: 15px;
        line-height: 1.4;
        font-size: 11px;
      }

      .header {
        text-align: center;
        border-bottom: 3px solid #e74c3c;
        padding-bottom: 20px;
        margin-bottom: 30px;
      }

      h1 {
        color: #e74c3c;
        font-size: 20px;
        margin: 0;
      }

      h2 {
        color: #3498db;
        border-bottom: 2px solid #3498db;
        padding-bottom: 5px;
        margin-top: 25px;
        font-size: 16px;
      }

      h3 {
        color: #2ecc71;
        margin-top: 20px;
        font-size: 14px;
      }

      .project-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin: 20px 0;
        border: 1px solid #dee2e6;
      }

      .code-block {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        padding: 8px;
        margin: 8px 0;
        font-family: 'Courier New', 'DejaVu Sans Mono', monospace;
        font-size: 7px;
        white-space: pre-wrap;
        word-wrap: break-word;
        border-radius: 3px;
        overflow: hidden;
      }

      .file-header {
        background: #343a40;
        color: white;
        padding: 6px 12px;
        margin: 15px 0 0 0;
        font-weight: bold;
        font-size: 9px;
        border-radius: 3px 3px 0 0;
      }

      .stats-table {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0;
        font-size: 10px;
      }

      .stats-table th,
      .stats-table td {
        border: 1px solid #dee2e6;
        padding: 6px;
        text-align: left;
      }

      .stats-table th {
        background-color: #f8f9fa;
        font-weight: bold;
      }

      .toc {
        background: #f8f9fa;
        padding: 15px;
        margin: 20px 0;
        border-left: 4px solid #007bff;
        border-radius: 3px;
      }

      .page-break {
        page-break-before: always;
      }

      .summary-box {
        background: #e8f5e8;
        border: 1px solid #28a745;
        padding: 10px;
        margin: 10px 0;
        border-radius: 3px;
      }

      .method-list,
      .property-list {
        background: #fff3cd;
        border: 1px solid #ffc107;
        padding: 8px;
        margin: 5px 0;
        border-radius: 3px;
        font-size: 9px;
      }

      .badge {
        display: inline-block;
        padding: 2px 6px;
        font-size: 8px;
        font-weight: bold;
        border-radius: 3px;
        margin-right: 5px;
        color: white;
      }

      .badge-controller {
        background: #007bff;
      }

      .badge-model {
        background: #28a745;
      }

      .badge-migration {
        background: #dc3545;
      }

      .badge-view {
        background: #ffc107;
        color: #212529;
      }

      .warning {
        background: #fff3cd;
        border: 1px solid #ffc107;
        padding: 10px;
        margin: 10px 0;
        border-radius: 3px;
        color: #856404;
      }
    </style>
  </head>

  <body>
    <!-- Header -->
    <div class="header">
      <h1>{{ $documentation['project_info']['name'] ?? 'Laravel Project' }}</h1>
      <p>{{ $documentation['project_info']['description'] ?? 'Laravel Application Documentation' }}</p>
      <p><strong>Generated:</strong> {{ $documentation['project_info']['generated_at'] ?? date('Y-m-d H:i:s') }}</p>
    </div>

    <!-- Project Info -->
    <div class="project-info">
      <h2>Project Information</h2>
      <table class="stats-table">
        <tr>
          <td><strong>Project Name</strong></td>
          <td>{{ $documentation['project_info']['name'] ?? 'N/A' }}</td>
        </tr>
        <tr>
          <td><strong>Description</strong></td>
          <td>{{ $documentation['project_info']['description'] ?? 'N/A' }}</td>
        </tr>
        @if (isset($documentation['project_info']['laravel_version']))
          <tr>
            <td><strong>Laravel Version</strong></td>
            <td>{{ $documentation['project_info']['laravel_version'] }}</td>
          </tr>
        @endif
        @if (isset($documentation['project_info']['php_version']))
          <tr>
            <td><strong>PHP Version</strong></td>
            <td>{{ $documentation['project_info']['php_version'] }}</td>
          </tr>
        @endif
      </table>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-box">
      <h2>Project Statistics</h2>
      <table class="stats-table">
        <tr>
          <td><span class="badge badge-controller">CONTROLLERS</span></td>
          <td>{{ count($documentation['controllers'] ?? []) }} files</td>
        </tr>
        <tr>
          <td><span class="badge badge-model">MODELS</span></td>
          <td>{{ count($documentation['models'] ?? []) }} files</td>
        </tr>
        <tr>
          <td><span class="badge badge-migration">MIGRATIONS</span></td>
          <td>{{ count($documentation['migrations'] ?? []) }} files</td>
        </tr>
        <tr>
          <td><span class="badge badge-view">VIEWS</span></td>
          <td>{{ count($documentation['views'] ?? []) }} files</td>
        </tr>
      </table>
    </div>

    <!-- Table of Contents -->
    <div class="toc">
      <h2>Table of Contents</h2>
      <ul>
        <li>Controllers ({{ count($documentation['controllers'] ?? []) }})</li>
        <li>Models ({{ count($documentation['models'] ?? []) }})</li>
        <li>Migrations ({{ count($documentation['migrations'] ?? []) }})</li>
        <li>Views ({{ count($documentation['views'] ?? []) }})</li>
      </ul>
    </div>

    <div class="page-break"></div>

    <!-- Controllers Documentation -->
    <h2>Controllers Documentation</h2>
    @if (!empty($documentation['controllers']))
      @foreach ($documentation['controllers'] as $controller)
        <h3>{{ $controller['name'] ?? 'Unknown' }}</h3>
        <div class="file-header">{{ $controller['path'] ?? 'Unknown path' }}
          ({{ number_format(($controller['size'] ?? 0) / 1024, 2) }} KB)</div>

        @if (!empty($controller['methods']))
          <div class="method-list">
            <strong>Methods:</strong> {{ implode(', ', $controller['methods']) }}
          </div>
        @endif

        <div class="code-block">{!! $controller['content'] ?? 'No content available' !!}</div>
      @endforeach
    @else
      <div class="warning">No controllers found in the project.</div>
    @endif

    <div class="page-break"></div>

    <!-- Models Documentation -->
    <h2>Models Documentation</h2>
    @if (!empty($documentation['models']))
      @foreach ($documentation['models'] as $model)
        <h3>{{ $model['name'] ?? 'Unknown' }}</h3>
        <div class="file-header">{{ $model['path'] ?? 'Unknown path' }}
          ({{ number_format(($model['size'] ?? 0) / 1024, 2) }} KB)</div>

        @if (!empty($model['properties']))
          <div class="property-list">
            @if (isset($model['properties']['table']))
              <strong>Table:</strong> {{ $model['properties']['table'] }}<br>
            @endif
            @if (isset($model['properties']['fillable']) && !empty($model['properties']['fillable']))
              <strong>Fillable:</strong> {{ implode(', ', $model['properties']['fillable']) }}
            @endif
          </div>
        @endif

        <div class="code-block">{!! $model['content'] ?? 'No content available' !!}</div>
      @endforeach
    @else
      <div class="warning">No models found in the project.</div>
    @endif

    <div class="page-break"></div>

    <!-- Migrations Documentation -->
    <h2>Migrations Documentation</h2>
    @if (!empty($documentation['migrations']))
      @foreach ($documentation['migrations'] as $migration)
        <h3>{{ $migration['name'] ?? 'Unknown' }}</h3>
        <div class="file-header">
          {{ $migration['filename'] ?? 'Unknown' }}
          ({{ number_format(($migration['size'] ?? 0) / 1024, 2) }} KB)
          - Created: {{ $migration['created_date'] ?? 'Unknown' }}
        </div>
        <div class="code-block">{!! $migration['content'] ?? 'No content available' !!}</div>
      @endforeach
    @else
      <div class="warning">No migrations found in the project.</div>
    @endif

    <div class="page-break"></div>

    <!-- Views Documentation -->
    <h2>Views Documentation</h2>
    @if (!empty($documentation['views']))
      @foreach ($documentation['views'] as $view)
        <h3>{{ $view['name'] ?? 'Unknown' }}</h3>
        <div class="file-header">
          {{ $view['path'] ?? 'Unknown path' }}
          ({{ number_format(($view['size'] ?? 0) / 1024, 2) }} KB)
          - Type: {{ $view['type'] ?? 'Unknown' }}
        </div>
        <div class="code-block">{!! $view['content'] ?? 'No content available' !!}</div>
      @endforeach
    @else
      <div class="warning">No views found in the project.</div>
    @endif

    <!-- Footer -->
    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #6c757d;">
      <p>Documentation generated by Laravel Artisan Command</p>
      <p>{{ $documentation['project_info']['generated_at'] ?? date('Y-m-d H:i:s') }}</p>
    </div>
  </body>

</html>
