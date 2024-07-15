<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<section class="bg-grey">
    <div class="container">
        <h1>Cash App Pay method vs. Cash App Link method</h1>
        <p><strong><em>Checkout with Cash App</em></strong> is a WordPress plugin that helps your customers pay you with Cash App.</p>
        <p><a class="btn btn-primary" role="button" href="https://square.theafricanboss.com" target="_blank">Go to square.theafricanboss.com for updates</a></p>
        <p><strong><em>Checkout with Cash App</em> plugin comes with 2 payment methods</strong>. Enable one of them based on the features you need.</p>
        <p>Here is how the checkout page looks like for both</p>

        <div class="">
            <img class="shadow" src="<?php echo (WCCASHAPP_PLUGIN_DIR_URL . 'assets/images/cash_app_pay_checkout.jpg'); ?>" width="50%" height="auto" alt="Cash App Pay on the checkout page" />
            <p>Cash App Pay example above</p>
            <br><br>
            <img class="shadow" src="<?php echo (WCCASHAPP_PLUGIN_DIR_URL . 'assets/images/cash_app_checkout.jpg'); ?>" width="50%" height="auto" alt="Cash App Link on the checkout page" />
            <p>Cash App Link PRO example above</p>
        </div>
        <br><br>
        <h2>Here is a much more detailed list of differences:</h2>
        <div class="table-responsive bg-light shadow compare p-0">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="table-primary text-center table-title">Woocommerce Payment Gateways</th>
                        <th class="text-center subscription-plan">
                            <p>Payment Method #1</p>
                            <h2>Cash App Pay</h2>
                        </th>
                        <th class="text-center subscription-plan">
                            <p>Payment Method #2</p>
                            <h2>Cash App Link</h2>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="feature-row">
                        <td>Official Square Integration<br /></td>
                        <td class="text-center feature-option">✔</td>
                        <td class="text-center feature-option">x</td>
                    </tr>
                    <tr class="feature-row">
                        <td>Transaction &amp; App Fees **<br /></td>
                        <td class="text-center feature-option">✔</td>
                        <td class="text-center feature-option">x - with personal Cash App<br />✔ - with Cash for Business<br /></td>
                    </tr>
                    <tr class="feature-row">
                        <td>Requires Cash App account<br /></td>
                        <td class="text-center feature-option">x</td>
                        <td class="text-center feature-option">✔</td>
                    </tr>
                    <tr class="feature-row">
                        <td>Requires Square merchant account<br /></td>
                        <td class="text-center feature-option">✔</td>
                        <td class="text-center feature-option">x</td>
                    </tr>
                    <tr class="feature-row">
                        <td>emailreceipts.io integration<br /></td>
                        <td class="text-center feature-option">N/A</td>
                        <td class="text-center feature-option">✔</td>
                    </tr>
                    <tr class="feature-row">
                        <td>Automatic order status updates<br /></td>
                        <td class="text-center feature-option">✔</td>
                        <td class="text-center feature-option">x - without emailreceipts.io<br />✔ - with emailreceipts.io (NEW)<br /></td>
                    </tr>
                    <tr class="feature-row">
                        <td>Reduce inventory stock<br /></td>
                        <td class="text-center feature-option">✔</td>
                        <td class="text-center feature-option">x - without emailreceipts.io<br />✔ - with emailreceipts.io (NEW)<br /></td>
                    </tr>
                    <tr class="feature-row">
                        <td>Transfer/Withdrawal platform<br /></td>
                        <td class="text-center feature-option">Square via website/app/hardware</td>
                        <td class="text-center feature-option">Cash App via the app</td>
                    </tr>
                    <tr class="feature-row">
                        <td>Instant transfer/withdrawal<br /></td>
                        <td class="text-center feature-option">instant transfer = paid<br />delayed transfer = free</td>
                        <td class="text-center feature-option">instant transfer = paid<br />delayed transfer = free</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="mt-4">** <a href="https://squareup.com/us/en/payments/our-fees" target="_blank">Square</a> charges 2.6% + $0.10 for all swiped transactions including Visa, Master Card, Discover and American Express. 3.5% + $0.15 for manually entered transactions.</p>
        <p class="mt-4">** <a href="https://cash.app/help/us/en-us/6502-what-is-cash-for-business" target="_blank">Cash for Business</a> lets you accept unlimited payments of any size using your email, $cashtag, or cash.app account. You won’t have any account limits, but there will be a 2.5% per transaction fee when you accept payments with a Cash for Business account.</p>
    </div>
</section>