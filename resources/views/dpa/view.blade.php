<x-layout>
	<x-slot name="pgname">
		{{ __('dpa') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('dpa-new') }}</h3>
		<div class="row mb-3">
			@if ( $dpa->reject )
				<div role="alert" class="alert alert-danger text-center">
					<span><strong>{{ __('dpa-rejected', [ 'complete' => $dpa->completed, 'reject' => __('dpa-reject-' . $dpa->reject ) ] ) }}</strong><br/></span>
				</div>
			@elseif ( $dpa->completed )
				<div role="alert" class="alert alert-success text-center">
					<span><strong>{{ __('dpa-accepted', [ 'complete' => $dpa->completed ] ) }}</strong><br/></span>
				</div>
			@endif
			<div class="col-lg-8">
				<div class="row">
					<div class="col">
						<div class="card shadow mb-3">
							<div class="card-header py-3">
								<p class="text-primary m-0 fw-bold text-center">{{ __('subject') }}</p>
							</div>
							<div class="card-body">
								<form>
									<div class="row">
										<div class="col">
											<div class="mb-3">
												<label class="form-label"
												       for="username"><strong>{{ __('username') }}: </strong>{{ $dpa->user->username }}
												</label>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="card shadow">
							<div class="card-header py-3">
								<p class="text-primary m-0 fw-bold text-center">{{ __('evidence') }}</p>
							</div>
							<div class="card-body">
								<form>
									<div class="row">
										<div class="col">
											@if ( $dpa->underage === null )
												<div class="alert alert-success text-center text-primary" role="alert">
													<span><strong>{{ __('dpa-own-account') }}</strong><br/></span>
												</div>
											@else
												<p>{{ $dpa->underage }}</p>
											@endif
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 mb-md-auto">
				<div class="card shadow mb-4">
					<div class="card-body text-center">
						<x-user.verified :user="$dpa->user"/>
					</div>
				</div>
				@if ( !$dpa->completed && Gate::check('ts') )
					<div class="card shadow mb-3">
						<div class="card-header py-3">
							<p class="text-primary m-0 fw-bold text-center">{{ __('resolution') }}</p>
						</div>
						<div class="card-body">
							<form method="POST" action="/dpa/{{ $dpa->id }}">
								@csrf
								@method('PATCH')
								<div class="row">
									<div class="col text-center" style="margin-bottom: 20px;">
										<button class="btn btn-success text-white" name="approve" value="approve" type="submit">{{ __('approve') }}</button>
									</div>
								</div>
							</form>
							<div class="col text-center">
								<button class="btn btn-danger" type="button" data-bs-target="#modal-reject"
								        data-bs-toggle="modal">{{ __('reject') }}
								</button>
							</div>
							<div class="modal fade" role="dialog" tabindex="-1" id="modal-reject">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">{{ __('dpa-reject') }}</h4>
											<button type="button" class="btn-close" data-bs-dismiss="modal"
											        aria-label="Close"></button>
										</div>
										<form method="POST" action="/dpa/{{ $dpa->id }}">
											@csrf
											@method('PATCH')
											<div class="modal-body">
												<div class="alert alert-danger text-center" role="alert">
													<span><strong>{{ __('dpa-reject-notice') }}</strong></span>
												</div>
												<div class="col">
													<div class="mb-3"></div>
													<label class="form-label" for="reason"><strong>{{ __('dpa-reject-label') }}...</strong></label>
													<select class="form-select" name="reason" id="reason">
														@foreach ( config('app.rejectDPA') as $reason )
															<option value="{{ $reason }}">{{ __('dpa-reject-' . $reason ) }}
															</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="modal-footer">
												<button class="btn btn-light" type="button" data-bs-dismiss="modal">
													{{ __('close') }}
												</button>
												<button class="btn btn-primary" name="reject" type="submit">{{ __('reject') }}
												</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				@endif
			</div>
		</div>
	</x-slot>
	<x-slot name="scripts"></x-slot>
</x-layout>
