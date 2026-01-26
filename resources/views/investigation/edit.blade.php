<x-layout>
	<x-slot name="pgname">
		{{ __('investigations') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('investigation-edit') }} #{{ $investigation->id }}</h3>
		<form method="POST" action="/investigation/{{ $investigation->id }}">
			@csrf
			@method('PATCH')
			<div class="row mb-3">
				<div class="col-lg-8">
					<div class="row">
						<div class="col">
							<div class="card shadow mb-3">
								<div class="card-header py-3">
									<p class="text-primary m-0 fw-bold text-center">{{ __('core') }}</p>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col">
											<label class="form-label" for="topic"><strong>{{ __('investigation-about') }} ...</strong></label>
											<select class="form-select" name="topic" id="topic">
												@foreach ( config('app.investigationTopics') as $topic )
													<option
														value="{{ $topic }}" {{ ( $investigation->type == $topic ) ? "selected" : "" }}>
														... {{ __('investigation-topic-' . $topic ) }}
													</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="card shadow mb-3">
								<div class="card-header py-3 text-center">
									<label class="text-primary m-0 fw-bold" for="evidence">{{ __('evidence') }}</label>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col"><textarea class="form-control" name="evidence"
										                           id="evidence">{{ $investigation->text }}</textarea>
										</div>
									</div>
									<div class="mb-3"></div>
									<div class="alert alert-warning text-center" role="alert">
										<span><strong>{{ __('investigation-legislation-note') }}</strong><br/></span>
									</div>
								</div>
							</div>
							<div class="card shadow mb-3">
								<div class="card-header py-3">
									<p class="text-primary m-0 fw-bold text-center">{{ __('recommendation') }}</p>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col">
											<label class="form-label" for="recommend"><strong>{{ __('recommendation-is') }}...</strong></label>
											<select class="form-select" name="recommend" id="recommend">
												@foreach ( config('app.recommendations') as $recommend )
													<option
														value="{{ $recommend }}" {{ ( $investigation->recommendation == $recommend ) ? "selected" : "" }}>
														... {{ __('recommendation-' . $recommend ) }}
													</option>
												@endforeach
											</select>
											<label class="form-label" for="justify"><strong> {{ __('recommendation-reason') }}...</strong></label>
											<textarea class="form-control" name="justify"
											          id="justify">{{ $investigation->explanation }}</textarea>
										</div>
									</div>
									<div class="mb-3"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					@if ( $investigation->assigned != auth()->user() )
						<div class="card shadow mb-4">
							<div class="card-header py-3">
								<h6 class="text-primary fw-bold m-0 text-center"><label for="assign">{{ __('reassign') }}</label></h6>
							</div>
							<div class="card-body">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" name="assign" id="assign" value="assign"
									       style="padding: 0; height: 16px; margin: 5px 0 0 -24px;"/>
									<label class="form-check-label" for="assign">
										@if ( $investigation->assigned === null )
											{{ __('reassign-claim') }}
										@else
											{!! __('reassign-assigned', [ 'assigned' => $investigation->assigned->username ]) !!}
										@endif
									</label>
								</div>
							</div>
						</div>
					@endif
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="text-center text-primary fw-bold m-0">{{ __('legislation') }}</h6>
						</div>
						<div class="card-body">
							@foreach ( config('app.legislation') as $name => $act )
								<strong>{{ $name }}</strong> - {{ $act }} <br>
							@endforeach
						</div>
					</div>
				</div>
			</div>
			<div class="card shadow mb-5 text-center">
				<div class="card-header py-3">
					<p class="text-primary m-0 fw-bold">{{ __('submission') }}</p>
				</div>
				<div class="card-body">
					<button class="btn btn-primary text-white" name="edit" type="submit"
					        style="padding: 4px 8px; margin: 10px 0 0 25px;">
						<i class="fa-solid fa-pen-square"></i> {{ __('investigation-update') }}
					</button>
				</div>
			</div>
		</form>
	</x-slot>
	<x-slot name="scripts"></x-slot>
</x-layout>

