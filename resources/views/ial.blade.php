<x-layout>
	<x-slot name="pgname">
		{{ __('ial') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('ial-list') }}</h3>
		<div class="card shadow">
			<div class="card-body">
				<div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
					<table class="table table-striped my-0 text-center align-middle" id="dataTable">
						<thead>
						<tr>
							<th style="width: 10%;">{{ __('id') }}</th>
							<th style="width: 20%;">{{ __('username') }}</th>
							<th style="width: 15%;">{{ __('wiki') }}</th>
							<th style="width: 10%;">{{ __('ial-type') }}</th>
							<th style="width: 35%">{{ __('comments') }}</th>
							<th style="width: 10%;">{{ __('assigned') }}</th>
						</tr>
						</thead>
						<tbody>
						@foreach ( $ials as $ial )
							<tr>
								<td>{{ $ial->id }}</td>
								<td><a class="nav-link" href="/user/{{ $ial->user }}">{{ \App\Models\User::findById( $ial->user )->username }}</a></td>
								<td>{{ $ial->wiki }}</td>
								<td>{{ $ial->type }}</td>
								<td>{{ $ial->comments }}</td>
								@if ( $ial->dpa )
									<td><a class="nav-link" href="/dpa/{{ $ial->dpa }}">{{ __('dpa') }}</a></td>
								@elseif ( $ial->investigation )
									<td><a class="nav-link" href="/investigation/{{ $ial->investigation }}">{{ __('investigation') }}</a></td>
								@else
									<td>
										<button class="btn btn-danger text-white" type="button" data-bs-target="#ial-{{ $ial->id }}-modal"
										        data-bs-toggle="modal"><i class="fa-solid fa-link"></i> {{ __('assign') }}
										</button>
									</td>
								@endif
							</tr>
						@endforeach
						</tbody>
						<tfoot>
						<tr>
							<th style="width: 10%;">{{ __('id') }}</th>
							<th style="width: 20%;">{{ __('username') }}</th>
							<th style="width: 15%;">{{ __('wiki') }}</th>
							<th style="width: 10%;">{{ __('ial-type') }}</th>
							<th style="width: 35%">{{ __('comments') }}</th>
							<th style="width: 10%;">{{ __('assigned') }}</th>
						</tr>
						</tfoot>
					</table>
					@if ( $ials->hasPages() )
						<nav>
							<ul class="pagination justify-content-center">
								<li class="page-item {{ $ials->onFirstPage() ? 'disabled' : '' }}">
									<a class="page-link" {{ $ials->onFirstPage() ? '' : 'href=' . $ials->previousPageUrl() }} ">{{ __('nav-previous') }}</a>
								</li>
								<li class="page-item {{ $ials->hasMorePages() ? '' : 'disabled' }}">
									<a class="page-link" {{ $ials->hasMorePages() ? 'href=' . $ials->nextPageUrl() : '' }}>{{ __('nav-next') }}</a>
								</li>
							</ul>
						</nav>
					@endif
				</div>
				@foreach ( $ials as $ial )
					@if ( is_null($ial->investigation) && is_null($ial->dpa) )
						<div class="modal fade" role="dialog" tabindex="-1" id="ial-{{ $ial->id }}-modal">
							<form method="POST" action="/ial/{{ $ial->id }}">
								@csrf
								@method('PATCH')
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">{{ __('ial-assign-header') }}</h4>
											<button type="button" class="btn-close" data-bs-dismiss="modal"
											        aria-label="Close"></button>
										</div>
										<div class="modal-body">
											<label class="form-label mt-1" for="assign-id"><strong>{{ __('ial-assign-label') }}</strong></label>
											<input class="form-control" name="assign-id" id="assign-id">
										</div>
										<div class="modal-footer">
											<button class="btn btn-primary" type="submit">Submit</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					@endif
				@endforeach
			</div>
		</div>
	</x-slot>
	<x-slot name="scripts"></x-slot>
</x-layout>
