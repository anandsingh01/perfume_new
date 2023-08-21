@extends('layouts.web')
<?php
session_start();
?>
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        .input-group {
            padding: 0;
            justify-content: center;
        }
        .cart-bottom{
            display:block;
        }
        form {
            width: 100%;
        }

        #privacy_policy , #privacy_policy p{
            color:#000;
            font-size: 16px;
        }
        #privacy_policy h3{
            color: #173054;
            font-weight: 600;
        }
    </style>
@stop
@section('body')
    <?php
    $get_cart = get_cart();
    $get_count = json_decode($get_cart);
    $getAllCart = getCartProducts();
    ?>

    <main class="main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Return policy</li>
                </ol>
            </div><!-- End .container -->
        </nav><!-- End .breadcrumb-nav -->

        <div class="" style="background-image: url('assets/images/backgrounds/login-bg.jpg')">
            <div class="container" id="privacy_policy">
                <div class="row">
                    <h3 class="text-center">  SHIPPING INFORMAITON:</h3>
                    <div class="col-md-12">
                        <p>
                           <b>
                               FREE standard shipping for all orders above $75.00
                           </b>
                        </p>
                        (The $75.00 total is calculated AFTER any promotion, coupon deductions and before taxes)

                        <br>
                        Shipping charges for Standard Ground shipping is calculated by our system automatically depending on weight and location of shipment ordered.
                        </p>

                        <p>
                        <h6> Standard U.S. shipping (4-7 business days)</h6>
                        U.S. orders will be shipped via U.S. Postal Service, UPS or FedEx. Most packages arrive within 4-7 business days. (Not a guaranteed service)

                        </p>

                        <p>
                        <h6> Express U.S. Shipping (2-3 business days guaranteed).</h6>
                        Not available at P.O. Boxes.
                        Starting at $15.99 depending on weight of shipment ordered.

                        </p>

                        <p>
                        <h6> 1-Business Day shipping (1 business day guaranteed)</h6>
                        Order by 12pm Eastern Time for delivery next business day. <br>
                        Excluding weekends. Not available at P.O. Boxes.<br>
                        Starting at $19.99 depending on weight of shipment ordered.

                        <br>
                        We may change these shipping charges from time to time in the future. Any such changes will be posted here. We advise you to check back frequently to see any updates or changes.

                        </p>
 <p>
                        <h6>International Orders:</h6>
                        Currently Long Island Fragrances LLC is operating ONLY in U.S. And all the orders and shipments will be only in U.S.
                        </p>

                       <br>

                    </div>
                </div>
            </div><!-- End .container -->
        </div><!-- End .login-page section-bg -->
    </main>

@stop
@section('js')

@stop
