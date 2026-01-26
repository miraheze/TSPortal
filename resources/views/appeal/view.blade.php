<x-layout>
	<x-slot name="pgname">
		{{ __('appeal') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('appeal-header') }} (#{{ $appeal->id }})</h3>
		<div class="row mb-3">
			<div class="col-lg-8">
				<div class="row">
					<div class="col">
						<div class="card shadow mb-3">
							<div class="card-header py-3">
								<p class="text-primary m-0 fw-bold text-center">{{ __('appeal') }}</p>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p>{{ $appeal->text }}</p>
									</div>
								</div>
							</div>
						</div>
						<div class="card shadow mb-3">
							<div class="card-header py-3">
								<p class="text-primary m-0 fw-bold text-center">{{ __('appeal-review') }}</p>
							</div>
							<div class="card-body">
								@if( $appeal->review === null )
									<div class="row">
										<div class="col">
											{{ __('empty') }}
										</div>
									</div>
								@else
									<div class="container text-center">
										@foreach( json_decode( $appeal->review, true ) as $name => $data )
											@if( $name == 'appeal-outcome' )
												<div class="row mt-2 mb-2">
													<div class="col-10 fw-bold align-content-center">{{ __($name) }}</div>
													@if( $data == 'upheld')
														<div class="col-2"><p class="btn btn-outline-danger fw-bold disabled">{{ ucfirst( __( 'appeal-outcome-upheld' ) ) }}</p></div>
													@else
														<div class="col-2"><p class="btn btn-outline-success fw-bold disabled">{{ ucfirst( __( 'appeal-outcome-not-upheld' ) ) }}</p></div>
													@endif
												</div>
											@elseif( $name == 'comments' )
												<div class="row mt-2 mb-2">
													<div class="col">{{ $data }}</div>
												</div>
											@else
												@php
													$longOpt = ( $data == 'y' ) ? 'yes' : 'no';
													$value = config('app.appeals.' . $appeal->type . '.' . $name )[$longOpt];
													$colour = ( $value == 1 ) ? 'success' : ( ( $value == -1 ) ? 'danger' : 'secondary' );
												@endphp
												<div class="row mb-2 mt-2">
													<div class="col-10 fw-bold">{{ __('appeal-review-' . $appeal->type . '-' . $name) }}</div>
													<div class="col-2"><p class="btn btn-outline-{{ $colour }} fw-bold disabled">{{ __( $longOpt )}}</p></div>
												</div>
											@endif
										@endforeach
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="text-center text-primary fw-bold m-0">{{ __('information') }}</h6>
					</div>
					<div class="card-body">
						<p style="text-align: center;">
							{{ __('assigned') }}: @if ( $appeal->assigned->username )
								{{ $appeal->assigned->username }}
							@else
								{{ __('unassigned') }}
							@endif
						</p>
						<p style="text-align: center;">
							{{ __('status') }}: @if ( $appeal->reviewed )
								<span class="badge bg-success">{{ __('status-closed') }}</span>
							@else
								<span class="badge bg-danger">{{ __('status-open') }}</span>
							@endif
						</p>
						@if( !$appeal->reviewed )
							<div class="row">
								<div class="col" style="text-align: center;">
									<button class="btn btn-primary text-white" type="button" data-bs-target="#modal-1"
									        data-bs-toggle="modal"><i class="fa-solid fa-eye"></i> {{ __('appeal-edit-review') }}
									</button>
								</div>
							</div>
						@endif
						<div class="modal fade" role="dialog" tabindex="-1" id="modal-1">
							<form method="POST" action="/appeal/{{ $appeal->id }}">
								@csrf
								@method('PATCH')
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">{{ __('appeal') }}</h4>
											<button type="button" class="btn-close" data-bs-dismiss="modal"
											        aria-label="Close"></button>
										</div>
										<div class="modal-body text-center">
											@foreach( config('app.appeals.' . $appeal->type ) as $label => $outcomes )
												<p class="form-label py-2"><strong>{{ __('appeal-review-' . $appeal->type . '-' . $label) }}</strong></p>
												@foreach( $outcomes as $opt => $value )
													@php
														$sc = ( $opt == 'yes' ) ? 'y' : 'n';
														$colour = ( $value == 1 ) ? 'success' : ( ( $value == -1 ) ? 'danger' : 'secondary' );
													@endphp
													<input type="radio" class="btn-check" name="{{ $label }}" id="{{ $label . '-' . $sc }}" autocomplete="off" value="{{ $sc }}">
													<label class="btn btn-outline-{{ $colour }}" for="{{ $label . '-' . $sc }}">{{ __( $opt ) }}</label>
												@endforeach
											@endforeach
											<hr>
											<p class="form-label"><strong>{{ __('appeal-outcome') }}</strong></p>
											<input type="radio" class="btn-check" name="appeal-outcome" id="upheld" autocomplete="off" value="upheld">
											<label class="btn btn-outline-danger" for="upheld">{{ ucfirst(__( 'appeal-outcome-upheld' )) }}</label>
											<input type="radio" class="btn-check" name="appeal-outcome" id="not-upheld" autocomplete="off" value="not-upheld">
											<label class="btn btn-outline-success" for="not-upheld">{{ ucfirst(__( 'appeal-outcome-not-upheld' )) }}</label>
											<br>
											<label class="form-label mt-2" for="comments"><strong>{{ __('comments') }}</strong></label>
											<textarea class="form-control" name="comments" id="comments"></textarea>
										</div>
										<div class="modal-footer">
											<button class="btn btn-primary" type="submit">Submit</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="text-center text-primary fw-bold m-0">{{ __('assets') }}</h6>
					</div>
					<div class="card-body text-center">
						<p class="fw-bold mt-1 mb-1">{{ __('investigation') }}</p>
						<a href="/investigation/{{ $appeal->investigation->id }}">#{{ $appeal->investigation->id }}</a>
						@foreach( $appeal->investigation->reports as $report )
							@if( $loop->first )
								<p class="fw-bold mt-1 mb-1">{{ __('report') }}</p>
							@endif
							<a href="/report/{{ $report->id }}">#{{ $report->id }}</a>
							@if( !$loop->last )
								<br>
							@endif
						@endforeach
						@foreach( $appeal->investigation->appeals as $otherAppeal )
							@if( $loop->first && $loop->count > 1)
								<p class="fw-bold mt-1 mb-1">{{ __('appeal') }}</p>
							@endif
							@if ( $appeal->id != $otherAppeal->id )
								<a href="/appeal/{{ $otherAppeal->id }}">#{{ $otherAppeal->id }}</a>
								@if( !$loop->last )
									<br>
								@endif
							@endif
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</x-slot>
	<x-slot name="scripts">
		<script src="/js/appeal-filter.js"></script>
	</x-slot>
</x-layout>
