function updateAppealFilter() {
	let current;

	if (document.getElementById('event').value) {
		current = document.getElementById('event').value;
	}

	hideAll('toggle-hideall');
	if (current === 'appeal-recv') {
		document.getElementById('filter-appeal-recv-label').classList.remove('d-none');
		document.getElementById('filter-appeal-recv').classList.remove('d-none');
	}
}

function hideAll(hideClass) {
	const elements = document.getElementsByClassName(hideClass);
	for (const id of elements) {
		id.classList.add('d-none');
	}
}

window.onload = updateAppealFilter();
