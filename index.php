<?php
$local_portal_domain = 'http://www.propertyfinder.ae';
//print_r($_GET);
//home price values in AED
$minHomePrice = 0;
$maxHomePrice = 10000000;
$defaultHomePrice = 2500000;

//down payment values in %
$minDownPayment = 0;
$maxDownPayment = 80;
$defaultDownPayment = 25;

$defaultDownPaymentValue = $defaultHomePrice * ($defaultDownPayment / 100);

//interest rate values in %
$minInterestRate = 1;
$maxInterestRate = 12;
$defaultInterestRate = 3.99;

//long term values in years
$minLongTerm = 5;
$maxLongTerm = 30;
$defaultLongTerm = 25;
if (isset($_GET)) {
    extract($_GET);
    if (isset($hp) && is_numeric($hp) && $hp > $minHomePrice && $hp <= $maxHomePrice) {
        $defaultHomePrice = $hp;
        if (isset($dp) && is_numeric($dp)) {
            $minDownPaymentValue = ($minDownPayment / 100) * $hp;
            $maxDownPaymentValue = ($maxDownPayment / 100) * $hp;
            $userDownPayment = ($dp / $hp) * 100;
            if ($userDownPayment >= $minDownPayment && $userDownPayment <= $maxDownPayment) {
                $defaultDownPayment = $userDownPayment;
                $defaultDownPaymentValue = $dp;
            }
        }
    }

    if (isset($ir) && (is_float($ir) || is_numeric($ir)) && $ir >= $minInterestRate && $ir <= $maxInterestRate)
        $defaultInterestRate = $ir;
    if (isset($y) && is_numeric($y) && $y >= $minLongTerm && $y <= $maxLongTerm)
        $defaultLongTerm = $y;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Js Test</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
    <link href="favicon-32.png" rel="shortcut icon" type="image/x-icon"/>

    <script src="vendor/jquery/dist/jquery.min.js"></script>
    <script src="vendor/jquery-ui/jquery-ui.min.js"></script>
    <script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="vendor/bootstrapvalidator/dist/js/bootstrapValidator.min.js"></script>
    <script src="vendor/bootstrap-slider/bootstrap-slider.js"></script>
    <script src="vendor/prefixfree/prefixfree.min.js"></script>
    <script src="vendor/jquery-price-format/jquery.price_format.min.js"></script>
    <script src="vendor/slick-carousel/slick/slick.min.js"></script>
    <script src="vendor/readmore.js/readmore.js"></script>
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap-slider/slider.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" type="text/css" href="vendor/bootstrapvalidator/dist/css/bootstrapValidator.css"/>
    <link rel="stylesheet" type="text/css" href="vendor/slick-carousel/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="vendor/slick-carousel/slick/slick-theme.css"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="vendor/html5shiv/dist/html5shiv.js"></script>
    <script src="vendor/respond/src/respond.js"></script>
    <![endif]-->
</head>
<body>
<div id="height-wrapper">
    <div id="main-content" class="container">
        <div class="row">
            <!-- Jquery Sliders-->
            <div class="col-sm-6">
                <h2>Mortgage Calculator</h2>

                <div id="sliders">
                    <form class="form" id="mortgageForm" name="mortgageForm">
                        <div class="control-group form-group">
                            <div class="controls">
                                <div id="label_home_price"><strong>Home price</strong> <span
                                        class="pull-right slider-value"
                                        id="home_price_value"><?= number_format($defaultHomePrice, 0, '', ' ') ?>
                                        <small>AED</small></span></div>
                                <div>
                                    <input type="text" id="home_price" data-slider-min="<?= $minHomePrice ?>"
                                           data-slider-max="<?= $maxHomePrice ?>" data-slider-step="100000"
                                           data-slider-value="<?= $defaultHomePrice ?>" data-slider-tooltip="hide"
                                           value="<?= $defaultHomePrice ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group form-group">
                            <div class="controls">
                                <div id="label_down_payment"><strong>Down Payment</strong> <span
                                        class="pull-right slider-value"><span
                                            id="down_payment_value"><?= number_format($defaultDownPaymentValue, 0, '', ' ') ?>
                                            <small>AED</small></span> / <span
                                            id="down_payment_percent"><?= $defaultDownPayment ?> %</span></span></div>
                                <div>
                                    <input type="text" id="down_payment" data-slider-min="<?= $minDownPayment ?>"
                                           data-slider-max="<?= $maxDownPayment ?>" data-slider-step="1"
                                           data-slider-value="<?= $defaultDownPayment ?>" data-slider-tooltip="hide"
                                           value="<?= $defaultDownPayment ?>"/>
                                    <input type="hidden" id="calculated_loan_amount" value=""/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group form-group">
                            <div class="controls">
                                <div id="label_interest_rate"><strong>Interest rate</strong> <span
                                        class="pull-right slider-value"
                                        id="interest_rate_value"><?= $defaultInterestRate ?> %</span></div>
                                <div>
                                    <input type="text" id="interest_rate" data-slider-min="<?= $minInterestRate ?>"
                                           data-slider-max="<?= $maxInterestRate ?>" data-slider-step="0.01"
                                           data-slider-value="<?= $defaultInterestRate ?>" data-slider-tooltip="hide"
                                           value="<?= $defaultInterestRate ?>"/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group form-group">
                            <div class="controls">
                                <div id="label_long_term"><strong>Long term</strong> <span
                                        class="pull-right slider-value" id="long_term_value"><?= $defaultLongTerm ?>
                                        years</span></div>
                                <div>
                                    <input type="text" id="long_term" data-slider-min="<?= $minLongTerm ?>"
                                           data-slider-max="<?= $maxLongTerm ?>" data-slider-step="5"
                                           data-slider-value="<?= $defaultLongTerm ?>" data-slider-tooltip="hide"
                                           value="<?= $defaultLongTerm ?>"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Google Donut Chart-->
            <div class="col-sm-6">
                <h2 class="text-center">Your monthly payment is estimated at</h2>

                <div class="monthly-payment text-center"><span id="monthly_payment_calculated"></span>
                    <small>AED/month</small>
                </div>
                <div id="donutchart" class="text-center" style="width: 100%; height: 250px;"></div>
                <div class="col-sm-12 text-center">
                    <input type="hidden" name="monthly_payment" id="monthly_payment" value=""/>
                    <a rel="no-follow" role="button" class="btn btn-primary btn-lg col-md-offset-2 col-md-8"
                       href="contact-form.php" data-toggle="modal" data-target="#ajaxModal">Request free advice</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-sm-push-6" id="payment-breakdown">
                <div id="read-more-content">
                    <h2 class="text-center">Payment Breakdown</h2>

                    <div class="row">
                        <div class="col-xs-6"><label>Principal</label></div>
                        <div class="col-xs-6 text-right"><span id="display_principal">0</span>
                            <small> AED</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6"><label>Interest</label></div>
                        <div class="col-xs-6 text-right"><span id="display_interest">0</span>
                            <small> AED</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6"><label><strong>Monthly Payment</strong></label></div>
                        <div class="col-xs-6 text-right"><strong><span id="display_monthly_payment">0</span>
                                <small> AED</small>
                            </strong></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6"><label><strong>Loan Amount</strong></label></div>
                        <div class="col-xs-6 text-right"><strong><span id="display_loan_amount">0</span>
                                <small> AED</small>
                            </strong></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-6"><label>Dubai land department fee</label></div>
                        <div class="col-xs-6 text-right"><span id="display_land_dept_fee">0</span>
                            <small> AED</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6"><label>Registration fee</label></div>
                        <div class="col-xs-6 text-right"><span id="display_registration_fee">0</span>
                            <small> AED</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6"><label>Mortgage Registration</label></div>
                        <div class="col-xs-6 text-right"><span id="display_mortgage_registration">0</span>
                            <small> AED</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6"><label>Broker commission</label></div>
                        <div class="col-xs-6 text-right"><span id="display_broker_commission">0</span>
                            <small> AED</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6"><label>Mortgage processing</label></div>
                        <div class="col-xs-6 text-right"><span id="display_mortgage_processing">0</span>
                            <small> AED</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6"><label>Conveyance</label></div>
                        <div class="col-xs-6 text-right"><span id="display_conveyance">0</span>
                            <small> AED</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6"><label>Valuation</label></div>
                        <div class="col-xs-6 text-right"><span id="display_valuation">0</span>
                            <small> AED</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-6"><label>Down payment</label></div>
                        <div class="col-xs-6 text-right"><span id="display_down_payment">0</span>
                            <small> AED</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6"><label>Total Extra</label></div>
                        <div class="col-xs-6 text-right"><span id="display_total_extra">0</span>
                            <small> AED</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-6"><label><strong>Required Upfront</strong></label></div>
                        <div class="col-xs-6 text-right"><strong><span id="display_required_upfront">0</span>
                                <small> AED</small>
                            </strong></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6"><label>Total paid interest</label></div>
                        <div class="col-xs-6 text-right"><span id="display_total_paid_interest">0</span>
                            <small> AED</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-sm-pull-6 text-justify">
                <h2>What you should know</h2>

                <p>Buying your dream home, investment property or refinancing your existing loan can be a daunting
                    process, particularly in a new country where rules, regulations and language can be a barrier.</p>

                <p>The UAE mortgage landscape can be complicated and confusing. Getting the right advice is key to
                    saving time, money and potentially big headaches in the future. A professional mortgage broker can
                    guide you through and get you a loan that best suits your unique circumstances. Sometime the right
                    mortgage broker can help even if your bank says they can't.</p>

                <p><a rel="no-follow" role="button" href="contact-form.php" data-toggle="modal"
                      data-target="#ajaxModal">Contact us</a> for an obligation free home loan assessment. You'll be
                    glad you did.</p>
            </div>
        </div>
        <div class="row logos">
            <div class="col-xs-3"><img class="img-responsive" src="images/logo_0000_standardchartered.png" alt="Standard-Chartered-Bank"
                                       title="Standard-Chartered-Bank" /></div>
            <div class="col-xs-3"><img class="img-responsive" src="images/logo_0001_emiratesnbd.png" alt="Emirates NBD Bank"
                                       title="Emirates NBD Bank" /></div>
            <div class="col-xs-3"><img class="img-responsive" src="images/logo_0002_cbd.png" alt="commerce bank of dubai"
                                       title="commerce bank of dubai" /></div>
            <div class="col-xs-3"><img class="img-responsive" src="images/logo_0003_rakbank.png" alt="RAK logo en"
                                       title="32289RAK logo en" /></div>
            <div class="col-xs-3"><img class="img-responsive" src="images/logo_0004_rbs.png" alt="Royal Bank of Scotland"
                                       title="Royal Bank of Scotland"/></div>
            <div class="col-xs-3"><img class="img-responsive" src="images/logo_0005_natwest.png" alt="NatWest"
                                       title="NatWest"/></div>
            <div class="col-xs-3"><img class="img-responsive" src="images/logo_0006_nbad.png" alt="National Bank of Abu Dhabi"
                                       title="National Bank of Abu Dhabi"/></div>
            <div class="col-xs-3"><img class="img-responsive" src="images/logo_0007_emirates-money.png" alt="emirates money"
                                       title="emirates money"/></div>
            <div class="col-xs-3"><img class="img-responsive" src="images/logo_0008_nedbank.png" alt="NED Bank"
                                       title="NED Bank"/></div>
        </div>
        <div id="ajaxModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ajaxModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Content will be loaded here through ajax -->
                </div>
            </div>
        </div>
    </div>
</div>
<footer id="main-footer">
    <div class="container">
        <p class="text-center">
            <small>Copyright <span>-</span> <br / class="shows"> All rights reserved <?= date('Y') ?></small>
        </p>
    </div>
</footer>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="js/index.js"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages: ["corechart"]});
    google.setOnLoadCallback(drawChart);
</script>
</body>
</html>