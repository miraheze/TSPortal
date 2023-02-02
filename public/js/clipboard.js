/*
 * To make this script copy elements to the clipboard, add the "copyToClipboard" class to a copy to clipboard button.
 * Then add a 'data-copy="idOfTheElementYouWantToCopy"' attribute to the element with the copyToClipboard class.
*/
function copyToClipboard(event) {
	try {
		if (event.target.dataset.copy = "") {
			console.warn('Element has an empty data-copy attribute. Nothing was copied to the clipboard', event.target);
			return;
		}
	} catch (e) {
		console.warn('Error when accesing data-copy attribute. Nothing was copied to the clipboard.', event.target, e);
		return;
	}
	let copyElement = document.getElementById(event.target.dataset.copy);
	navigator.clipboard.writeText(copyElement.innerText);
}
let copyElements = document.getElementsByClassName("copyToClipboard");
for (let i = 0; i < copyElements.length; i++) {
	copyElements[i].addEventListener("click", copyToClipboard)
}
