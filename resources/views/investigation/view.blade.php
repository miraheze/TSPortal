<x-layout>
	<x-slot name="pgname">
		{{ __('investigations') }}
	</x-slot>
	<x-slot name="content">
		@if ( $investigation->openAppeal() )
			<div class="alert alert-danger text-center" role="alert">
				<span>
					<strong>{{ __('investigation-open-appeal') }} <a
							href="/appeal/{{ $investigation->openAppeal() }}">#{{ $investigation->openAppeal() }}</a>.</strong>
				</span>
			</div>
		@endif
		<h3 class="text-dark mb-4">{{ __('investigation-header') }} (#{{ $investigation->id }})</h3>
		<div class="row mb-3">
			<div class="col-lg-8">
				<div class="row">
					<div class="col">
						<div class="card shadow mb-3">
							<div class="card-header py-3">
								<p class="text-primary m-0 fw-bold text-center">{{ __('core') }}</p>
							</div>
							<div class="card-body">
								<form>
									<div class="row">
										<div class="col">
											<p><strong>{{ __('investigation-about') }}</strong> {{ $investigation->type ? __('investigation-topic-' . $investigation->type ) : __('empty') }}.
											</p>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="card shadow mb-3">
							<div class="card-header py-3">
								<p class="text-primary m-0 fw-bold text-center">{{ __('evidence') }}</p>
							</div>
							<div class="card-body">
								<form>
									<div class="row">
										<div class="col">
											<p>{{ $investigation->text ?? __('empty') }}</p>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="card shadow mb-3">
							<div class="card-header py-3">
								<p class="text-primary m-0 fw-bold text-center">{{ __('recommendation') }}</p>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col">
										<p><strong>{{ __('recommendation-is') }}</strong> {{ $investigation->recommendation ? __('recommendation-' . $investigation->recommendation ) : __('empty') }}</p>
										<p><strong>{{ __('recommendation-reason') }}</strong> {{ $investigation->explanation ?? __('empty') }}.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="text-center text-primary fw-bold m-0">{{ __('information') }}</h6>
					</div>
					<div class="card-body">
						<p style="text-align: center;">
							{{ __('assigned') }}: @if ( $investigation->assigned->username )
								{{ $investigation->assigned->username }}
							@else
								<a href="/investigation/{{ $investigation->id }}/edit">[{{ __('claim') }}]</a>
							@endif
						</p>
						<p style="text-align: center;">
							{{ __('status') }}: @if ( $investigation->closed )
								<span class="badge bg-danger">{{ __('status-closed') }}</span>
							@else
								<span class="badge bg-success">{{ __('status-open') }}</span>
							@endif
						</p>
						<div class="row">
							<div class="col" style="text-align: center;">
								<button class="btn btn-primary text-white" type="button" data-bs-target="#modal-1"
								        data-bs-toggle="modal"><i class="fa-solid fa-circle-plus"></i> {{ __('investigation-event') }}
								</button>
								<hr>
								<button class="btn btn-primary text-white" onclick="location.href='/investigation/edit/{{ $investigation->id }}'" type="button"><i class="fa-solid fa-square-pen"></i> {{ __('investigation-edit') }}
								</button>
							</div>
						</div>
						<div class="modal fade" role="dialog" tabindex="-1" id="modal-1">
							<form method="POST" action="/investigation/{{ $investigation->id }}">
								@csrf
								@method('PATCH')
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h4 class="modal-title">{{ __('investigation-event-add') }}</h4>
											<button type="button" class="btn-close" data-bs-dismiss="modal"
											        aria-label="Close"></button>
										</div>
										<div class="modal-body">
											<label class="form-label" for="event"><strong>{{ __('investigation-event-q') }}</strong></label>
											<select class="form-select" name="event" id="event" onchange="updateAppealFilter()">
												@foreach( config('app.events') as $type => $events )
													<optgroup label="{{ __('events-' . $type ) }}">
														@foreach ( $events as $event )
															<option value="{{ $type . '-' . $event }}">{{ __('events-' . $type . '-' . $event ) }}</option>
														@endforeach
													</optgroup>
												@endforeach
											</select>
											<label class="form-label toggle-hideall d-none" id="filter-appeal-recv-label" for="filter-appeal-recv"><strong>{{ __('appeal-type') }}</strong></label>
											<select class="form-select form-control toggle-hideall d-none" name="appeal-type" id="filter-appeal-recv">
												@foreach( config('app.appeals') as $type => $data )
													<option value="{{ $type }}" {{ ( request()->input( 'type' ) == $type ) ? 'selected' : '' }}>{{ ucfirst(__('appeal-type-' . $type)) }}.</option>
												@endforeach
											</select>
											<label class="form-label mt-1" for="comments"><strong>{{ __('comments') }}</strong></label><textarea
												class="form-control" name="comments" id="comments"></textarea>
										</div>
										<div class="modal-footer">
											<button class="btn btn-primary" type="submit">Submit</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="card shadow mb-4">
					<div class="card-header py-3">
						<h6 class="text-center text-primary fw-bold m-0">{{ __('subject') }}</h6>
					</div>
					<div class="card-body">
						<p class="text-center"><a href="/user/{{ $investigation->subject->id }}">{{ $investigation->subject->username }}</a></p>
						<p style="text-align: center;">
							{{ __('status') }}:
							<x-user.standing :user="$investigation->subject"/>
						</p>
					</div>
				</div>
			</div>
		</div>
		@if ( count( $investigation->events ) )
			<div class="card shadow mb-3" style="margin-bottom: 0;">
				<div class="card-header py-3">
					<p class="text-primary m-0 fw-bold text-center">{{ __('history') }}</p>
				</div>
				<div class="card-body">
					@foreach ( $investigation->events as $event)
						<figure>
							<blockquote class="blockquote">
								<p class="mb-0">{{ __('events-' . $event->action . '-desc', [ 'comment' => $event->comment ?? __('events-no-comment') ] ) }}</p>
							</blockquote>
							<figcaption class="blockquote-footer">{{ $event->created_by->username }}
								at {{ $event->created }}</figcaption>
						</figure>
						@if ( !$loop->last )
							<hr/>
						@endif
					@endforeach
				</div>
			</div>
		@endif
		<div class="card shadow mb-5">
			<form method="POST" action="/investigation/{{ $investigation->id }}">
				@csrf
				@method('PATCH')
				<input type="hidden" name="event" value="comment">
				<div class="card-header py-3 text-center">
					<label class="text-primary m-0 fw-bold" for="comments">{{ __('comments-add') }}</label>
				</div>
				<div class="card-body">
					<input class="form-control" name="comments" id="comments" type="text"/>
					<div class="form-check mt-2">
						<input class="form-check-input" type="checkbox" id="status" name="status" value="status"
						       style="padding: 0; height: 16px; margin: 5px 0 0 -24px;"/>
						<label class="form-check-label" for="status">
							@if ( $investigation->closed )
								{{ __('investigation-reopen') }}
							@else
								{{ __('investigation-close') }}
							@endif
						</label>
					</div>
					<div class="text-center">
						<button class="btn btn-primary text-white" type="submit" style="padding: 4px 8px; margin: 10px 0 0 25px;">
							<i class="fa-solid fa-comment-medical fa-sm text-white-50"></i> {{ __('comments-add') }}
						</button>
					</div>
				</div>
			</form>
		</div>
	</x-slot>
	<x-slot name="scripts">
		<script src="/js/appeal-filter.js"></script>
	</x-slot>
</x-layout>


