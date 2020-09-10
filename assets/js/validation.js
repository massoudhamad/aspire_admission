$(document).ready(function() {
    $("#register").validate({
        rules: {
            indexYear:{
                required:true
            },
            name: "required",
            indexNumber:{
                required:true,
                minlength:15,
                maxlength:15
            },
            equivalence_number: {
                required: true/* ,
                minlength: 15,
                maxlength: 15 */
            },
            email: {
                required: true,
                email: true
            },
            exam_body: {
            	required: true
            },
            phoneNumber: {
                required: true,
                number: true
            },
            url: {
                required: false,
                url: true
            },
            username: {
                required: true,
                minlength: 6
            },
            password: {
                required: true,
                minlength: 6
            },
            confirm_password: {
                required: true,
                minlength: 6,
                equalTo: "#password"
            },
            agree: "required"
        },
        messages: {
            indexNumber: {
                required: "Please Enter Valid Index Number",
                maxlength: "The Maximum Length is 15",
                minlength:"The Minimum Length is 15"
            },
            equivalence_number: {
                required: "Please Enter Valid Equivalence Number"/* ,
                maxlength: "The Maximum Length is 12",
                minlength: "The Minimum Length is 12" */
            },
            indexYear: {
                required: "Please Select Index Year"
            },
            exam_body: {
                required: "Please Select Exam Body"
            },
            name: "Please enter your name",
            email: "Please enter a valid email address",
            phoneNumber: {
                required: "Please enter your phone number",
                number: "Please enter only numeric value"
            },
            url: {
                url: "Please enter valid url"
            },
            username: {
                required: "Please enter a username",
                minlength: "Your username must consist of at least 6 characters"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long"
            },
            confirm_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 6 characters long",
                equalTo: "Please enter the same password as above"
            },
            agree: "Please accept our policy"
        }
    });
    
      $('#indexNumber').mask('TCCCC/SSSS/RRRR', {'translation': {
            T: {pattern: /[EePpSsUu]/},
            C: {pattern: /[0-9]/},
            S: {pattern: /[0-9]/},
            R: {pattern: /[0-9]/}
        }
    });

    $('#indexNumberO').mask('TCCCC/SSSS', {'translation': {
            T: {pattern: /[EePpSsUu]/},
            C: {pattern: /[0-9]/},
            S: {pattern: /[0-9]/}
        }
    });

    $('#equivalence_number').mask('TQCCCCCCCCCC', {
                'translation': {
            T: {pattern: /[E]/},
            Q: {pattern:/[Q]/},
            C: {pattern: /[0-9]/},
            S: {pattern: /[0-9]/}
        }
    });
});

