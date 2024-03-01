<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Arrow Key Dropdown Demo</title>
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Highlight the selected item */
        .selected {
            background-color: #ddd;
        }
    </style>
</head>

<body>
    <div class="dropdown">
        <button onclick="toggleDropdown()" id="dropdownBtn">Dropdown</button>
        <div id="myDropdown" class="dropdown-content">
            <a href="#" onclick="selectItem(this)">Item 1</a>
            <a href="#" onclick="selectItem(this)">Item 2</a>
            <a href="#" onclick="selectItem(this)">Item 3</a>
            <a href="#" onclick="selectItem(this)">Item 4</a>
            <a href="#" onclick="selectItem(this)">Item 5</a>
        </div>
    </div>

    <script>
        let dropdownIndex = -1;

        function toggleDropdown() {
            const dropdown = document.getElementById("myDropdown");
            dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block";
        }

        function selectItem(item) {
            resetDropdown();
            item.classList.add("selected");
            document.getElementById("dropdownBtn").innerText = item.innerText;
            toggleDropdown();
        }

        function resetDropdown() {
            const items = document.querySelectorAll(".dropdown-content a");
            items.forEach(item => {
                item.classList.remove("selected");
            });
        }

        document.addEventListener("keydown", function (e) {
            const dropdown = document.getElementById("myDropdown");
            const items = document.querySelectorAll(".dropdown-content a");

            if (dropdown.style.display === "block") {
                switch (e.key) {
                    case "ArrowUp":
                        dropdownIndex = Math.max(dropdownIndex - 1, 0);
                        break;
                    case "ArrowDown":
                        dropdownIndex = Math.min(dropdownIndex + 1, items.length - 1);
                        break;
                    case "Enter":
                        if (dropdownIndex !== -1) {
                            selectItem(items[dropdownIndex]);
                        }
                        break;
                }

                items.forEach((item, index) => {
                    if (index === dropdownIndex) {
                        item.classList.add("selected");
                    } else {
                        item.classList.remove("selected");
                    }
                });
            }
        });
    </script>
</body>

</html>