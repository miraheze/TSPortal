<x-layout>
	<x-slot name="pgname">
		{{ __('reports') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('open-reports') }}</h3>
		<div class="card shadow">
			<div class="card-body">
				<div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
					<table class="table table-striped my-0 text-center align-middle" id="dataTable">
						<thead>
						<tr>
							<th style="width: 33%;">{{ __('id') }}</th>
							<th style="width: 33%;">{{ __('topic') }}</th>
							<th style="width: 33%;">{{ __('report-age') }}</th>
						</tr>
						</thead>
						<tbody>
						@foreach ( $reports as $report )
							<tr>
								<td><a class="nav-link" href="/report/{{ $report->id }}">{{ $report->id }}</a></td>
								<td>{{ ucfirst(__('report-topic-' . $report->type)) }}</td>
								<td>{{ $report->created->diffForHumans() }}</td>
							</tr>
						@endforeach
						</tbody>
						<tfoot>
						<tr>
							<th style="width: 33%;">{{ __('id') }}</th>
							<th style="width: 33%;">{{ __('topic') }}</th>
							<th style="width: 33%;">{{ __('report-age') }}</th>
						</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</x-slot>
	<x-slot name="scripts"></x-slot>
</x-layout>
