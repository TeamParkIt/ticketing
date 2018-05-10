    <div class="row">
        <div class='col-sm-6'>
            <div class="form-group">
                <div class='input-group date' id='datetimepicker1'>
                    <input type='text' class="form-control" name="dateTime" required="" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar open-datetimepicker"></span>
                    </span>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker({
                	format: 'YYYY-MM-DD HH:mm'
                });
                $('#datetimepicker1 input').click(function(event){
   					$('#datetimepicker1 ').data("DateTimePicker").show();
				});
            });

        </script>
    </div>