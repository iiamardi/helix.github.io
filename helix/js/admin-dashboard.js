document.addEventListener("DOMContentLoaded", function () {
    fetch("admin-dashboard.php")
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById("productsTableBody");
            tableBody.innerHTML = "";

            data.forEach(product => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${product.id}</td>
                    <td>${product.name}</td>
                    <td>${product.price} €</td>
                    <td>
                        <a href="/admin/edit-product.php?id=${product.id}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="/admin/delete-product.php?id=${product.id}" class="btn btn-sm btn-danger ms-2">Delete</a>
                    </td>
                `;

                tableBody.appendChild(row);
            });
        })
        .catch(error => {
            console.error("Gabim gjatë marrjes së produkteve:", error);
        });
});
