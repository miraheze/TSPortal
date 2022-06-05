<x-layout>
	<x-slot name="pgname">
		{{ __('dpa') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('open-dpa') }}</h3>
		<div class="card shadow">
			<div class="card-body">
				<div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
					<table class="table table-striped my-0 text-center align-middle" id="dataTable">
						<thead>
						<tr>
							<th style="width: 33%;">{{ __('id') }}</th>
							<th style="width: 33%;">{{ __('username') }}</th>
							<th style="width: 33%;">{{ __('request-age') }}</th>
						</tr>
						</thead>
						<tbody>
						@foreach ( $dpas as $dpa )
							<tr>
								<td><a class="nav-link" href="/dpa/{{ $dpa->id }}">{{ $dpa->id }}</a></td>
								<td>{{ $dpa->subject->username }}</td>
								<td>{{ $dpa->filed->diffForHumans() }}</td>
							</tr>
						@endforeach
						</tbody>
						<tfoot>
						<tr>
							<td><strong>{{ __('id') }}</strong></td>
							<td><strong>{{ __('username') }}</strong></td>
							<td><strong>{{ __('request-age') }}</strong></td>
						</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</x-slot>
	<x-slot name="scripts"></x-slot>
</x-layout>
