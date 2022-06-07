<x-layout>
	<x-slot name="pgname">
		{{ __('investigations') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('investigation-launch') }}</h3>
		@if ( $errors->any() )
			<div class="alert alert-danger">
				@foreach ( $errors->all() as $error )
					<li>{{ $error }}</li>
				@endforeach
			</div>
		@endif
		<form method="POST" action="/investigation/new">
			@csrf
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
											<label class="form-label" for="topic"><strong>{{ __('investigation-about') }}...</strong></label>
											<select class="form-select" name="topic" id="topic">
												@foreach ( config('app.investigationTopics') as $topic )
													<option
														value="{{ $topic }}" {{ ( old( 'topic' ) == $topic ) ? 'selected' : '' }}>
														... {{ __('investigation-topic-' . $topic ) }}
													</option>
												@endforeach
											</select>
											<label class="form-label" for="username"><strong>{{ __('investigation-involved') }}:</strong><br/></label><input class="form-control" type="text"
											                                                                                                                 name="username" id="username" value="{{ old('username') }}" placeholder="username"/>
											<small class="form-text">
												{{ __('investigation-involved-note') }}
											</small>
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
										<div class="col"><textarea class="form-control" name="evidence" id="evidence">{{ old('evidence') }}</textarea></div>
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
											<div class="mb-3"></div>
											<label class="form-label" for="recommend"><strong>{{ __('recommendation-is') }}...</strong></label>
											<select class="form-select" name="recommend" id="recommend">
												@foreach ( config('app.recommendations') as $recommend )
													<option
														value="{{ $recommend }}">
														... {{ __('recommendation-' . $recommend ) }}
													</option>
												@endforeach
											</select>
											<label class="form-label" for="justify"><strong>{{ __('recommendation-reason') }}...</strong></label>
											<textarea class="form-control" name="justify" id="justify">{{ old('justify') }}</textarea>
										</div>
									</div>
									<div class="mb-3"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
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
					<button class="btn btn-primary" type="submit"
					        style="padding: 4px 8px; margin: 10px 0 0 25px;">
						<i class="fa-solid fa-magnifying-glass-plus fa-sm text-white-50"></i> {{ __('investigation-launch') }}
					</button>
				</div>
			</div>
		</form>
	</x-slot>
	<x-slot name="scripts"></x-slot>
</x-layout>
