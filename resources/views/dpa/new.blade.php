<x-layout>
	<x-slot name="pgname">
		{{ __('dpa') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('dpa-new') }}</h3>
		@if ( $errors->any() )
			<div class="alert alert-danger">
				@foreach ( $errors->all() as $error )
					<li>{{ $error }}</li>
				@endforeach
			</div>
		@endif
		<form method="POST" action="/dpa/new">
			@csrf
			<div class="row mb-3">
				<div class="col-lg-8">
					<div class="row">
						<div class="col">
							<div class="card shadow mb-3">
								<div class="card-header py-3">
									<p class="text-primary m-0 fw-bold text-center">{{ __('subject') }}</p>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col">
											<div class="mb-3">
												<label class="form-label"
												       for="username"><strong>{{ __('username') }}</strong></label><input
													class="form-control" type="text" id="username"
													placeholder="username" name="username"
													value="{{ old('username') }}"/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col">
											<div class="mb-3"></div>
											<label class="form-label" for="username-type"><strong>{{ __('dpa-username-above') }}...</strong></label>
											<select class="form-select" id="username-type" name="username-type" onchange="dpaEvidence()">
												<option
													value="own-removal" {{ ( old('username-type') == 'own-removal' ) ? 'selected' : '' }}>
													... {{ __('dpa-username-mine') }}.
												</option>
												<option
													value="under-13" {{ ( old('username-type') == 'under-13' ) ? 'selected' : '' }}>
													... {{ __('dpa-username-u13') }}.
												</option>
											</select>
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
									<div class="row toggle-hideall d-none" id="evidence-under-13">
										<div class="col"><textarea class="form-control" id="evidence" name="evidence" rows="5">{{ old('evidence') }}</textarea>
										</div>
									</div>
									<div class="alert alert-success text-center text-primary toggle-hideall" role="alert" id="evidence-own-removal">
										<span><strong>{{ __('dpa-oauth-verify') }}</strong><br/></span>
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
						<div class="card-body">{!! __('guidance-dpa') !!}</div>
					</div>
				</div>
			</div>
			<div class="card shadow mb-5 text-center">
				<div class="card-header py-3">
					<p class="text-primary m-0 fw-bold">{{ __('submission') }}</p>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="dpa" name="dpa" value="dpa"
								       style="padding: 0; height: 16px; margin: 5px 0 0 -24px;" {{ old('dpa') ? 'checked' : '' }}/>
								<label class="form-check-label" for="dpa">
									{{ __('dpa-statutory') }}
								</label>
							</div>
						</div>
					</div>
					<button class="btn btn-primary d-none d-sm-inline-block" type="submit"
					        style="padding: 4px 8px; margin: 10px 0 0 25px;"><i
							class="fa-solid fa-hourglass fa-sm text-white-50"></i> {{ __('submit') }}
					</button>
				</div>
			</div>
		</form>
	</x-slot>
	<x-slot name="scripts">
		<script src="/js/guidance-toggles.js"></script>
	</x-slot>
</x-layout>
