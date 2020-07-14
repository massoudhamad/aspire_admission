var submittedlist, approvedlist, userlist, tcu_check_status, add_applicant_tcu, populate_dashbboard_tcu, payment_list, allapplicants, pgsubmitlist;
$(document).ready(function() {
	submittedlist = $("#submitlist").DataTable({
		"ajax": "data/submittedlist.php",
                "dom": 'Blfrtip',
                "buttons":[
                        {
                            extend:'excel',
                            title: 'List of Applicants who Submit their Applications without Approved',
                            footer:false,
                            exportOptions:{
                                columns: [0, 1, 2, 3,5,6,7]
                            }
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'List of Applicants who Submit their Applications without Approved',
                            footer: false,
                            exportOptions: {
                                columns: [0, 1, 2, 3,5,6,7]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'List of Applicants who Submit their Applications without Approved',
                            footer: true,
                           exportOptions: {
                                columns: [0, 1, 2, 3,5,6,7]
                            },
                            
                        }

                        ],
		"order": []
    });
    
    //pg_list
        submittedlist = $("#pgsubmitlist").DataTable({
            "ajax": "data/submitted_list_pg.php",
            "dom": 'Blfrtip',
            "buttons": [
                {
                    extend: 'excel',
                    title: 'List of Applicants who Submit their Applications without Approved',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 5, 6, 7]
                    }
                },
                ,
                {
                    extend: 'print',
                    title: 'List of Applicants who Submit their Applications without Approved',
                    footer: false,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 5, 6, 7]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: 'List of Applicants who Submit their Applications without Approved',
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 5, 6, 7]
                    },

                }

            ],
            "order": []
        });

    //pg_list
    submittedlist = $("#foreignsubmitlist").DataTable({
        "ajax": "data/submitted_list_foreign.php",
        "dom": 'Blfrtip',
        "buttons": [
            {
                extend: 'excel',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer: false,
                exportOptions: {
                    columns: [0, 1, 2, 3, 5, 6, 7]
                }
            },
            ,
            {
                extend: 'print',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer: false,
                exportOptions: {
                    columns: [0, 1, 2, 3, 5, 6, 7]
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer: true,
                exportOptions: {
                    columns: [0, 1, 2, 3, 5, 6, 7]
                },

            }

        ],
        "order": []
    });


        approvedlist = $("#approvedlist").DataTable({
		"ajax": "data/approvedlist.php",
                "dom": 'Blfrtip',
                "buttons":[
                        {
                            extend:'excel',
                            title: 'Approved List',
                            footer:false,
                            exportOptions:{
                                columns: [0, 1, 2, 3,5,6,7]
                            }
                        },
                        ,
                        {
                            extend: 'print',
                            title: 'Approved List',
                            footer: false,
                            exportOptions: {
                                columns: [0, 1, 2, 3,5,6,7]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Approved List',
                            footer: true,
                           exportOptions: {
                                columns: [0, 1, 2, 3,5,6,7]
                            },
                            
                        }

                        ],
		"order": []
	});

    allapplicants = $("#allapplicants").DataTable({
        "ajax": "data/allapplicants.php",
        "dom": 'Blfrtip',
        "buttons":[
            {
                extend:'excel',
                title: 'All Applicants List',
                footer:false,
                exportOptions:{
                    columns: [0, 1, 2, 3,5,6,7]
                }
            },
            ,
            {
                extend: 'print',
                title: 'Approved List',
                footer: false,
                exportOptions: {
                    columns: [0, 1, 2, 3,5,6,7]
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'Approved List',
                footer: true,
                exportOptions: {
                    columns: [0, 1, 2, 3,5,6,7]
                },

            }

        ],
        "order": []
    });
        
        
        userlist = $("#userdata").DataTable({
    		"ajax": "data/userlist.php",
                    "dom": 'Blfrtip',
                    "buttons":[
                            {
                                extend:'excel',
                                title: 'List of all Users',
                                footer:false,
                                exportOptions:{
                                    columns: [0, 1, 2, 3,5,6,7]
                                }
                            },
                            ,
                            {
                                extend: 'print',
                                title: 'List of all Users',
                                footer: false,
                                exportOptions: {
                                    columns: [0, 1, 2, 3,5,6,7]
                                }
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'List of all Users',
                                footer: true,
                               exportOptions: {
                                    columns: [0, 1, 2, 3,5,6,7]
                                },
                                
                            }

                            ],
    		"order": []
    	});


    tcu_check_status = $("#tcu_check_status").DataTable({
        "ajax": "data/tcu_check_status.php",
        "dom": 'Blfrtip',
        "buttons":[
            {
                extend:'excel',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer:false,
                exportOptions:{
                    columns: [0, 1, 2, 3,5,6,7]
                }
            },
            ,
            {
                extend: 'print',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer: false,
                exportOptions: {
                    columns: [0, 1, 2, 3,5,6,7]
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer: true,
                exportOptions: {
                    columns: [0, 1, 2, 3,5,6,7]
                },

            }

        ],
        "order": []
    });

    add_applicant_tcu = $("#add_applicant_tcu").DataTable({
        "ajax": "data/add_applicant_tcu.php",
        "dom": 'Blfrtip',
        "buttons":[
            {
                extend:'excel',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer:false,
                exportOptions:{
                    columns: [0, 1, 2, 3,5,6,7]
                }
            },
            ,
            {
                extend: 'print',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer: false,
                exportOptions: {
                    columns: [0, 1, 2, 3,5,6,7]
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer: true,
                exportOptions: {
                    columns: [0, 1, 2, 3,5,6,7]
                },

            }

        ],
        "order": []
    });

    populate_dashbboard_tcu = $("#populate_dashbboard_tcu").DataTable({
        "ajax": "data/populate_dashboard_tcu.php",
        "dom": 'Blfrtip',
        "buttons":[
            {
                extend:'excel',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer:false,
                exportOptions:{
                    columns: [0, 1, 2, 3,5,6,7]
                }
            },
            ,
            {
                extend: 'print',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer: false,
                exportOptions: {
                    columns: [0, 1, 2, 3,5,6,7]
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer: true,
                exportOptions: {
                    columns: [0, 1, 2, 3,5,6,7]
                },

            }

        ],
        "order": []
    });


    payment_list = $("#payment_list").DataTable({
        "ajax": "data/payment_list.php",
        "dom": 'Blfrtip',
        "buttons":[
            {
                extend:'excel',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer:false,
                exportOptions:{
                    columns: [0, 1, 2, 3,5,6,7]
                }
            },
            ,
            {
                extend: 'print',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer: false,
                exportOptions: {
                    columns: [0, 1, 2, 3,5,6,7]
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'List of Applicants who Submit their Applications without Approved',
                footer: true,
                exportOptions: {
                    columns: [0, 1, 2, 3,5,6,7]
                },

            }

        ],
        "order": []
    });
        
        
        

});

