<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - API Visibility</title>
    <style>
        :root {
            --primary: #3a86ff;
            --danger: #ef476f;
            --warning: #ffbe0b;
            --success: #06d6a0;
            --dark: #212529;
            --gray: #6c757d;
            --gray-light: #e9ecef;
            --border-radius: 6px;
            --font-main: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            --font-mono: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            --shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-main);
            line-height: 1.6;
            color: var(--dark);
            background-color: #f8f9fa;
            padding: 0;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
            width: 100%;
            flex: 1;
        }

        .error-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .error-header {
            background-color: var(--danger);
            color: white;
            padding: 20px;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .error-header svg {
            margin-right: 10px;
        }

        .error-body {
            padding: 20px;
        }

        .error-message {
            font-size: 16px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-light);
        }

        .suggestions {
            margin-bottom: 20px;
        }

        .suggestions h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: var(--dark);
        }

        .suggestions ul {
            list-style-type: none;
            padding-left: 0;
        }

        .suggestions li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .suggestions li:before {
            content: "→";
            position: absolute;
            left: 0;
            color: var(--primary);
            font-weight: bold;
        }

        .docs-link {
            display: inline-block;
            margin-top: 10px;
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
        }

        .docs-link:hover {
            text-decoration: underline;
        }

        .error-details {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-light);
        }

        .error-details h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: var(--dark);
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .error-details h3 svg {
            margin-right: 5px;
            transition: transform 0.2s;
        }

        .error-details h3.collapsed svg {
            transform: rotate(-90deg);
        }

        .error-details-content {
            background-color: var(--gray-light);
            border-radius: var(--border-radius);
            padding: 15px;
            font-family: var(--font-mono);
            font-size: 12px;
            white-space: pre-wrap;
            overflow-x: auto;
            display: none;
        }

        .error-details-content.visible {
            display: block;
        }

        .error-details-item {
            margin-bottom: 10px;
        }

        .error-details-label {
            font-weight: bold;
            color: var(--gray);
            margin-bottom: 3px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            text-decoration: none;
            margin-top: 20px;
            font-size: 14px;
        }

        .back-link svg {
            margin-right: 5px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .validation-errors {
            margin-top: 20px;
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            border-radius: var(--border-radius);
            padding: 15px;
        }

        .validation-errors h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #856404;
        }

        .validation-error {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ffeeba;
        }

        .validation-error:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .validation-field {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .validation-message {
            font-size: 13px;
        }

        .actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: var(--border-radius);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #2667cc;
        }

        .btn-outline {
            background-color: transparent;
            color: var(--gray);
            border: 1px solid var(--gray-light);
        }

        .btn-outline:hover {
            background-color: var(--gray-light);
        }

        .btn svg {
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-card">
            <div class="error-header">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                Error {{ $error['status'] }}
            </div>
            <div class="error-body">
                <div class="error-message">
                    {{ $error['message'] }}
                </div>

                @if(!empty($error['suggestions']))
                    <div class="suggestions">
                        <h3>Suggestions to fix this issue:</h3>
                        <ul>
                            @foreach($error['suggestions'] as $suggestion)
                                <li>{{ $suggestion }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(isset($error['errors']) && is_array($error['errors']))
                    <div class="validation-errors">
                        <h3>Validation Errors:</h3>
                        @foreach($error['errors'] as $field => $messages)
                            <div class="validation-error">
                                <div class="validation-field">{{ $field }}:</div>
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
                        Read the documentation for more information →
                    </a>
                @endif

                @if(isset($error['details']) && $error['details'])
                    <div class="error-details">
                        <h3 id="toggle-details">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="toggle-icon">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                            Technical Details
                        </h3>
                        <div class="error-details-content" id="error-details">
                            @if(isset($error['details']['exception']))
                                <div class="error-details-item">
                                    <div class="error-details-label">Exception:</div>
                                    <div>{{ $error['details']['exception'] }}</div>
                                </div>
                            @endif

                            @if(isset($error['details']['file']))
                                <div class="error-details-item">
                                    <div class="error-details-label">File:</div>
                                    <div>{{ $error['details']['file'] }} (line {{ $error['details']['line'] }})</div>
                                </div>
                            @endif

                            @if(isset($error['details']['trace']))
                                <div class="error-details-item">
                                    <div class="error-details-label">Stack Trace:</div>
                                    <div>{{ $error['details']['trace'] }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="actions">
                    <a href="{{ url()->previous() }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                        Go Back
                    </a>
                    <a href="{{ route('api-visibility.docs') }}" class="btn btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            const toggleDetails = document.getElementById('toggle-details');
            const errorDetails = document.getElementById('error-details');

            if (toggleDetails && errorDetails) {
                toggleDetails.addEventListener('click', function() {
                    errorDetails.classList.toggle('visible');
                    toggleDetails.classList.toggle('collapsed');
                });
            }
        });
    </script>
</body>
</html>
