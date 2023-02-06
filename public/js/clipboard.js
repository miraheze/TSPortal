function copyToClipboard(event) {
	navigator.clipboard.writeText(event.target.innerText);
}

let copyElements = document.getElementsByClassName("copyToClipboard");
for (let i = 0; i < copyElements.length; i++) {
	copyElements[i].addEventListener("click", copyToClipboard)
}
