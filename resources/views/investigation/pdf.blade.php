<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Investigation #{{ $investigation->id }}</title>
	<style>
		body {
			font-family: DejaVu Sans, sans-serif;
			font-size: 12px;
		}
		h1, h2 {
			margin-bottom: 5px;
		}
		.section {
			margin-bottom: 15px;
		}
		.box {
			border: 1px solid #ccc;
			padding: 8px;
			margin-bottom: 8px;
		}
	</style>
</head>
<body>

<h1>Investigation #{{ $investigation->id }}</h1>

<div class="section">
	<strong>Subject:</strong> {{ $investigation->subject->name }}<br>
	<strong>Assigned:</strong> {{ $investigation->assigned?->name }}<br>
	<strong>Type:</strong> {{ $investigation->type }}<br>
	<strong>Created:</strong> {{ $investigation->created }}
</div>

<div class="section">
	<h2>Details</h2>
	<div class="box">{{ $investigation->text }}</div>

	<h3>Recommendation</h3>
	<div class="box">{{ $investigation->recommendation }}</div>

	<h3>Explanation</h3>
	<div class="box">{{ $investigation->explanation }}</div>
</div>

<div class="section">
	<h2>Reports</h2>
	@foreach ( $investigation->reports as $report )
		<div class="box">
			#{{ $report->id }} — {{ $report->text }}
		</div>
	@endforeach
</div>

<div class="section">
	<h2>Events</h2>
	@foreach ( $investigation->events as $event )
		<div class="box">
			{{ $event->created }} — {{ $event->action }}
			@if ( $event->comment )
				<br>{{ $event->comment }}
			@endif
		</div>
	@endforeach
</div>

<div class="section">
	<h2>Appeals</h2>
	@foreach ( $investigation->appeals as $appeal )
		<div class="box">
			#{{ $appeal->id }} — {{ $appeal->text }}
		</div>
	@endforeach
</div>

</body>
</html>
