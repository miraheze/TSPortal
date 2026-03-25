<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Investigation #{{ $investigation->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #212529;
        }
        h1, h2, h3 {
            margin-bottom: 5px;
        }
        .alert {
            border: 1px solid #dc3545;
            background-color: #f8d7da;
            color: #721c24;
            padding: 8px;
            margin-bottom: 10px;
            text-align: center;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -8px;
        }
        .col-lg-8 {
            flex: 0 0 66.6667%;
            max-width: 66.6667%;
            padding: 0 8px;
        }
        .col-lg-4 {
            flex: 0 0 33.3333%;
            max-width: 33.3333%;
            padding: 0 8px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .card-header {
            background-color: #e9f7ff;
            color: #0d6efd;
            font-weight: bold;
            text-align: center;
            padding: 5px;
        }
        .card-body {
            padding: 8px;
        }
        .box {
            border: 1px solid #ccc;
            padding: 6px;
            margin-bottom: 6px;
        }
        .badge-success {
            background-color: #198754;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
        }
        a {
            color: #0d6efd;
            text-decoration: none;
        }
    </style>
</head>
<body>

@if($investigation->openAppeal())
    <div class="alert">
        <strong>{{ __('investigation-open-appeal') }} 
            <a href="{{ url('/appeal/' . $investigation->openAppeal()) }}">#{{ $investigation->openAppeal() }}</a>.
        </strong>
    </div>
@endif

<h1>{{ __('investigation-header') }} (#{{ $investigation->id }})</h1>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">{{ __('core') }}</div>
            <div class="card-body">
                <p><strong>{{ __('investigation-about') }}:</strong> 
                    {{ $investigation->type ? __('investigation-topic-' . $investigation->type) : __('empty') }}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">{{ __('evidence') }}</div>
            <div class="card-body">
                <div class="box">{{ $investigation->text ?? __('empty') }}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">{{ __('recommendation') }}</div>
            <div class="card-body">
                <div class="box"><strong>{{ __('recommendation-is') }}:</strong> 
                    {{ $investigation->recommendation ? __('recommendation-' . $investigation->recommendation) : __('empty') }}
                </div>
                <div class="box"><strong>{{ __('recommendation-reason') }}:</strong> 
                    {{ $investigation->explanation ?? __('empty') }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">{{ __('information') }}</div>
            <div class="card-body">
                <p><strong>{{ __('assigned') }}:</strong> 
                    {{ $investigation->assigned?->username ?? '[Unassigned]' }}
                </p>
                <p><strong>{{ __('status') }}:</strong>
                    @if($investigation->closed)
                        <span class="badge-danger">{{ __('status-closed') }}</span>
                    @else
                        <span class="badge-success">{{ __('status-open') }}</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">{{ __('subject') }}</div>
            <div class="card-body">
                <p><a href="{{ url('/user/' . $investigation->subject->id) }}">{{ $investigation->subject->username }}</a></p>
            </div>
        </div>
    </div>
</div>

@if($investigation->events()->exists())
    <div class="card">
        <div class="card-header">{{ __('history') }}</div>
        <div class="card-body">
            @foreach($investigation->events as $event)
                <div class="box">
                    {{ $event->created }} — {{ $event->action }}
                    @if($event->comment)
                        <br>{{ $event->comment }}
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endif

@if($investigation->reports()->exists())
    <div class="card">
        <div class="card-header">{{ __('reports') }}</div>
        <div class="card-body">
            @foreach($investigation->reports as $report)
                <div class="box">#{{ $report->id }} — {{ $report->text }}</div>
            @endforeach
        </div>
    </div>
@endif

@if($investigation->appeals()->exists())
    <div class="card">
        <div class="card-header">{{ __('appeals') }}</div>
        <div class="card-body">
            @foreach($investigation->appeals as $appeal)
                <div class="box">#{{ $appeal->id }} — {{ $appeal->text }}</div>
            @endforeach
        </div>
    </div>
@endif

</body>
</html>
