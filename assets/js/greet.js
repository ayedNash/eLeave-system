const hours = new Date().getHours();
const headerTitle = document.querySelector("header h1");
if (hours < 12) {
    headerTitle.textContent = "Good Morning, Admin";
} else if (hours < 18) {
    headerTitle.textContent = "Good Afternoon, Admin";
} else {
    headerTitle.textContent = "Good Evening, Admin";
}

const headerTitle2 = document.querySelector("adminHeader left h1");
if (hours < 12) {
    headerTitle2.textContent = "Good Morning, Admin";
} else if (hours < 18) {
    headerTitle2.textContent = "Good Afternoon, Admin";
} else {
    headerTitle2.textContent = "Good Evening, Admin";
}

