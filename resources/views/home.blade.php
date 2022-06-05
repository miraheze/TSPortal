<x-layout>
	<x-slot name="pgname">
		{{ __('home') }}
	</x-slot>
	<x-slot name="content">
		@can('ts')
			<div class="row">
				<div class="col-md-6 col-xl-3 mb-4">
					<div class="card shadow border-start-primary py-2">
						<div class="card-body">
							<div class="row align-items-center no-gutters">
								<div class="col me-2">
									<div class="text-uppercase text-primary fw-bold text-xs mb-1">
										<span>{{ __('open-investigations') }}</span></div>
									<div class="text-dark fw-bold h5 mb-0">
										<span>{{ count(\App\Models\Investigation::all()->whereNull('closed')) }}</span>
									</div>
								</div>
								<div class="col-auto"><i class="fa-solid fa-magnifying-glass fa-2x text-gray-300"></i></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xl-3 mb-4">
					<div class="card shadow border-start-success py-2">
						<div class="card-body">
							<div class="row align-items-center no-gutters">
								<div class="col me-2">
									<div class="text-uppercase text-success fw-bold text-xs mb-1">
										<span>{{ __('open-reports') }}</span>
									</div>
									<div class="text-dark fw-bold h5 mb-0">
										<span>{{ count(\App\Models\Report::all()->whereNull('reviewed')) }}</span></div>
								</div>
								<div class="col-auto"><i class="fa-solid fa-triangle-exclamation fa-2x text-gray-300"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xl-3 mb-4">
					<div class="card shadow border-start-info py-2">
						<div class="card-body">
							<div class="row align-items-center no-gutters">
								<div class="col me-2">
									<div class="text-uppercase text-info fw-bold text-xs mb-1"><span>{{ __('open-dpa') }}</span>
									</div>
									<div class="row g-0 align-items-center">
										<div class="col-auto">
											<div class="text-dark fw-bold h5 mb-0 me-3">
												<span>{{ count(\App\Models\DPA::all()->whereNull('completed')) }}</span>
											</div>
										</div>
									</div>
								</div>
								<div class="col-auto"><i class="fa-solid fa-user-slash fa-2x text-gray-300"></i></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xl-3 mb-4">
					<div class="card shadow border-start-warning py-2">
						<div class="card-body">
							<div class="row align-items-center no-gutters">
								<div class="col me-2">
									<div class="text-uppercase text-warning fw-bold text-xs mb-1">
										<span>{{ __('open-appeals') }}</span>
									</div>
									<div class="text-dark fw-bold h5 mb-0"><span>{{ count([]) }}</span></div>
								</div>
								<div class="col-auto"><i class="fa-solid fa-gavel fa-2x text-gray-300"></i></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endcan
		<div class="row">
			<div class="col">
				<div class="row">
					<div class="col-lg-6 mb-4">
						<div class="card shadow text-center">
							<div class="card-header py-3">
								<p class="text-primary m-0 fw-bold">{{ __('home-q-trust-and-safety') }}</p>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p style="border-color: rgb(133, 135, 150); color: rgb(133, 135, 150);">{{ __('home-a-trust-and-safety') }}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6 mb-4">
						<div class="card shadow text-center">
							<div class="card-header py-3">
								<p class="text-primary m-0 fw-bold">{{ __('home-q-how-report') }}</p>
							</div>
							<div class="card-body">
								<div class="row" style="border-color: rgb(255, 255, 255);">
									<div class="col">
										<p style="border-color: rgb(133, 135, 150); color: rgb(133, 135, 150);">{{ __('home-a-how-report') }}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6 mb-4">
						<div class="card shadow text-center">
							<div class="card-header py-3">
								<p class="text-primary m-0 fw-bold">{{ __('home-q-dpa') }}</p>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p style="border-color: rgb(133, 135, 150); color: rgb(133, 135, 150);">{{ __('home-a-dpa') }}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6 mb-4">
						<div class="card shadow text-center">
							<div class="card-header py-3">
								<p class="text-primary m-0 fw-bold">{{ __('home-q-appeal') }}</p>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p style="border-color: rgb(133, 135, 150); color: rgb(133, 135, 150);">{{ __('home-a-appeal') }}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</x-slot>
	<x-slot name="scripts"></x-slot>
</x-layout>
