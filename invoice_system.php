<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Invoice Generator</h1>
        <form id="invoiceForm" method="POST" action="generate_invoice.php">
            <label for="invoiceDate">Invoice Date:</label>
            <input type="date" id="invoiceDate" name="invoiceDate" required><br><br>

            <label for="customerName">Customer Name:</label>
            <input type="text" id="customerName" name="customerName" required><br><br>

            <table id="itemsTable">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Subject</th>
                        <th>Price Rate</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Hours</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="studentNames[]" required></td>
                        <td>
                            <select name="subjects[]" required>
                                <option value="BM">BM</option>
                                <option value="BI">BI</option>
                                <option value="MT">MT</option>
                                <option value="SC">SC</option>
                                <option value="SJ">SJ</option>
                            </select>
                        </td>
                        <td>
                            <select name="priceRates[]" required>
                                <option value="20">20</option>
                                <option value="25">25</option>
                                <option value="30">30</option>
                                <option value="35">35</option>
                                <option value="40">40</option>
                                <option value="45">45</option>
                                <option value="50">50</option>
                                <option value="55">55</option>
                                <option value="60">60</option>
                            </select>
                        </td>
                        <td><input type="date" name="dates[]" required></td>
                        <td><input type="time" name="startTimes[]" required onchange="calculateTotalHours(this)"></td>
                        <td><input type="time" name="endTimes[]" required onchange="calculateTotalHours(this)"></td>
                        <td><input type="number" name="hours[]" class="hours" readonly></td>
                        <td><input type="number" name="amounts[]" class="amount" readonly></td>
                        <td><button type="button" onclick="removeRow(this)">Remove</button></td>
                    </tr>
                </tbody>
            </table>

            <br>
            <button type="button" onclick="addRow()">Add Row</button>
            <br><br>

            <strong>Total Amount:</strong> <input type="number" id="totalAmount" name="totalAmount" readonly>
            <br><br>

            <button type="submit">Generate Invoice</button>
        </form>
    </div>

    <script>
        function addRow() {
            const table = document.getElementById('itemsTable').querySelector('tbody');
            const lastRow = table.querySelector('tr:last-child'); // Get the last row of the table
            const newRow = lastRow.cloneNode(true); // Clone the last row

            // Retain event listeners for the cloned row
            newRow.querySelector('input[name="startTimes[]"]').addEventListener('change', function () {
                calculateTotalHours(this);
            });
            newRow.querySelector('input[name="endTimes[]"]').addEventListener('change', function () {
                calculateTotalHours(this);
            });

            // Append the cloned row to the table
            table.appendChild(newRow);
        }

        function removeRow(button) {
            const row = button.parentNode.parentNode;
            row.remove();
            calculateTotal();
        }

        function calculateTotalHours(element) {
            const row = element.closest('tr');
            const startTime = row.querySelector('input[name="startTimes[]"]').value;
            const endTime = row.querySelector('input[name="endTimes[]"]').value;
            const hoursField = row.querySelector('.hours');
            const amountField = row.querySelector('.amount');
            const priceRate = row.querySelector('select[name="priceRates[]"]').value;

            if (startTime && endTime) {
                const start = new Date(`1970-01-01T${startTime}:00Z`);
                const end = new Date(`1970-01-01T${endTime}:00Z`);
                let diff = (end - start) / (1000 * 60 * 60);

                if (diff < 0) {
                    diff += 24;
                }

                hoursField.value = diff;
                amountField.value = diff * priceRate;
                calculateTotal();
            }
        }

        function calculateTotal() {
            const rows = document.querySelectorAll('#itemsTable tbody tr');
            let total = 0;

            rows.forEach(row => {
                const amountField = row.querySelector('.amount');
                total += parseFloat(amountField.value || 0);
            });

            document.getElementById('totalAmount').value = total;
        }
    </script>
</body>
</html>
