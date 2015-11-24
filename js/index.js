/**
 * Created by Faiz on 02-03-2015.
 */
$(function() {

    $('#read-more-content').readmore({
        moreLink: '<div id="read-more"> <a href="#"> See details </a> <span class="glyphicon glyphicon-traingle-bottom"></span></div>',
        lessLink: '<div id="read-less"><a href="#"> Show less <span class="glyphicon glyphicon-traingle-up"></span> </a></div>',
        embedCSS: false,
        speed: 300,
        afterToggle: function(trigger, element, expanded) {
            if(! expanded) { // The "Close" link was clicked
                $('html, body').animate( { scrollTop: element.offset().top }, {duration: 100 } );
            }
        }
    });

    var updateDownPayment = function(){
        var hp = parseInt(homePrice.getValue());
        var dp = parseInt(downPayment.getValue())/100;
        var ir = parseFloat(intRate.getValue() / 100).toFixed(2);
        var lt = parseInt(longTerm.getValue());
        $('#home_price_value').text(hp).priceFormat({
            prefix: '',
            suffix: '  <small>AED</small>',
            centsLimit: 0,
            thousandsSeparator: ' '
        });
        $('#down_payment_value').text(parseInt(hp * dp)).priceFormat({
            prefix: '',
            suffix: ' <small> AED</small>',
            centsLimit: 0,
            thousandsSeparator: ' '
        });
        $('#down_payment_percent').text(parseInt(downPayment.getValue()) + ' %');
        $('#calculated_loan_amount').val(parseInt(hp * (1 - dp)));
        updateChart(hp, dp, ir, lt);
    }
    var updateInterestVal = function(){
        var hp = parseInt(homePrice.getValue());
        var dp = parseInt(downPayment.getValue())/100;
        var ir = parseFloat(intRate.getValue()/100).toFixed(2);
        var lt = parseInt(longTerm.getValue());
        $('#interest_rate_value').text(parseFloat(intRate.getValue()).toFixed(2) + ' %');
        $('#interest_rate').val(parseFloat(intRate.getValue()).toFixed(2));
        updateChart(hp, dp, ir, lt);
    }
    var updateTermVal = function(){
        var hp = parseInt(homePrice.getValue());
        var dp = parseInt(downPayment.getValue())/100;
        var ir = parseFloat(intRate.getValue()/100).toFixed(2);
        var lt = parseInt(longTerm.getValue());
        $('#long_term_value').text(parseInt(lt) + ' years');
        updateChart(hp, dp, ir, lt);
    }

    var homePrice = $('#home_price').slider()
        .on('slide', updateDownPayment)
        .data('slider');

    var downPayment = $('#down_payment').slider()
        .on('slide', updateDownPayment)
        .data('slider');

    var intRate = $('#interest_rate').slider()
        .on('slide', updateInterestVal)
        .data('slider');

    var longTerm = $('#long_term').slider()
        .on('slide', updateTermVal)
        .data('slider');

    $('#home_price_value').text(parseInt(homePrice.getValue())).priceFormat({
        prefix: '',
        suffix: ' <small>AED</small>',
        centsLimit: 0,
        thousandsSeparator: ' '
    });
    $('#down_payment_value').text(parseInt(homePrice.getValue()) * (parseInt(downPayment.getValue())/100)).priceFormat({
        prefix: '',
        suffix: ' <small> AED</small>',
        centsLimit: 0,
        thousandsSeparator: ' '
    });

    var homePriceValue = parseInt(homePrice.getValue());
    var downPaymentValue = parseInt(downPayment.getValue())/100;
    var interestRateValue = parseFloat(intRate.getValue()/100).toFixed(2);
    var longTermValue = parseInt(longTerm.getValue());

    var loanAmount = homePriceValue - (homePriceValue * downPaymentValue);
    $('#calculated_loan_amount').val(loanAmount);
    $('#down_payment_percent').text(parseInt(downPayment.getValue()) + ' %');
    $('#interest_rate_value').text(parseFloat(intRate.getValue()).toFixed(2) + ' %');
    $('#long_term_value').text(parseInt(longTerm.getValue()) + ' years');

    var monthlyPayment = calculate(homePriceValue, downPaymentValue, longTermValue, interestRateValue);
    $('#monthly_payment').val(monthlyPayment);
    $('#monthly_payment_calculated').text(currencyFormat(monthlyPayment, 0));
    var interest = parseFloat((41.81/100) * monthlyPayment.toFixed(0));
    var principal = parseFloat((58.19/100) * monthlyPayment.toFixed(0));
    drawChart(interest, principal);

    $('.logos').slick({
        autoplay: true,
        speed: 1000,
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 1
    });
});

function updateChart(hpv, dpv, irv, ltv){
    var monthlyPayment = calculate(hpv, dpv, ltv, irv);
    var loanAmt   = hpv - (hpv * dpv);

    $('#monthly_payment').val(monthlyPayment.toFixed(2));
    $('#down_payment_value').text(hpv * dpv).priceFormat({
        prefix: '',
        suffix: ' <small> AED</small>',
        centsLimit: 0,
        thousandsSeparator: ' '
    });
    $('#monthly_payment_calculated').text(currencyFormat(monthlyPayment, 0));

    var interest = parseFloat(((41.81/100) * monthlyPayment).toFixed(0));
    var principal = parseFloat(((58.19/100) * monthlyPayment).toFixed(0));
    drawChart(interest, principal);
}

function drawChart(interestAmt, principalAmt) {
    var data = google.visualization.arrayToDataTable([
        ['Task', 'Monthly Payments'],
        ['Interest', interestAmt],
        ['Principal', principalAmt]
    ]);

    var options = {
        legend: {position: 'bottom', alignment: 'center', maxLines: 1, textStyle: {color: 'black', fontSize: 16, bold: true}},
        pieHole: 0.5,
        pieSliceText: 'none',
        slices: {0: {color: '#3bafda'}, 1: {color: '#c80529'}},
        tooltip: 'none'
    };

    var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
    chart.draw(data, options);
}

function  calculate(value, downPayment, years, interest){
    var loan   = value - (value * downPayment);
    var numPayments  = years * 12;
    var monthlyInterest = interest / 12;
    var term   = Math.pow((1 + monthlyInterest), numPayments);
    var monthlyPayment = loan * (monthlyInterest * term / (term - 1));
    var interest = parseFloat(((41.81/100) * monthlyPayment).toFixed(0));
    var principal = parseFloat(((58.19/100) * monthlyPayment).toFixed(0));

    // display payment breakdown
    $('#display_principal').text(currencyFormat(principal, 0));
    $('#display_interest').text(currencyFormat(interest, 0));
    $('#display_monthly_payment').text(currencyFormat(monthlyPayment, 0));
    $('#display_loan_amount').text(currencyFormat(loan, 0));

    var landDeptFee   = (value * 0.04) + 540;
    var registrationFee   = 4000;
    var mortgageRegistration   = loan * (0.25/100) + 10;
    var brokerCommission   = value * 0.02;
    var mortgageProcessing   = loan * 0.01;
    var conveyance   = 8000;
    var valuation   = 3000;
    $('#display_land_dept_fee').text(currencyFormat(landDeptFee, 0));
    $('#display_registration_fee').text(currencyFormat(registrationFee, 0));
    $('#display_mortgage_registration').text(currencyFormat(mortgageRegistration, 0));
    $('#display_broker_commission').text(currencyFormat(brokerCommission, 0));
    $('#display_mortgage_processing').text(currencyFormat(mortgageProcessing, 0));
    $('#display_conveyance').text(currencyFormat(conveyance, 0));
    $('#display_valuation').text(currencyFormat(valuation, 0));

    var downPaymentValue   = value * downPayment;
    var totalExtra   = landDeptFee + registrationFee + mortgageRegistration + brokerCommission + mortgageProcessing + conveyance + valuation;
    $('#display_down_payment').text(currencyFormat(downPaymentValue, 0));
    $('#display_total_extra').text(currencyFormat(totalExtra, 0));

    $('#display_required_upfront').text(currencyFormat((downPaymentValue + totalExtra), 0));
    var totalPaidInterest = monthlyPayment * term * 12;
    $('#display_total_paid_interest').text(currencyFormat(totalPaidInterest, 0));

    return  monthlyPayment;
}

function currencyFormat (num, precision) {
    return num.toFixed(precision).replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1 ")
}