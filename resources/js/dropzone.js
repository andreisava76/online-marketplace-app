import Dropzone from "dropzone";
import $ from "jquery";
import axios from "axios";

$(document).ready(function () {
    console.log(constants.url);
    // Dropzone.autoDiscover = false;
    let token = $('meta[name="csrf-token"]').attr('content');
    let myDropzone = new Dropzone('#dropzoneDragArea', {
        url: constants.url,
        previewsContainer: 'div.dropzone-previews',
        addRemoveLinks: true,
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 100,
        maxFiles: 100,
        thumbnailWidth: 300,
        acceptedFiles: ".jpeg,.jpg,.png",
        params: {
            _token: token
        },
        init: function () {
            let submitButton = document.querySelector("#submit");
            myDropzone = this;
            submitButton.addEventListener('click', function () {
                myDropzone.processQueue();
            });
            this.on("complete",function (){
                if(this.getQueuedFiles().length===0 && this.getUploadingFiles().length===0)
                {
                    var _this= this;
                    _this.removeAllFiles();
                }
            })

            // let myDropzone = this;
            // //form submission code goes here
            $("form[name='listing-form']").submit(function (event) {
            //     //Make sure that the form isn't actully being sent.
                event.preventDefault();
                URL = $("#listing-form").attr('action');
                let formData = $('#listing-form').serialize();

                console.log(formData);
                axios.post(constants.url, {formData}).then((data) => {
                    console.log(data);
                }).catch((err) => {
                    console.log(err);
                })
                // $.ajax({
                //     type: 'POST',
                //     url: URL,
                //     data: formData,
                //     success: function (result) {
                //         if (result.status === "success") {
                //             // fetch the useid
                //             // let userid = result.user_id;
                //             // $("#userid").val(userid); // inseting userid into hidden input field
                //             //process the queue
                //             myDropzone.processQueue();
                //         } else {
                //             console.log("error");
                //         }
                //     }
                // })
            })
        }
    })
})
