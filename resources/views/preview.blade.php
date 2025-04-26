<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Response Preview</title>
    <style>
        :root {
            --primary: #3b82f6;
            --primary-light: #dbeafe;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --dark: #1f2937;
            --gray: #9ca3af;
            --light: #f9fafb;
            --white: #ffffff;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --radius: 6px;
            --code-bg: #f8fafc;
            --code-color: #334155;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background-color: var(--light);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background-color: var(--white);
            padding: 1.5rem 2rem;
            box-shadow: var(--shadow);
            z-index: 10;
        }

        h1 {
            font-size: 1.5rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            margin-top: 0.5rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #2563eb;
        }

        .back-icon {
            width: 16px;
            height: 16px;
        }

        .main-container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .sidebar {
            width: 300px;
            background-color: var(--white);
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark);
        }

        .search-container {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .search-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: var(--radius);
            font-size: 0.85rem;
            background-color: var(--white);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px var(--primary-light);
        }

        .route-list {
            list-style: none;
            padding: 0;
            margin: 0;
            overflow-y: auto;
            flex: 1;
        }

        .route-item {
            border-bottom: 1px solid #e5e7eb;
        }

        .route-link {
            text-decoration: none;
            color: var(--dark);
            display: flex;
            padding: 0.75rem 1rem;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.2s;
        }

        .route-link:hover {
            background-color: #f3f4f6;
        }

        .route-link.active {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 500;
            border-left: 3px solid var(--primary);
        }

        .route-uri {
            font-size: 0.85rem;
            word-break: break-all;
        }

        .method {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            color: white;
            min-width: 50px;
        }

        .get { background-color: var(--info); }
        .post { background-color: var(--success); }
        .put, .patch { background-color: var(--warning); }
        .delete { background-color: var(--danger); }

        .content {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background-color: var(--white);
        }

        .content-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background-color: var(--white);
        }

        .route-details {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .route-path {
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-size: 1rem;
            font-weight: 500;
        }

        .content-body {
            padding: 1.5rem;
            overflow-y: auto;
            flex: 1;
        }

        .response-container {
            border: 1px solid #e5e7eb;
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .response-header {
            padding: 0.75rem 1rem;
            background-color: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .response-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark);
        }

        .status {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
        }

        .status-2xx { background-color: var(--success); }
        .status-3xx { background-color: var(--warning); }
        .status-4xx, .status-5xx { background-color: var(--danger); }

        .headers-container {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .headers-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: var(--dark);
        }

        .header-item {
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-size: 0.8rem;
            margin-bottom: 0.5rem;
            padding: 0.25rem 0;
            display: flex;
        }

        .header-name {
            font-weight: 600;
            margin-right: 0.5rem;
            color: var(--primary);
        }

        .header-value {
            color: var(--code-color);
        }

        .response {
            background-color: var(--code-bg);
            padding: 1rem;
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-size: 0.85rem;
            line-height: 1.5;
            white-space: pre-wrap;
            overflow-x: auto;
            color: var(--code-color);
            border-radius: 0 0 var(--radius) var(--radius);
        }

        .error {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border-left: 4px solid var(--danger);
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            color: var(--gray);
            text-align: center;
            height: 100%;
        }

        .empty-icon {
            width: 48px;
            height: 48px;
            margin-bottom: 1rem;
            color: var(--gray);
        }

        .empty-title {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .empty-description {
            font-size: 0.9rem;
            max-width: 400px;
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .main-container {
                position: relative;
            }

            .sidebar {
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 20;
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .mobile-toggle {
                display: block;
            }

            .header {
                padding: 1rem;
            }

            .content-header, .content-body {
                padding: 1rem;
            }
        }

        /* JSON syntax highlighting */
        .json-key {
            color: #8839ef;
        }

        .json-string {
            color: #40a02b;
        }

        .json-number {
            color: #fe640b;
        }

        .json-boolean {
            color: #1e66f5;
        }

        .json-null {
            color: #d20f39;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>API Response Preview</h1>
        <p class="subtitle">Test and visualize API responses</p>
        <a href="{{ route('api-visibility.docs') }}" class="back-link">
            <svg class="back-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Documentation
        </a>
    </div>

    <div class="main-container">
        <button class="mobile-toggle" id="sidebarToggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <span class="sidebar-title">Available Routes</span>
            </div>
            <div class="search-container">
                <input type="text" class="search-input" id="routeSearch" placeholder="Search routes...">
            </div>
            <ul class="route-list" id="routeList">
                @foreach($routes as $route)
                    <li class="route-item" data-uri="{{ $route['uri'] }}" data-methods="{{ implode(',', array_filter($route['methods'], function($m) { return $m !== 'HEAD'; })) }}">
                        <a href="{{ route('api-visibility.preview.show', ['routeName' => $route['name']]) }}"
                           class="route-link {{ isset($selectedRoute) && $selectedRoute === $route['name'] ? 'active' : '' }}">
                            @foreach($route['methods'] as $method)
                                @if($method !== 'HEAD')
                                    <span class="method {{ strtolower($method) }}">{{ $method }}</span>
                                @endif
                            @endforeach
                            <span class="route-uri">{{ $route['uri'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="content">
            @if(isset($selectedRoute))
                <div class="content-header">
                    <div class="route-details">
                        @if(isset($currentRoute))
                            @foreach($currentRoute['methods'] as $method)
                                @if($method !== 'HEAD')
                                    <span class="method {{ strtolower($method) }}">{{ $method }}</span>
                                @endif
                            @endforeach
                            <span class="route-path">{{ $currentRoute['uri'] }}</span>
                        @endif
                    </div>
                </div>

                <div class="content-body">
                    @if(isset($error))
                        <div class="error">
                            {{ $error }}
                        </div>
                    @endif

                    @if(isset($result))
                        <div class="response-container">
                            <div class="response-header">
                                <span class="response-title">Response</span>
                                @if(isset($result['status']))
                                    <span class="status status-{{ substr($result['status'], 0, 1) }}xx">
                                        Status: {{ $result['status'] }}
                                    </span>
                                @endif
                            </div>

                            @if(isset($result['headers']))
                                <div class="headers-container">
                                    <h3 class="headers-title">Headers</h3>
                                    @foreach($result['headers'] as $name => $values)
                                        <div class="header-item">
                                            <span class="header-name">{{ $name }}:</span>
                                            <span class="header-value">{{ implode(', ', $values) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if(isset($result['formatted']))
                                <div class="response" id="responseContent">{{ $result['formatted'] }}</div>
                            @elseif($result instanceof \Illuminate\Http\Response)
                                <div class="response" id="responseContent">{{ $result->getContent() }}</div>
                            @else
                                <div class="response" id="responseContent">{{ json_encode($result, JSON_PRETTY_PRINT) }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            @else
                <div class="empty-state">
                    <svg class="empty-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <h3 class="empty-title">No Route Selected</h3>
                    <p class="empty-description">Select a route from the sidebar to preview its response.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }

            // Route search functionality
            const routeSearch = document.getElementById('routeSearch');
            const routeItems = document.querySelectorAll('.route-item');

            if (routeSearch) {
                routeSearch.addEventListener('input', function() {
                    const searchValue = this.value.toLowerCase();

                    routeItems.forEach(item => {
                        const uri = item.getAttribute('data-uri').toLowerCase();
                        const methods = item.getAttribute('data-methods').toLowerCase();

                        if (uri.includes(searchValue) || methods.includes(searchValue)) {
                            item.classList.remove('hidden');
                        } else {
                            item.classList.add('hidden');
                        }
                    });
                });
            }

            // JSON syntax highlighting
            const responseContent = document.getElementById('responseContent');

            if (responseContent && responseContent.textContent.trim()) {
                try {
                    // Check if content is JSON
                    const content = responseContent.textContent;
                    JSON.parse(content); // Will throw if not valid JSON

                    // Apply syntax highlighting
                    const highlighted = content
                        .replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
                            let cls = 'json-number';
                            if (/^"/.test(match)) {
                                if (/:$/.test(match)) {
                                    cls = 'json-key';
                                } else {
                                    cls = 'json-string';
                                }
                            } else if (/true|false/.test(match)) {
                                cls = 'json-boolean';
                            } else if (/null/.test(match)) {
                                cls = 'json-null';
                            }
                            return '<span class="' + cls + '">' + match + '</span>';
                        });

                    responseContent.innerHTML = highlighted;
                } catch (e) {
                    // Not JSON or invalid JSON, leave as is
                }
            }
        });
    </script>
</body>
</html>
