<x-layout>
	<x-slot name="pgname">
		{{ __( 'dpa' ) }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4 fw-semibold">{{ __( 'dpa-new' ) }}</h3>
		<div class="row mb-3">
			@if ( $dpa->reject )
				<div role="alert" class="alert alert-danger text-center shadow-sm border-0">
					<span><strong>{{ __( 'dpa-rejected', [ 'complete' => $dpa->completed, 'reject' => __( 'dpa-reject-' . $dpa->reject ) ] ) }}</strong><br/></span>
				</div>
			@elseif ( $dpa->completed )
				<div role="alert" class="alert alert-success text-center shadow-sm border-0">
					<span><strong>{{ __( 'dpa-accepted', [ 'complete' => $dpa->completed ] ) }}</strong><br/></span>
				</div>
			@endif
			<div class="col-lg-8">
				<div class="row">
					<div class="col">
						<div class="card shadow-sm mb-4 border-0">
							<div class="card-header py-3 border-bottom">
								<p class="text-primary m-0 fw-bold text-center">{{ __( 'subject' ) }}</p>
							</div>
							<div class="card-body">
								<div class="row g-3">
									<div class="col-12">
										<div class="p-3 bg-light rounded d-flex justify-content-between align-items-center border flex-nowrap">
											<div class="me-2 overflow-hidden">
												<div class="text-muted small">{{ __( 'username' ) }}</div>
												<div class="fw-semibold fs-6 text-truncate" id="username">
													{{ $dpa->user->username }}
												</div>
											</div>
											<button type="button" class="btn btn-outline-primary copyToClipboard flex-shrink-0 px-3 py-2" data-copy="username">
												<i class="fa fa-clipboard me-1"></i>{{ __( 'copy' ) }}
											</button>
										</div>
									</div>
									<div class="col-12">
										<div class="p-3 bg-light rounded d-flex justify-content-between align-items-center border flex-nowrap">
											<div class="me-2 overflow-hidden">
												<div class="text-muted small">{{ __( 'id' ) }}</div>
												<div class="fw-semibold fs-6 text-truncate" id="id">
													{{ $dpa->id }}
												</div>
											</div>
											<button type="button" class="btn btn-outline-primary copyToClipboard flex-shrink-0 px-3 py-2" data-copy="id">
												<i class="fa fa-clipboard me-1"></i>{{ __( 'copy' ) }}
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card shadow-sm border-0">
							<div class="card-header py-3 border-bottom">
								<p class="text-primary m-0 fw-bold text-center">{{ __( 'evidence' ) }}</p>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col">
										@unless ( $dpa->underage )
											<div class="alert alert-success text-center border-0 shadow-sm">
												<span><strong>{{ __( 'dpa-own-account' ) }}</strong><br/></span>
											</div>
										@else
											<div class="p-3 bg-light rounded border">
												<p class="mb-0">{{ $dpa->underage }}</p>
											</div>
										@endunless
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 mb-md-auto">
				<div class="card shadow-sm mb-4 border-0">
					<div class="card-body text-center">
						<x-user.verified :user="$dpa->user"/>
					</div>
				</div>
				@can('ts')
					@unless ( $dpa->completed )
						<div class="card shadow-sm mb-3 border-0">
							<div class="card-header py-3 border-bottom">
								<p class="text-primary m-0 fw-bold text-center">{{ __( 'resolution' ) }}</p>
							</div>
							<div class="card-body">
								<form method="POST" action="/dpa/{{ $dpa->id }}">
									@csrf
									@method('PATCH')
									<div class="row">
										<div class="col d-flex justify-content-center gap-2 flex-wrap" style="margin-bottom: 20px;">
											<button class="btn btn-success px-4 flex-fill flex-sm-grow-0" name="approve" value="true" type="submit">
												{{ __( 'approve' ) }}
											</button>
											<button class="btn btn-danger px-4 flex-fill flex-sm-grow-0" type="button" data-bs-target="#modal-reject"
											        data-bs-toggle="modal">
												{{ __( 'reject' ) }}
											</button>
										</div>
									</div>
								</form>
								<div class="modal fade" role="dialog" tabindex="-1" id="modal-reject">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content border-0 shadow">
											<div class="modal-header border-bottom">
												<h4 class="modal-title fw-semibold">{{ __( 'dpa-reject' ) }}</h4>
												<button type="button" class="btn-close" data-bs-dismiss="modal"
												        aria-label="{{ __( 'close' ) }}"></button>
											</div>
											<form method="POST" action="/dpa/{{ $dpa->id }}">
												@csrf
												@method('PATCH')
												<div class="modal-body">
													<div class="alert alert-danger text-center border-0 shadow-sm">
														<span><strong>{{ __( 'dpa-reject-notice' ) }}</strong></span>
													</div>
													<div class="col">
														<div class="mb-3"></div>
														<label class="form-label" for="reason">
															<strong>{{ __( 'dpa-reject-label' ) }}...</strong>
														</label>
														<select class="form-select shadow-sm" name="reason" id="reason">
															@foreach ( config( 'app.rejectDPA' ) as $reason )
																<option value="{{ $reason }}">{{ __( 'dpa-reject-' . $reason ) }}</option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="modal-footer border-top">
													<button class="btn btn-light" type="button" data-bs-dismiss="modal">
														{{ __( 'close' ) }}
													</button>
													<button class="btn btn-primary px-4" name="reject" type="submit">
														{{ __( 'reject' ) }}
													</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					@endunless
				@endcan
			</div>
		</div>
	</x-slot>
	<x-slot name="scripts"></x-slot>
</x-layout>
