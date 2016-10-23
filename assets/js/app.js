var TechResizer = TechResizer || {};

window.url = window.location.href;
window.host = window.location.host;

$.extend(TechResizer, {
    ajaxRequest: function(form, spinnerTarget)
    {
        var alertTarget = $("div#alertBox");
        var url = form.attr('action');
        var method = form.attr('method');
        var formData = new FormData(form[0]);

        var request = $.ajax({
            url: url,
            type: method,
            data: formData,
            dataType: "json",
            //encode: true,
            processData: false,
            contentType: false,
            cache: false
        });

        request.success(function(json)
        {
            TechResizer.RenderSpinner(spinnerTarget, 'stop');

            if(json.status == 'error')
            {
                var errorMsg = json.response;

                TechResizer.RenderAlert('warning', errorMsg, 'Error!');
            }
            else if(json.status == 'success')
            {
                var successMsg = json.response;

                TechResizer.RenderAlert('success', successMsg, 'Success!');

                $('body').append('<iframe style="display:none;" src="download.php?file=' + json.info.zipFileName + '"></iframe>');
            }
            else
            {
                var responseMsg = json.response;

                TechResizer.RenderAlert('warning', responseMsg, 'Error!');
            }
        });

        request.fail(function(errorresult)
        {
            TechResizer.RenderSpinner(spinnerTarget, 'stop');

            console.log(errorresult);


            var statusMsg = 'There was an problem making this request, please try again!';

            TechResizer.RenderAlert('error', statusMsg, 'Error!');
        });

    },
    RenderSpinner: function(spinnerTarget, action)
    {
        var buttonLoader = $('button#' + spinnerTarget);
        var buttonVal =  '<i class="fa fa-spinner"></i> Batch Process';
        var spinner = '<i style="font-size: 16px;" class="fa fa-spinner fa-spin"></i> Processing Please Wait...';

        return (action == 'stop') ? buttonLoader.removeAttr('disabled').html(buttonVal)
            : buttonLoader.attr('disabled','disabled').html(spinner);
    },
    RenderAlert: function(type, Msg, emphasize, alertTarget)
    {
        var icon = 'fa fa-remove';


        alertTarget =  (typeof(alertTarget) != "undefined" && alertTarget !== null) ? alertTarget : $('div#alertBox');

        if (type == 'error')
        {
            icon = 'fa fa-exclamation-circle';
        }
        else if (type == 'success')
        {
            icon = 'fa fa-thumbs-o-up';
        }
        else if (type == 'warning')
        {
            icon = 'fa fa-exclamation-circle';
        }
        else if (type == 'info')
        {
            icon = 'fa fa-info';
        }

        type = (type == 'error') ? 'danger': type;

        var message = '<div class="alert alert-' + type + ' alert-dismissable">' +
            '<button type="button" class="close" data-dismiss="alert">' +
            '&times;</button>' +
            '<div  class="fontmain text-center"><i class="' + icon + '"></i>' +
            '<strong>  ' + emphasize + ' </strong>' + Msg + '</div></div>';

        setTimeout(function() {
            alertTarget.html(message).fadeTo(7000, 100).slideUp(3000, function(){
                $(this).children().remove();
                $(this).removeAttr('style');
            });
        }, 100);
    },

    PickAFolder: function()
    {
        var files,
            file,
            extension,
            input = document.getElementById("fileUrl"),
            output = document.getElementById("fileOutput"),
            holder = document.getElementById("fileHolder");

        input.addEventListener("change", function (e) {
            files = e.target.files;
            output.innerHTML = "";

            for (var i = 0, len = files.length; i < len; i++) {
                file = files[i];
                extension = file.name.split(".").pop();
                output.innerHTML += "<li class='file type-" + extension + "'>" + file.name + " (" +  Math.floor(file.size/1024 * 100)/100 + "KB)</li>";
            }
        }, false);



        // This event is fired as the mouse is moved over an element when a drag is occuring
        input.addEventListener("dragover", function (e) {
            holder.classList.add("highlightOver");
        });

        // This event is fired when the mouse leaves an element while a drag is occuring
        input.addEventListener("dragleave", function (e) {
            holder.classList.add("highlightOver");
        });

        // Fires when the user releases the mouse button while dragging an object.
        input.addEventListener("dragend", function (e) {
            holder.classList.remove("highlightOver");
        });

        // The drop event is fired on the element where the drop was occured at the end of the drag operation
        input.addEventListener("drop", function (e) {
            holder.classList.remove("highlightOver");
        });

    },
    ToggleInputWithClass: function(input)
    {
        var inputs = $('input.' + input);

        (inputs.prop('disabled') == false) ? inputs.prop('disabled',true) : inputs.prop('disabled',false);
    }
});