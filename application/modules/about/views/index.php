
<center>
    <p class="lead">
        This application allows you to reserve an unused carpark <br/>or<br/>you can release you carpark if you are not using it.<br/>      
    </p>
    If you like this app, leave a small donation and keep the author in beer. <br/>
    Application written by Frank Cusmano.<br/><br/>

    <?php 
    if (empty($this->input->post('stripeEmail'))) {
        echo form_open(site_url() . '/about');
        echo '
            <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="pk_test_zCbNzs6Nmpnb9IqdqN3wsRHc"
                data-amount="500"
                data-name="Parker"
                data-description="Donate $5"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                data-locale="auto"
                data-zip-code="false"
                data-currency="aud"
                data-panel-label="Donate"
                data-label="Donate"
                >
            </script>
        ';
        echo form_close(); 
    } else {
      echo '<BR><BR>THANK YOU FOR YOUR DONATION';  
    }
?>
        
</center>