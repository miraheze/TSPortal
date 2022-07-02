<x-layout>
	<x-slot name="pgname">
		{{ __('reports') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('report-header') }} (#{{ $report->id }})</h3>
		@if ( $report->reviewed )
			@if ( $report->investigation )
				<div class="alert alert-success text-center" role="alert">
					@can('update', $report)
						<span>
                            <strong>{{ __('report-investigation') }} <a
		                            href="/investigation/{{ $report->investigation }}">#{{ $report->investigation }}</a>.</strong>
                        </span>
					@else
						<span><strong>{{ __('report-investigation-public') }}</strong></span>
					@endcan
				</div>
			@else
				<div class="alert alert-danger text-center" role="alert">
					<span><strong>{{ __('report-closed') }}</strong></span>
				</div>
			@endif
		@endif
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
											<p><strong>{{ __('report-about') }} </strong>{{ __( 'report-topic-' . $report->type ) }}.</p>
										</div>
										<p><strong>{{ __('investigation-involved') }}:</strong> <a
												href="/user/{{ $report->user->id }}">{{ $report->user->username }}</a>
										</p>
									</div>
								</div>
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
											<p>{{ $report->text }}</p>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="text-primary fw-bold m-0 text-center">{{ __('subject') }}</h6>
					</div>
					<div class="card-body">
						@can('update', $report)
							@if ( ( count( $report->user->reports ) + count( $report->user->investigations ) ) > 1 )
								<div class="alert alert-danger text-center" role="alert">
									<strong>{{ __('subject-known') }}</strong>
								</div>
							@else
								<div class="alert alert-success text-center" role="alert">
									<strong>{{ __('subject-notknown') }}</strong>
								</div>
							@endif
						@endcan
						<p>CentralAuth: <a
								href="https://meta.miraheze.org/wiki/Special:CentralAuth/{{ $report->user->username }}">{{ $report->user->username }}</a>
						</p>
					</div>
				</div>
				@can('update', $report)
					<div class="card shadow mb-4">
						<div class="card-header py-3">
							<h6 class="text-primary fw-bold m-0 text-center">{{ __('resolution') }}</h6>
						</div>
						<div class="card-body">
							<form method="POST" action="/report/{{ $report->id }}">
								@csrf
								@method('PATCH')
								<div class="col text-center" style="margin-bottom: 20px;">
									<button class="btn btn-success" name="investigate" value="true" type="submit">{{ __('investigation-launch') }}</button>
								</div>
								<div class="col text-center" style="margin: 0;">
									<button class="btn btn-danger" name="close" value="true" type="submit">{{ __('report-close') }}</button>
								</div>
							</form>
						</div>
					</div>
				@endcan
			</div>
		</div>
	</x-slot>
	<x-slot name="scripts"></x-slot>
</x-layout>
