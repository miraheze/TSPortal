@props( [ 'rows' ] )

<table class="table table-sm my-0 align-middle">
	<tbody>
	@foreach ( $rows as [ $label, $value ] )
		<tr>
			<td>{{ $label }}</td>
			<td class="text-end fw-bold"><x-transparency.count :value="$value"/></td>
		</tr>
	@endforeach
	</tbody>
</table>
