function updateFilter() {
	let current;

	if (document.getElementById('filter').value) {
		current = document.getElementById('filter').value;
	} else {
		current = 'type';
	}

	hideAll('toggle-hideall');
	const element = document.getElementById('filter-' + current);
	element.classList.remove('d-none');
	element.disabled = !document.getElementById('filter').value;
}

function hideAll(hideClass) {
	const elements = document.getElementsByClassName(hideClass);
	for (const id of elements) {
		id.classList.add('d-none');
		id.disabled = true;
	}
}

window.onload = updateFilter();
