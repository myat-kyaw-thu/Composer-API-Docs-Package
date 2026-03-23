<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>API Preview</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;font-size:13px;background:#0d1117;color:#e6edf3;min-height:100vh;display:flex;flex-direction:column}
a{color:inherit;text-decoration:none}
.topbar{display:flex;align-items:center;gap:12px;padding:10px 16px;background:#161b22;border-bottom:1px solid #30363d;position:sticky;top:0;z-index:100;flex-shrink:0}
.topbar a{font-size:12px;color:#8b949e;padding:5px 10px;border:1px solid #30363d;border-radius:6px}
.topbar a:hover{border-color:#8b949e;color:#e6edf3}
.layout{display:flex;flex:1;align-items:flex-start}
.sidebar{width:260px;flex-shrink:0;background:#161b22;border-right:1px solid #30363d;position:sticky;top:41px;height:calc(100vh - 41px);display:flex;flex-direction:column;overflow:hidden}
.sidebar-top{padding:12px;border-bottom:1px solid #30363d;flex-shrink:0}
.sidebar-top input{width:100%;background:#0d1117;border:1px solid #30363d;border-radius:6px;padding:7px 10px;color:#e6edf3;font-size:12px;outline:none}
.sidebar-top input:focus{border-color:#388bfd}
.sidebar-routes{flex:1;overflow-y:auto}
.route-link{display:block;padding:10px 14px;border-bottom:1px solid #21262d;cursor:pointer;transition:background .15s}
.route-link:hover{background:#1c2128}
.route-link.active{background:#1c2128;border-left:2px solid #388bfd;padding-left:12px}
.route-link .uri{font-family:monospace;font-size:11px;color:#8b949e;margin-top:3px;word-break:break-all}
.badge{display:inline-block;font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px;font-family:monospace;color:#fff}
.GET{background:#1f6feb}.POST{background:#238636}.PUT,.PATCH{background:#9e6a03}.DELETE{background:#da3633}
.main{flex:1;min-width:0;padding:24px;display:flex;flex-direction:column;gap:20px}
.card{background:#161b22;border:1px solid #30363d;border-radius:8px;overflow:hidden}
.card-header{padding:10px 16px;background:#1c2128;border-bottom:1px solid #30363d;display:flex;justify-content:space-between;align-items:center;font-size:11px;font-weight:600;color:#8b949e;text-transform:uppercase;letter-spacing:.5px}
.card-body{padding:16px}
.endpoint{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.endpoint .url{font-family:monospace;font-size:14px;color:#e6edf3;word-break:break-all}
.meta-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px}
.meta-item .label{font-size:10px;font-weight:600;color:#8b949e;text-transform:uppercase;margin-bottom:4px}
.meta-item .value{font-family:monospace;font-size:12px;color:#e6edf3;word-break:break-all}
.auth-badge{display:inline-flex;align-items:center;gap:5px;font-size:11px;padding:3px 8px;border-radius:12px;font-weight:500}
.auth-required{background:#3d1f1f;color:#f85149;border:1px solid #6e2020}
.auth-public{background:#1a2f1a;color:#3fb950;border:1px solid #1f4a1f}
table{width:100%;border-collapse:collapse;font-size:12px}
th{text-align:left;padding:8px 10px;background:#1c2128;color:#8b949e;font-weight:600;font-size:11px;text-transform:uppercase;border-bottom:1px solid #30363d}
td{padding:8px 10px;border-bottom:1px solid #21262d;vertical-align:top}
tr:last-child td{border-bottom:none}
.field-name{font-family:monospace;color:#79c0ff}
.rule-tag{display:inline-block;font-size:10px;padding:1px 6px;border-radius:3px;margin:1px;font-family:monospace}
.rule-required{background:#3d1f1f;color:#f85149}
.rule-normal{background:#1c2128;color:#8b949e;border:1px solid #30363d}
.code-wrap{position:relative;background:#0d1117;border:1px solid #30363d;border-radius:6px}
.code-wrap pre{padding:14px;padding-top:38px;font-family:'SFMono-Regular',Consolas,'Liberation Mono',Menlo,monospace;font-size:12px;line-height:1.6;color:#e6edf3;overflow-x:auto;white-space:pre;word-break:normal}
.copy-btn{position:absolute;top:8px;right:8px;background:#21262d;border:1px solid #30363d;color:#8b949e;padding:3px 10px;border-radius:5px;font-size:11px;cursor:pointer}
.copy-btn:hover{background:#30363d;color:#e6edf3}
.copy-btn.copied{color:#3fb950;border-color:#1f4a1f}
.hdr-copy{background:#21262d;border:1px solid #30363d;color:#8b949e;padding:3px 10px;border-radius:5px;font-size:11px;cursor:pointer}
.hdr-copy:hover{background:#30363d;color:#e6edf3}
.hdr-copy.copied{color:#3fb950;border-color:#1f4a1f}
.status{display:inline-block;font-size:11px;font-weight:700;padding:2px 8px;border-radius:4px;font-family:monospace}
.s2xx{background:#1a2f1a;color:#3fb950;border:1px solid #1f4a1f}
.s4xx{background:#3d1f1f;color:#f85149;border:1px solid #6e2020}
.s5xx{background:#2d1f0e;color:#e3b341;border:1px solid #5a3e1b}
.empty{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:80px 40px;color:#8b949e;gap:8px}
.empty-icon{font-size:40px;opacity:.4}
::-webkit-scrollbar{width:6px;height:6px}
::-webkit-scrollbar-track{background:#0d1117}
::-webkit-scrollbar-thumb{background:#30363d;border-radius:3px}
</style>
</head>
<body>
<div class="topbar">
    <a href="{{ route('api-visibility.docs') }}">← Docs</a>
    <span style="color:#8b949e;font-size:12px">API Preview</span>
</div>
<div class="layout">
    <div class="sidebar">
        <div class="sidebar-top">
            <input type="text" id="search" placeholder="Search routes…" autocomplete="off">
        </div>
        <div class="sidebar-routes">
            @foreach($routes as $route)
                <a href="{{ route('api-visibility.preview.show', ['routeName' => $route['name']]) }}"
                   class="route-link {{ isset($selectedRoute) && $selectedRoute === $route['name'] ? 'active' : '' }}"
                   data-uri="{{ $route['uri'] }}">
                    @foreach($route['methods'] as $m)
                        @if($m !== 'HEAD')
                            <span class="badge {{ $m }}">{{ $m }}</span>
                        @endif
                    @endforeach
                    <div class="uri">{{ $route['uri'] }}</div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="main">
        @if(!isset($selectedRoute) || !$analysis)
            <div class="empty">
                <div class="empty-icon">⚡</div>
                <div>Select a route to inspect</div>
            </div>
        @else
            @php
                $a      = $analysis;
                $method = $a['method'];
                $isBody = in_array($method, ['POST', 'PUT', 'PATCH']);
                $fullUrl = url($a['uri']);
            @endphp

            {{-- Endpoint --}}
            <div class="card">
                <div class="card-header">
                    Endpoint
                    <button class="hdr-copy" data-copy="{{ $fullUrl }}">Copy URL</button>
                </div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
                    <div class="endpoint">
                        <span class="badge {{ $method }}">{{ $method }}</span>
                        <span class="url">{{ $fullUrl }}</span>
                    </div>
                    <div class="meta-grid">
                        <div class="meta-item">
                            <div class="label">Controller</div>
                            <div class="value">{{ $a['controller'] ?? '—' }}</div>
                        </div>
                        @if(!empty($a['middleware']))
                        <div class="meta-item">
                            <div class="label">Middleware</div>
                            <div class="value">{{ implode(', ', $a['middleware']) }}</div>
                        </div>
                        @endif
                        <div class="meta-item">
                            <div class="label">Auth</div>
                            <div class="value">
                                @if($a['requires_auth'])
                                    <span class="auth-badge auth-required">🔒 Required</span>
                                @else
                                    <span class="auth-badge auth-public">✓ Public</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- URI Parameters --}}
            @if(!empty($a['uri_params']))
            <div class="card">
                <div class="card-header">URI Parameters</div>
                <div class="card-body" style="padding:0">
                    <table>
                        <thead><tr><th>Name</th><th>Required</th></tr></thead>
                        <tbody>
                            @foreach($a['uri_params'] as $p)
                            <tr>
                                <td><span class="field-name">{{ $p['name'] }}</span></td>
                                <td>
                                    @if($p['required'])
                                        <span class="rule-tag rule-required">required</span>
                                    @else
                                        <span class="rule-tag rule-normal">optional</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Request Body / Query Params --}}
            @if(!empty($a['validation_rules']))
            <div class="card">
                <div class="card-header">{{ $isBody ? 'Request Body' : 'Query Parameters' }}</div>
                <div class="card-body" style="padding:0">
                    <table>
                        <thead><tr><th>Field</th><th>Rules</th></tr></thead>
                        <tbody>
                            @foreach($a['validation_rules'] as $field => $rules)
                            @php
                                $ruleList = is_array($rules) ? $rules : explode('|', $rules);
                                $ruleList = array_map(fn($r) => is_object($r) ? class_basename(get_class($r)) : (string)$r, $ruleList);
                            @endphp
                            <tr>
                                <td><span class="field-name">{{ $field }}</span></td>
                                <td>
                                    @foreach($ruleList as $rule)
                                        <span class="rule-tag {{ strtolower($rule) === 'required' ? 'rule-required' : 'rule-normal' }}">{{ $rule }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Example Payload --}}
            @if(!empty($a['example_payload']))
            <div class="card">
                <div class="card-header">Example {{ $isBody ? 'Request Payload' : 'Query Parameters' }}</div>
                <div class="card-body">
                    <div class="code-wrap">
                        <button class="copy-btn" data-copy-target>Copy</button>
                        <pre>{{ $a['example_payload'] }}</pre>
                    </div>
                </div>
            </div>
            @endif

            {{-- Success Response --}}
            @if(!empty($a['success_response']))
            <div class="card">
                <div class="card-header">
                    Success Response
                    <span class="status s2xx">{{ $a['success_response']['status'] }}</span>
                </div>
                <div class="card-body">
                    <div class="code-wrap">
                        <button class="copy-btn" data-copy-target>Copy</button>
                        <pre>{{ $a['success_response']['body'] }}</pre>
                    </div>
                </div>
            </div>
            @endif

            {{-- Error Responses --}}
            @if(!empty($a['error_responses']))
                @foreach($a['error_responses'] as $err)
                <div class="card">
                    <div class="card-header">
                        Error Response
                        <span class="status {{ $err['status'] >= 500 ? 's5xx' : 's4xx' }}">{{ $err['status'] }}</span>
                    </div>
                    <div class="card-body">
                        <div class="code-wrap">
                            <button class="copy-btn" data-copy-target>Copy</button>
                            <pre>{{ $err['body'] }}</pre>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            {{-- API Resources --}}
            @if(!empty($a['resources']))
            <div class="card">
                <div class="card-header">API Resources Used</div>
                <div class="card-body">
                    @foreach($a['resources'] as $res)
                        <span class="rule-tag rule-normal">{{ $res }}</span>
                    @endforeach
                </div>
            </div>
            @endif

        @endif
    </div>
</div>

<script>
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.copy-btn, .hdr-copy');
    if (!btn) return;

    let text = '';
    if (btn.hasAttribute('data-copy')) {
        text = btn.getAttribute('data-copy');
    } else if (btn.hasAttribute('data-copy-target')) {
        const pre = btn.parentElement.querySelector('pre');
        if (pre) text = pre.textContent;
    }
    if (!text) return;

    navigator.clipboard.writeText(text.trim()).catch(() => {
        const ta = document.createElement('textarea');
        ta.value = text.trim();
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
    });

    const orig = btn.textContent;
    btn.textContent = 'Copied!';
    btn.classList.add('copied');
    setTimeout(() => { btn.textContent = orig; btn.classList.remove('copied'); }, 1500);
});

document.getElementById('search').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.route-link').forEach(el => {
        el.style.display = el.dataset.uri.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
</body>
</html>
