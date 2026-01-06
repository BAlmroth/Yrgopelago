<section class="transferService">

    <h3>Get transfer code</h3>

    <form id="getTransferCode">
        <label for="tc_user">Name:</label>
        <input type="text" name="user" id="tc_user" required>

        <label for="tc_api_key">API key:</label>
        <input type="text" name="api_key" id="tc_api_key" required>

        <label for="tc_amount">Amount:</label>
        <input type="number" name="amount" id="tc_amount" required>

        <button type="submit">Get transfer code</button>
    </form>

    <p id="transferResult"></p>

    <hr>

</section>
