$(document).ready(function() {
    $(".invoke").click(function() {
        var clickBtnValue = $(this).val();
              var name = this.name;
              var inputs = $('input[name='+name+']').map(function(){
                 var obj = {};
                  obj[this.id] = this.value;
                 return obj;
              }).get();
              var myInputs = JSON.stringify(inputs);
              var ajaxurl = 'ajax.php',
              data =  {'action': clickBtnValue,'name':this.name,'inputs':myInputs};
              $.post(ajaxurl, data, function (response) {
                console.log(response);
                $("#divr"+name).empty();
                $("#divr"+name).append(response);
                $("table").tablesorter();
              });
               
    });
    $(".reset").click(function() {
        var clickBtnValue = $(this).val();
              var name = this.name;
              $("#divr"+name).empty();
             
    });
});