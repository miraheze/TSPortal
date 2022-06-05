<x-layout>
	<x-slot name="pgname">
		{{ __('users') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('users-list') }}</h3>
		<div class="card shadow">
			<div class="card-body">
				<div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
					<table class="table table-striped my-0 text-center align-middle" id="dataTable">
						<thead>
						<tr>
							<th style="width: 10%;">{{ __('id') }}</th>
							<th style="width: 40%;">{{ __('username') }}</th>
							<th style="width: 20%;">{{ __('status') }}</th>
							<th style="width: 10%;">{{ __('verified') }}</th>
							<th style="width: 20%;">{{ __('flags') }}</th>
						</tr>
						</thead>
						<tbody>
						@foreach ( $users as $user )
							<tr>
								<td><a class="nav-link" href="/user/{{ $user->id }}">{{ $user->id }}</a></td>
								<td>{{ $user->username }}</td>
								<td>
									<x-user.standing :user="$user"/>
								</td>
								<td>
									<x-user.verified :user="$user"/>
								</td>
								<td>
									<x-user.flags :user="$user"/>
								</td>
							</tr>
						@endforeach
						</tbody>
						<tfoot>
						<tr>
							<th style="width: 10%;">{{ __('id') }}</th>
							<th style="width: 40%;">{{ __('username') }}</th>
							<th style="width: 20%;">{{ __('status') }}</th>
							<th style="width: 10%;">{{ __('verified') }}</th>
							<th style="width: 20%;">{{ __('flags') }}</th>
						</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</x-slot>
	<x-slot name="scripts"></x-slot>
</x-layout>
