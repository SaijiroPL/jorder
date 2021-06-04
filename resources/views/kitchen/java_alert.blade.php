
<div class="alert_modal_content">
    <div class="container-fluid" style="position: sticky; top: 0;">
        <div class="ex_co_modal_header">
            <div class="col-sm-10" style="padding: 24px 0 0 30px;">

            </div>
            <div class="col-sm-2" style="padding: 18px 0 0 0px;">
                <p class="ex_co_right_close" data-dismiss="modal" onclick="alert_modal_close()">
                    <img src="{{ asset('img/Group1101.png') }}" style="width: 20px;height: 20px;">
                </p>
            </div>
        </div>
    </div>
    <div class="container-fluid" style="position: sticky; top: 0;">
        <div class="ex_co_modal_header">
            <div class="col-sm-12" style="text-align: center;">
                <p style="font-size: 18px;">There is no data for reprint!</p>
            </div>
        </div>
    </div>
</div>

<script>

    var parentURL = window.parent.location.href;

    //modal_close
    function alert_modal_close()
    {
        $("#java-alert").modal("hide");
        //window.location.replace(parentURL);
    }

</script>