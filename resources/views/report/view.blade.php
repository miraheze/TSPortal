<x-layout>
	<x-slot name="pgname">
		{{ __( 'reports' ) }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4 fw-semibold">{{ __( 'report-header' ) }} (#{{ $report->id }})</h3>
		@if ( $report->reviewed )
			@if ( $report->investigation )
				<div class="alert alert-success text-center shadow-sm border-0" role="alert">
					@can('ts')
						<span>
                            <strong>{{ __( 'report-investigation' ) }} <a
		                            href="/investigation/{{ $report->investigation }}">#{{ $report->investigation }}</a>.</strong>
                        </span>
					@else
						<span><strong>{{ __( 'report-investigation-public' ) }}</strong></span>
					@endcan
				</div>
			@elseif ( $report->dpa )
				<div class="alert alert-success text-center shadow-sm border-0" role="alert">
					<span><strong>{{ __( 'report-dpa' ) }}</strong></span>
				</div>
			@else
				<div class="alert alert-danger text-center shadow-sm border-0" role="alert">
					<span><strong>{{ __( 'report-closed' ) }}</strong></span>
				</div>
			@endif
		@endif
		<div class="row mb-4">
			<div class="col-lg-8">
				<div class="row">
					<div class="col">
						<div class="card shadow-sm mb-4 border-0">
							<div class="card-header py-3 border-bottom">
								<p class="text-primary m-0 fw-bold text-center">{{ __( 'core' ) }}</p>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<div class="mb-4">
											<p><strong>{{ __( 'report-about' ) }} </strong>{{ __( 'report-topic-' . $report->type ) }}.</p>
										</div>
										<p><strong>{{ __( 'investigation-involved' ) }}:</strong> <a
												href="/user/{{ $report->user->id }}">{{ $report->user->username }}</a>
										</p>
									</div>
								</div>
							</div>
						</div>
						<div class="card shadow-sm mb-4 border-0">
							<div class="card-header py-3 border-bottom">
								<p class="text-primary m-0 fw-bold text-center">{{ __( 'evidence' ) }}</p>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<div class="p-3 bg-light rounded border">
											<p class="mb-0">{{ $report->text }}</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="card shadow-sm mb-4 border-0">
					<div class="card-header py-3 border-bottom">
						<h6 class="text-primary fw-bold m-0 text-center">{{ __( 'subject' ) }}</h6>
					</div>
					<div class="card-body">
						@can('ts')
							@if ( ( $report->user->reports()->count() + $report->user->investigations()->count() ) > 1 )
								<div class="alert alert-danger text-center border-0 shadow-sm">
									<strong>{{ __( 'subject-known' ) }}</strong>
								</div>
							@else
								<div class="alert alert-success text-center border-0 shadow-sm">
									<strong>{{ __( 'subject-notknown' ) }}</strong>
								</div>
							@endif
						@endcan
						<p>CentralAuth: <a
								href="https://meta.miraheze.org/wiki/Special:CentralAuth/{{ $report->user->username }}">{{ $report->user->username }}</a>
						</p>
					</div>
				</div>
				@can('ts')
					@unless ( $report->investigation || $report->dpa )
						<div class="card shadow-sm mb-4 border-0">
							<div class="card-header py-3 border-bottom">
								<h6 class="text-primary fw-bold m-0 text-center">{{ __( 'resolution' ) }}</h6>
							</div>
							<div class="card-body">
								<form method="POST" action="/report/{{ $report->id }}">
									@csrf
									@method('PATCH')
									@unless ( $report->reviewed )
										<div class="row">
											<div class="col d-flex justify-content-center gap-2 flex-wrap">
												<button class="btn btn-success px-4 flex-fill flex-sm-grow-0" name="investigate" value="true" type="submit">
													{{ __( 'investigation-launch' ) }}
												</button>
												<button class="btn btn-warning px-4 flex-fill flex-sm-grow-0" name="dpa" value="true" type="submit">
													{{ __( 'dpa-open' ) }}
												</button>
												<button class="btn btn-danger px-4 flex-fill flex-sm-grow-0" name="close" value="true" type="submit">
													{{ __( 'report-close' ) }}
												</button>
											</div>
										</div>
									@else
										<div class="d-grid">
											<button class="btn btn-primary px-4 flex-fill flex-sm-grow-0" name="reopen" value="true" type="submit">
												{{ __( 'report-reopen' ) }}
											</button>
										</div>
									@endunless
								</form>
							</div>
						</div>
					@endunless
				@endcan
			</div>
		</div>
	</x-slot>
	<x-slot name="scripts"></x-slot>
</x-layout>
