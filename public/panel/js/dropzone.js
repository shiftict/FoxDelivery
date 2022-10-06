"use strict";
// Class definition

var KTDropzoneDemo = function () {
    // Private functions
    var demo1 = function () {
        // multiple file upload
        $('#vendorAttached').dropzone({
            url: "attacheds", // Set the url for your upload script location
            paramName: "file", // The name that will be used to transfer the file
            maxFiles: 10,
            maxFilesize: 10, // MB
            addRemoveLinks: true,
            acceptedFiles: "image/*,application/pdf",
            // acceptedFiles: "image/*,application/pdf,.doc,.docx,.xls,.xlsx,.csv,.tsv,.ppt,.pptx,.pages,.odt,.rtf", /*is this correct?*/
            headers: {
                'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
            },
            accept: function(file, done) {
                if (file.name == "justinbieber.jpg") {
                    done("Naha, you don't.");
                } else {
                    done();
                }
            }
        });
    }
    var demo2 = function () {
        // multiple file upload
        $('#vendorAttacheds').dropzone({
            url: "/fox/public/dashboard/vendor/attacheds", // Set the url for your upload script location
            paramName: "file", // The name that will be used to transfer the file
            maxFiles: 10,
            maxFilesize: 10, // MB
            addRemoveLinks: true,
            acceptedFiles: "image/*,application/pdf", /*is this correct?*/
            headers: {
                'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
            },
            accept: function(file, done) {
                if (file.name == "justinbieber.jpg") {
                    done("Naha, you don't.");
                } else {
                    done();
                }
            }
        });
    }

    return {
        // public functions
        init: function() {
            demo2();
            demo1();
        }
    };
}();

KTUtil.ready(function() {
    KTDropzoneDemo.init();
});
