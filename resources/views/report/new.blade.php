<x-layout>
	<x-slot name="pgname">
		Reports
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('report-header') }}</h3>
		@if ( $errors->any() )
			<div class="alert alert-danger">
				@foreach ( $errors->all() as $error )
					<li>{{ $error }}</li>
				@endforeach
			</div>
		@endif
		<form method="POST" action="/report/new">
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
											<div class="mb-3">
												<label class="form-label" for="report"><strong>{{ __('report-reporting') }}...</strong></label>
												<select class="form-select" name="report" id="report" onchange="reportGuidance()">
													@foreach( config('app.reportTopics') as $type => $topics )
														<optgroup label="{{ $type }}">
															@foreach( $topics as $topic )
																{{ $allTopics[] = $topic }}
																<option value="{{ $type . '-' . $topic }}" {{ ( old('report') == $type . '-' . $topic ) ? 'selected' : '' }}>... {{ __('report-topic-' . $type . '-' . $topic) }}.</option>
															@endforeach
														</optgroup>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col">
											<label class="form-label" for="username"><strong>{{ __('investigation-involved') }}:</strong><br/></label><input class="form-control" type="text"
											                                                                                                                 name="username" id="username" value="{{ old('username') }}"/>
											<small class="form-text">
												{!! __('investigation-involved-note') !!}
											</small>
										</div>
									</div>
									<div class="mb-3"></div>
								</div>
							</div>
							<div class="card shadow">
								<div class="card-header py-3 text-center">
									<label class="text-primary m-0 fw-bold" for="evidence">{{ __('evidence') }}</label>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col"><textarea class="form-control" name="evidence"
										                           id="evidence" rows="5">{{ old('evidence') }}</textarea></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="text-primary fw-bold m-0 text-center">{{ __('guidance') }}</h6>
						</div>
						<div class="card-body">{!! __('guidance-report') !!}</div>
						@foreach( $allTopics as $topic )
							<div class="card-body toggle-hideall {{ $loop->first ? "" : "d-none" }}" id="guidance-{{ $topic }}">{!! __('guidance-report-' . $topic) !!}</div>
						@endforeach
					</div>
				</div>
			</div>
			<div class="card shadow mb-5">
				<div class="card-header py-3">
					<p class="text-primary m-0 fw-bold text-center">{{ __('submission') }}</p>
				</div>
				<div class="card-body text-center">
					<div class="row">
						<div class="col">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="at" name="at" value="at"
								       style="padding: 0; height: 16px; margin: 5px 0 0 -24px;" {{ old('at') ? 'checked' : '' }}/>
								<label class="form-check-label" for="at">{{ __('report-at-risk') }}</label>
							</div>
						</div>
					</div>
					<button class="btn btn-primary d-none d-sm-inline-block" type="submit"
					        style="padding: 4px 8px; margin: 10px 0 0 25px;"><i
							class="fa-solid fa-triangle-exclamation fa-sm text-white-50"></i> {{ __('submit') }}
					</button>
				</div>
			</div>
		</form>
	</x-slot>
	<x-slot name="scripts">
		<script src="/js/guidance-toggles.js"></script>
	</x-slot>
</x-layout>
