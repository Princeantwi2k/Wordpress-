<?php
class ClearGage_Payment_Gateway {
    public function __construct() {
        // Add hooks or initialization here
        add_action('woocommerce_payment_gateways', array($this, 'add_cleargage_gateway'));
    }

    public function add_cleargage_gateway($gateways) {
        $gateways[] = 'WC_ClearGage_Gateway';
        return $gateways;
    }
}

new ClearGage_Payment_Gateway();

class WC_ClearGage_Gateway extends WC_Payment_Gateway {
    public function __construct() {
        $this->id = 'cleargage';
        $this->has_fields = false;
        $this->method_title = 'ClearGage';
        $this->title = 'ClearGage Payment Gateway';

        $this->init_form_fields();
        $this->init_settings();

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'   => __('Enable/Disable', 'woocommerce'),
                'type'    => 'checkbox',
                'label'   => __('Enable ClearGage Payment Gateway', 'woocommerce'),
                'default' => 'yes',
            ),
            'title' => array(
                'title'       => __('Title', 'woocommerce'),
                'type'        => 'text',
                'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
                'default'     => __('ClearGage', 'woocommerce'),
                'desc_tip'    => true,
            ),
            'description' => array(
                'title'       => __('Description', 'woocommerce'),
                'type'        => 'textarea',
                'description' => __('This controls the description which the user sees during checkout.', 'woocommerce'),
                'default'     => __('Pay securely using ClearGage.', 'woocommerce'),
            ),
            'api_key' => array(
                'title'       => __('ClearGage API Key', 'woocommerce'),
                'type'        => 'text',
                'description' => __('Enter your ClearGage API Key.', 'woocommerce'),
                'default'     => '',
            ),
            // Add more configuration fields as needed
        );
    }
    

    public function process_payment($order_id) {
        $order = wc_get_order($order_id);
    
        // Your ClearGage API credentials
        $api_key = $this->get_option('api_key');
    
        // Get order details
        $amount = $order->get_total();
        $currency = $order->get_currency();
        $customer_email = $order->get_billing_email();
    
        // Call ClearGage API to process the payment
        $response = $this->call_cleargage_api($api_key, $amount, $currency, $customer_email);
    
        // Check if the payment was successful
        if ($response && $response->success) {
            // Payment was successful, update order status and reduce stock
            $order->payment_complete();
            $order->reduce_order_stock();
            wc_add_notice(__('Payment successful!', 'woocommerce'), 'success');
            return array(
                'result'   => 'success',
                'redirect' => $this->get_return_url($order),
            );
        } else {
            // Payment failed, display an error message
            wc_add_notice(__('Payment failed. Please try again.', 'woocommerce'), 'error');
            return;
        }
    }
    
    // Helper function to call the ClearGage API
    private function call_cleargage_api($api_key, $amount, $currency, $customer_email) {
        // Implement the logic to call ClearGage API here
        // Use $api_key, $amount, $currency, and $customer_email in the request
        // Handle the response and return it
        // Note: This is a placeholder, replace it with the actual API integration code
        $response = array('success' => true); // Placeholder, modify as needed
        return json_decode(json_encode($response));
    }
    

    public function payment_fields() {
        // Enqueue the JavaScript file
        wp_enqueue_script('cleargage-payment', plugin_dir_url(__FILE__) . 'js/cleargage-payment.js', array('jquery'), '1.0', true);
    
        // Localize variables to be used in JavaScript
        wp_localize_script('cleargage-payment', 'cleargage_params', array(
            'api_key' => $this->get_option('api_key'),
            // Add more configuration parameters as needed
        ));
    
        // Output additional form fields if necessary
        echo '<div id="cleargage-extra-fields">';
        echo '<label for="cleargage_custom_field">' . __('Custom Field:', 'woocommerce') . '</label>';
        echo '<input type="text" id="cleargage_custom_field" name="cleargage_custom_field" />';
        echo '</div>';
    }
    
    
}
