<x-layout>
	<x-slot name="pgname">
		{{ __('users') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('user-profile') }}</h3>
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
										<div class="mb-1">
											<label class="form-label" for="username">
												<strong>{{ __('username') }}: </strong>
												<a href="https://meta.miraheze.org/wiki/Special:CentralAuth/{{ $user->username }}">{{ $user->username }}</a>
											</label>
										</div>
										<div class="mt-1">
											<p><strong>{{ __('date-known') }}:</strong> {{ \Carbon\Carbon::parse( $user->created )->format('jS F Y') }}
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card shadow">
							<div class="card-header py-3">
								<p class="text-primary m-0 fw-bold text-center">{{ __('associations') }}</p>
							</div>
							<div class="card-body">
								@can('ts')
									<div class="mt-1">
										<p><strong>{{ __('investigations') }}:</strong> <a href="/investigations?user={{ $user->id }}">{{ count($user->investigations) }} </a>
										</p>
									</div>
									<div class="mt-1">
										<p><strong>{{ __('reports') }}:</strong> <a href="/reports?user={{ $user->id }}">{{ count($user->reports) }}</a>
										</p>
									</div>
								@endcan
								<div class="mt-1">
									<p><strong>{{ __('reports-made') }}:</strong> <a href="/reports?made={{ $user->id }}">{{ count( $user->reportsMade) }}</a></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 mb-md-auto">
				<div class="card shadow mb-4">
					<div class="card-body text-center">
						<x-user.verified :user="$user"/>
					</div>
				</div>
				<div class="card shadow mb-4">
					<div class="card-body text-center">
						<strong>{{ __('status') }}:&nbsp;</strong>
						<x-user.standing :user="$user"/>
					</div>
				</div>
				<div class="card shadow mb-3">
					<div class="card-header py-3">
						<p class="text-primary m-0 fw-bold text-center">{{ __('user-flags') }}</p>
					</div>
					<div class="card-body text-center">
						<x-user.flags :user="$user"/>
						@can('user-manager')
							<hr>
							<form method="POST" action="/user/{{ $user->id }}">
								@csrf
								@method('PATCH')
								<div class="row">
									<fieldset>
										<legend>{{ __('user-flags-modify') }} {{ $user->username }}</legend>
										@foreach ( $user->allFlags() as $flag )
											<div class="form-check">
												<input id="{{ $flag }}" name="new-access[]" value="{{ $flag }}"
												       class="form-check-input"
												       type="checkbox" {{ ( $user->hasFlag( $flag ) ) ? "checked" : "" }}/>
												<label class="form-check-label" for="{{ $flag }}">{{ __( 'user-flags-' . $flag ) }}</label>
											</div>
										@endforeach
									</fieldset>
								</div>
								<button class="btn btn-success text-white" type="submit"
								        style="padding: 4px 8px; margin: 10px 0 0 25px;"><i
										class="fa-solid fa-user-tag fa-sm text-white-50"></i> {{ __('user-flags-submit') }}
								</button>
							</form>
						@endcan
					</div>
				</div>
			</div>
		</div>
		@can('ts')
			<div class="row">
				<div class="col">
					<div class="card shadow mb-3" style="margin-bottom: 0;">
						<div class="card-header py-3">
							<p class="text-primary m-0 fw-bold text-center">{{ __('history') }}</p>
						</div>
						<div class="card-body">
							@foreach ( $user->events as $event)
								<figure>
									<blockquote class="blockquote">
										@if ( $event->action == 'update-flags' )
											<p class="mb-0">{{ __('events-' . $event->action . '-desc', [ 'flags' => implode( ', ', json_decode( $event->comment, true ) ) ] ) }}</p>
										@else
											<p class="mb-0">{{ __('events-' . $event->action . '-desc', [ 'comment' => $event->comment ] ) }}</p>
										@endif
									</blockquote>
									<figcaption class="blockquote-footer">{{ $event->created_by->username }}
										at {{ \Carbon\Carbon::parse( $event->created )->format('g:ia \o\n l jS F Y') }}</figcaption>
								</figure>
								@if (!$loop->last )
									<hr/>
								@endif
							@endforeach
						</div>
					</div>
				</div>
			</div>
		@endcan
	</x-slot>
	<x-slot name="scripts"></x-slot>
</x-layout>

