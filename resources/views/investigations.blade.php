<x-layout>
	<x-slot name="pgname">
		{{ __('investigations') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('open-investigations') }}</h3>
		<div class="card shadow">
			<div class="card-body">
				<div class="table-responsive table mt-2">
					<table class="table my-0 text-center align-middle">
						<thead>
						<tr>
							<th style="width: 10%;">{{ __('id') }}</th>
							<th style="width: 20%;">{{ __('topic') }}</th>
							<th style="width: 40%;">{{ __('subject') }}</th>
							<th style="width: 15%;">{{ __('assigned') }}</th>
							<th style="width: 15%;">{{ __('status') }}</th>
						</tr>
						</thead>
						<tbody>
						@foreach ( $investigations as $investigation )
							<tr>
								<td><a class="nav-link"
								       href="/investigation/{{ $investigation->id }}">{{ $investigation->id }}</a></td>
								<td>{{ ucfirst(__('investigation-topic-' . $investigation->type)) }}</td>
								<td><a class="nav-link"
								       href="/user/{{ $investigation->subject->id }}">{{ $investigation->subject->username }}</a>
								</td>
								<td>{{ $investigation->assigned->username }}</td>
								@if ( $investigation->closed )
									<td><span class="badge bg-danger">{{ __('status-closed') }}</span></td>
								@else
									<td><span class="badge bg-success">{{ __('status-open') }}</span></td>
								@endif
							</tr>
						@endforeach
						</tbody>
						<tfoot>
						<tr>
							<th style="width: 10%;">{{ __('id') }}</th>
							<th style="width: 20%;">{{ __('topic') }}</th>
							<th style="width: 40%;">{{ __('subject') }}</th>
							<th style="width: 15%;">{{ __('assigned') }}</th>
							<th style="width: 15%;">{{ __('status') }}</th>
						</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</x-slot>
	<x-slot name="scripts"></x-slot>
</x-layout>
