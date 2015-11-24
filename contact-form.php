<form id="formLeads" data-async class="form-horizontal" method="post" role="form">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Apply for a mortgage now</h4>
    </div>
    <div class="modal-body">
        <p>Please enter your details, a mortgage specialist will contact you as soon as possible.</p>
        <div class="form-group">
            <label class="control-label col-sm-3" for="sender_name">Name</label>
            <div class="col-sm-9">
                <input type="text" name="sender_name" id="sender_name" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3" for="sender_email">Email address</label>
            <div class="col-sm-9">
                <input type="text" name="sender_email" id="sender_email" value="" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3" for="sender_phone">Phone number</label>
        
            <div class="col-sm-9">
                <input type="text" name="sender_phone" id="sender_phone" value="" class="form-control"/>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Contact me</button>
    </div>
</form>
<script type="text/javascript">
$(function () {
	var currentLanguage = $('body').attr('data-lang');
	$('#formLeads').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                sender_name: {
                    message: 'The name is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The name is required'
                        },
                        stringLength: {
                            min: 3,
                            max: 200,
                            message: 'The name must be more than 3 and less than 200 characters long'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9\-_\. ]+$/,
                            message: 'Only alphanumeric, dot, hyphen and underscore are accepted'
                        }
                    }
                },
                sender_email: {
                    validators: {
                        notEmpty: {
                        message: 'The email address is required and can\'t be empty'
						},
						emailAddress: {
							message: 'The input is not a valid email address'
						}
                    }
                },
                sender_phone: {
                    validators: {
                        notEmpty: {
                            message: 'The phone number is required'
                        },
                        regexp: {
                            regexp: /^[0-9\- \+]+$/,
                            message: 'Only digits, hyphen and plus are accepted'
                        }
                    }
                }
            }
        }).on('success.form.bv', function(e){
				// Prevent form submission
				e.preventDefault();
                var hp = $('#home_price').val();
                var dp = $('#down_payment').val();
                var ir = $('#interest_rate').val();
                var lt = $('#long_term').val();
                var mp = $('#monthly_payment').val();
				var $form			= $(e.target),
					validator 		= $form.data('bootstrapValidator'),
					submitButton	= validator.getSubmitButton(),
                    formData = $form.serialize() + '&hp=' + hp + '&dp=' + dp + '&ir=' + ir + '&lt=' + lt + '&mp=' + mp;
				$.ajax({
					type: $form.attr('method'),
					url: 'send-data.php',
					data: formData,
					dataType: 'json',
					 
					success: function(data, status) {
						if(!data.error)
						{
                            var html = ''
                            html += '<div class="modal-header">';
                            html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
                            html += '</div>';
                            html += '<div class="modal-body">';
                            html += '<h1 class="modal-title text-success text-center" id="myModalLabel">Thank you!</h1>';
                            html += '<h2>One of our mortgage specialist will contact you quickly.</h2>';
                            html += '</div>';
							$('#ajaxModal .modal-content').html(html);
						}
						else
						{
							$('#ajaxModal .modal-content').html('<div class="modal-body"><div class="alert alert-danger">There was a problem while submitting your request. Please try again later.</div></div>');
						}
						setTimeout(function(){$('#ajaxModal').modal('hide');},10000);
					}
				});
			});
	$('body').on('hidden.bs.modal', function(e) {
		$(e.target).removeData('bs.modal').find('.modal-content').empty();
	});
});
</script>