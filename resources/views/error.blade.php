<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - API Visibility</title>
    <style>
        :root {
            /* Graphite Color Palette */
            --graphite-50: #f8f9fa;
            --graphite-100: #f1f3f4;
            --graphite-200: #e8eaed;
            --graphite-300: #dadce0;
            --graphite-400: #bdc1c6;
            --graphite-500: #9aa0a6;
            --graphite-600: #80868b;
            --graphite-700: #5f6368;
            --graphite-800: #3c4043;
            --graphite-900: #202124;
            
            /* Semantic Colors */
            --surface: #ffffff;
            --surface-variant: var(--graphite-50);
            --surface-container: var(--graphite-100);
            --on-surface: var(--graphite-900);
            --on-surface-variant: var(--graphite-700);
            --outline: var(--graphite-300);
            --outline-variant: var(--graphite-200);
            
            /* Status Colors */
            --error: #d93025;
            --error-container: #fce8e6;
            --on-error: #ffffff;
            --on-error-container: #410e0b;
            --warning: #ea8600;
            --warning-container: #fef7e0;
            --on-warning-container: #3c2e00;
            --primary: var(--graphite-700);
            --primary-container: var(--graphite-200);
            --on-primary: #ffffff;
            
            /* Typography */
            --font-family: 'Google Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-mono: 'Google Sans Mono', 'Roboto Mono', Consolas, 'Liberation Mono', monospace;
            
            /* Spacing */
            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 16px;
            --space-lg: 24px;
            --space-xl: 32px;
            --space-2xl: 48px;
            
            /* Border Radius */
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(60, 64, 67, 0.3), 0 1px 3px 1px rgba(60, 64, 67, 0.15);
            --shadow-md: 0 1px 2px 0 rgba(60, 64, 67, 0.3), 0 2px 6px 2px rgba(60, 64, 67, 0.15);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            font-size: 14px;
            line-height: 1.5;
            color: var(--on-surface);
            background-color: var(--surface-variant);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--space-lg);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .error-container {
            width: 100%;
            max-width: 800px;
        }

        .error-card {
            background-color: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid var(--outline-variant);
        }

        /* Error Header */
        .error-header {
            background-color: var(--error);
            color: var(--on-error);
            padding: var(--space-xl);
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }

        .error-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }

        .error-title {
            font-size: 20px;
            font-weight: 500;
            letter-spacing: -0.25px;
        }

        /* Error Body */
        .error-body {
            padding: var(--space-xl);
        }

        .error-message {
            font-size: 16px;
            color: var(--on-surface);
            margin-bottom: var(--space-xl);
            padding-bottom: var(--space-xl);
            border-bottom: 1px solid var(--outline-variant);
            line-height: 1.6;
        }

        /* Suggestions */
        .suggestions {
            margin-bottom: var(--space-xl);
        }

        .suggestions-title {
            font-size: 16px;
            font-weight: 500;
            color: var(--on-surface);
            margin-bottom: var(--space-md);
        }

        .suggestions-list {
            list-style: none;
            padding: 0;
        }

        .suggestion-item {
            position: relative;
            padding-left: var(--space-xl);
            margin-bottom: var(--space-md);
            font-size: 14px;
            color: var(--on-surface-variant);
            line-height: 1.5;
        }

        .suggestion-item:last-child {
            margin-bottom: 0;
        }

        .suggestion-item::before {
            content: "→";
            position: absolute;
            left: 0;
            top: 0;
            color: var(--primary);
            font-weight: 600;
            font-size: 16px;
        }

        /* Validation Errors */
        .validation-errors {
            background-color: var(--warning-container);
            border: 1px solid var(--warning);
            border-radius: var(--radius-md);
            padding: var(--space-lg);
            margin-bottom: var(--space-xl);
        }

        .validation-errors-title {
            font-size: 16px;
            font-weight: 500;
            color: var(--on-warning-container);
            margin-bottom: var(--space-md);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }

        .validation-error {
            margin-bottom: var(--space-md);
            padding-bottom: var(--space-md);
            border-bottom: 1px solid rgba(234, 134, 0, 0.2);
        }

        .validation-error:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .validation-field {
            font-weight: 600;
            color: var(--on-warning-container);
            margin-bottom: var(--space-xs);
            font-size: 13px;
            font-family: var(--font-mono);
        }

        .validation-message {
            font-size: 13px;
            color: var(--on-warning-container);
            line-height: 1.4;
        }

        .validation-message ul {
            list-style: none;
            padding: 0;
        }

        .validation-message li {
            position: relative;
            padding-left: var(--space-lg);
            margin-bottom: var(--space-xs);
        }

        .validation-message li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: var(--warning);
        }

        /* Documentation Link */
        .docs-link {
            display: inline-flex;
            align-items: center;
            gap: var(--space-sm);
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: var(--space-xl);
            padding: var(--space-md) var(--space-lg);
            background-color: var(--primary-container);
            border-radius: var(--radius-md);
            border: 1px solid var(--outline-variant);
        }

        /* Technical Details */
        .error-details {
            margin-bottom: var(--space-xl);
        }

        .details-toggle {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            font-size: 16px;
            font-weight: 500;
            color: var(--on-surface);
            padding: var(--space-md);
            border-radius: var(--radius-md);
            width: 100%;
            text-align: left;
            background-color: var(--surface-container);
            border: 1px solid var(--outline-variant);
        }

        .details-toggle:active {
            background-color: var(--outline-variant);
        }

        .toggle-icon {
            width: 16px;
            height: 16px;
            transform: rotate(0deg);
            transition: transform 0.2s ease;
        }

        .details-toggle.collapsed .toggle-icon {
            transform: rotate(-90deg);
        }

        .details-content {
            display: none;
            background-color: var(--surface-container);
            border: 1px solid var(--outline-variant);
            border-top: none;
            border-radius: 0 0 var(--radius-md) var(--radius-md);
            padding: var(--space-lg);
        }

        .details-content.visible {
            display: block;
        }

        .details-item {
            margin-bottom: var(--space-lg);
        }

        .details-item:last-child {
            margin-bottom: 0;
        }

        .details-label {
            font-weight: 600;
            color: var(--on-surface);
            margin-bottom: var(--space-xs);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .details-value {
            font-family: var(--font-mono);
            font-size: 12px;
            color: var(--on-surface-variant);
            background-color: var(--surface);
            padding: var(--space-md);
            border-radius: var(--radius-sm);
            border: 1px solid var(--outline-variant);
            white-space: pre-wrap;
            overflow-x: auto;
            line-height: 1.4;
        }

        /* Actions */
        .actions {
            display: flex;
            gap: var(--space-md);
            flex-wrap: wrap;
            padding-top: var(--space-xl);
            border-top: 1px solid var(--outline-variant);
        }

        .action-button {
            display: inline-flex;
            align-items: center;
            gap: var(--space-sm);
            padding: 12px var(--space-lg);
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid var(--outline);
            font-family: var(--font-family);
        }

        .action-primary {
            background-color: var(--primary);
            color: var(--on-primary);
            border-color: var(--primary);
        }

        .action-primary:active {
            background-color: var(--graphite-800);
        }

        .action-secondary {
            background-color: var(--surface);
            color: var(--on-surface-variant);
            border-color: var(--outline);
        }

        .action-secondary:active {
            background-color: var(--surface-container);
        }

        .action-icon {
            width: 16px;
            height: 16px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: var(--space-md);
                align-items: flex-start;
                padding-top: var(--space-xl);
            }

            .error-header {
                padding: var(--space-lg);
            }

            .error-body {
                padding: var(--space-lg);
            }

            .error-title {
                font-size: 18px;
            }

            .error-message {
                font-size: 15px;
            }

            .actions {
                flex-direction: column;
            }

            .action-button {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .error-header {
                padding: var(--space-md);
            }

            .error-body {
                padding: var(--space-md);
            }

            .suggestion-item {
                padding-left: var(--space-lg);
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-header">
                <svg class="error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <h1 class="error-title">Error {{ $error['status'] }}</h1>
            </div>

            <div class="error-body">
                <div class="error-message">
                    {{ $error['message'] }}
                </div>

                @if(!empty($error['suggestions']))
                    <div class="suggestions">
                        <h2 class="suggestions-title">How to fix this issue</h2>
                        <ul class="suggestions-list">
                            @foreach($error['suggestions'] as $suggestion)
                                <li class="suggestion-item">{{ $suggestion }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(isset($error['errors']) && is_array($error['errors']))
                    <div class="validation-errors">
                        <h2 class="validation-errors-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            Validation Errors
                        </h2>
                        @foreach($error['errors'] as $field => $messages)
                            <div class="validation-error">
                                <div class="validation-field">{{ $field }}</div>
                                <div class="validation-message">
                                    @if(is_array($messages))
                                        <ul>
                                            @foreach($messages as $message)
                                                <li>{{ $message }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        {{ $messages }}
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(isset($error['docs_link']))
                    <a href="{{ $error['docs_link'] }}" class="docs-link" target="_blank">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        Read Documentation
                    </a>
                @endif

                @if(isset($error['details']) && $error['details'])
                    <div class="error-details">
                        <button class="details-toggle collapsed" id="toggle-details">
                            <svg class="toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                            Technical Details
                        </button>
                        <div class="details-content" id="error-details">
                            @if(isset($error['details']['exception']))
                                <div class="details-item">
                                    <div class="details-label">Exception</div>
                                    <div class="details-value">{{ $error['details']['exception'] }}</div>
                                </div>
                            @endif

                            @if(isset($error['details']['file']))
                                <div class="details-item">
                                    <div class="details-label">File Location</div>
                                    <div class="details-value">{{ $error['details']['file'] }} (line {{ $error['details']['line'] }})</div>
                                </div>
                            @endif

                            @if(isset($error['details']['trace']))
                                <div class="details-item">
                                    <div class="details-label">Stack Trace</div>
                                    <div class="details-value">{{ $error['details']['trace'] }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="actions">
                    <a href="{{ url()->previous() }}" class="action-button action-primary">
                        <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                        </svg>
                        Go Back
                    </a>
                    <a href="{{ route('api-visibility.docs') }}" class="action-button action-secondary">
                        <svg class="action-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Back to Documentation
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggle-details');
            const detailsContent = document.getElementById('error-details');

            if (toggleButton && detailsContent) {
                toggleButton.addEventListener('click', function() {
                    detailsContent.classList.toggle('visible');
                    toggleButton.classList.toggle('collapsed');
                });
            }
        });
    </script>
</body>
</html>