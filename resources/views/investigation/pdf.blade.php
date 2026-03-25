<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Investigation #{{ $investigation->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #212529;
            margin: 20px;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 10px;
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }
        h2 {
            font-size: 14px;
            margin-bottom: 5px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 2px;
            color: #0d6efd;
        }
        h3 {
            font-size: 12px;
            margin-bottom: 3px;
            color: #198754;
        }
        .section {
            margin-bottom: 15px;
        }
        .box {
            border: 1px solid #ccc;
            padding: 6px 8px;
            margin-bottom: 6px;
            border-radius: 4px;
			page-break-inside: avoid;
			break-inside: avoid;
        }
        .alert {
            border: 1px solid #dc3545;
            background-color: #f8d7da;
            color: #721c24;
            padding: 8px;
            margin-bottom: 15px;
            text-align: center;
            border-radius: 4px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        td {
            padding: 4px 6px;
            vertical-align: top;
        }
        td.label {
            font-weight: bold;
            width: 120px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            color: #fff;
        }
        .badge-success { background-color: #198754; }
        .badge-danger { background-color: #dc3545; }
        a {
            color: #0d6efd;
            text-decoration: none;
        }
        hr {
            border: 0;
            border-top: 1px solid #ccc;
            margin: 10px 0;
        }
    </style>
</head>
<body>

@if($investigation->openAppeal())
    <div class="alert">
        {{ __('investigation-open-appeal') }} 
        <a href="{{ url('/appeal/' . $investigation->openAppeal()) }}">#{{ $investigation->openAppeal() }}</a>
    </div>
@endif

<h1>{{ __('investigation-header') }} (#{{ $investigation->id }})</h1>

<div class="section">
    <h2>{{ __('core') }}</h2>
    <table>
        <tr>
            <td class="label">{{ __('assigned') }}:</td>
            <td>{{ $investigation->assigned?->username ?? '[Unassigned]' }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('status') }}:</td>
            <td>
                @if($investigation->closed)
                    <span class="badge badge-danger">{{ __('status-closed') }}</span>
                @else
                    <span class="badge badge-success">{{ __('status-open') }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">{{ __('investigation-about') }}:</td>
            <td>{{ $investigation->type ? __('investigation-topic-' . $investigation->type) : __('empty') }}</td>
        </tr>
        <tr>
            <td class="label">{{ __('subject') }}:</td>
            <td><a href="{{ url('/user/' . $investigation->subject->id) }}">{{ $investigation->subject->username }}</a></td>
        </tr>
        <tr>
            <td class="label">{{ __('created') }}:</td>
            <td>{{ $investigation->created }}</td>
        </tr>
    </table>
</div>

<div class="section">
    <h2>{{ __('evidence') }}</h2>
    <div class="box">{{ $investigation->text ?? __('empty') }}</div>
</div>

<div class="section">
    <h2>{{ __('recommendation') }}</h2>
    <div class="box">
        <strong>{{ __('recommendation-is') }}:</strong> {{ $investigation->recommendation ? __('recommendation-' . $investigation->recommendation) : __('empty') }}
    </div>
    <div class="box">
        <strong>{{ __('recommendation-reason') }}:</strong> {{ $investigation->explanation ?? __('empty') }}
    </div>
</div>

@if($investigation->reports()->exists())
    <div class="section">
        <h2>{{ __('reports') }}</h2>
        @foreach($investigation->reports as $report)
            <div class="box">#{{ $report->id }} — {{ $report->text }}</div>
        @endforeach
    </div>
@endif

@if($investigation->events()->exists())
    <div class="section">
        <h2>{{ __('history') }}</h2>
        @foreach($investigation->events as $event)
            <div class="box">
                {{ $event->created }} — {{ __( 'events-' . $event->action . '-desc', [ 'comment' => '' ] ) }}
                @if($event->comment)
                    <br>{{ $event->comment }}
                @endif
            </div>
        @endforeach
    </div>
@endif

@if($investigation->appeals()->exists())
    <div class="section">
        <h2>{{ __('appeals') }}</h2>
        @foreach($investigation->appeals as $appeal)
            <div class="box">#{{ $appeal->id }} — {{ $appeal->text }}</div>
        @endforeach
    </div>
@endif

</body>
</html>
